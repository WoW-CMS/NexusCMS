<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Forum;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Frontend Forums Controller for handling forum functionality
 *
 * This controller handles all forum-related actions including viewing forums,
 * creating and viewing threads, and posting replies.
 *
 * @category Controllers
 * @package  App\Http\Controllers\Frontend
 * @author   NexusCMS <noreply@wow-cms.com>
 * @license  GNU General Public License (GPL)
 * @version  1.0.0
 * @link     wow-cms.com
 */
class ForumsController extends BaseController
{
    /**
     * Default views for the forums
     */
    protected $views = [
        'index' => 'forums.index',
        'forum' => 'forums.forum',
        'thread' => 'forums.thread',
        'create_thread' => 'forums.create_thread',
        'create_post' => 'forums.create_post',
    ];
    
    /**
     * Indicates if the controller uses a model
     *
     * @var bool
     */
    protected $hasModel = false;

    /**
     * Display the forums index page
     *
     * @param Request $request The request instance containing pagination parameters
     * @param string|null $view The view to render
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, ?string $view = null)
    {
        // Get all categories (forums where is_category = true)
        $categories = Forum::where('is_category', true)
            ->orderBy('order')
            ->with(['subforums' => function ($query) {
                $query->orderBy('order');
            }])
            ->get();

        return $this->renderView($view ?? $this->views['index'], [
            'categories' => $categories,
        ]);
    }

    /**
     * Display a specific forum with its threads
     *
     * @param string $slug The forum slug
     * @param string|null $view The view to render
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function showForum(string $slug, ?string $view = null)
    {
        $forum = Forum::where('slug', $slug)
            ->with(['subforums' => function ($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        $threads = Thread::where('forum_id', $forum->id)
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc')
            ->with(['user', 'latestPost.user'])
            ->paginate(20);

        return $this->renderView($view ?? $this->views['forum'], [
            'forum' => $forum,
            'threads' => $threads,
        ]);
    }

    /**
     * Display a specific thread with its posts
     *
     * @param string $forumSlug The forum slug
     * @param string $threadSlug The thread slug
     * @param string|null $view The view to render
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function showThread(string $forumSlug, string $threadSlug, ?string $view = null)
    {
        $forum = Forum::where('slug', $forumSlug)->firstOrFail();
        $thread = Thread::where('slug', $threadSlug)
            ->where('forum_id', $forum->id)
            ->firstOrFail();

        // Increment view count
        $thread->increment('view_count');

        $posts = Post::where('thread_id', $thread->id)
            ->orderBy('created_at')
            ->with('user')
            ->paginate(15);

        return $this->renderView($view ?? $this->views['thread'], [
            'forum' => $forum,
            'thread' => $thread,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form to create a new thread
     *
     * @param string $slug The forum slug
     * @param string|null $view The view to render
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function createThread(string $slug, ?string $view = null)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();

        return $this->renderView($view ?? $this->views['create_thread'], [
            'forum' => $forum,
        ]);
    }

    /**
     * Store a new thread
     *
     * @param Request $request The request object
     * @param string $slug The forum slug
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeThread(Request $request, string $slug)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|min:3|max:255',
            'content' => 'required|min:10',
        ]);

        $threadSlug = Str::slug($validated['title']);

        // Check if slug already exists and make it unique if needed
        $count = Thread::where('slug', $threadSlug)->count();
        if ($count > 0) {
            $threadSlug = $threadSlug . '-' . time();
        }

        DB::transaction(function () use ($forum, $validated, $threadSlug) {
            // Create the thread
            $thread = Thread::create([
                'title' => $validated['title'],
                'slug' => $threadSlug,
                'forum_id' => $forum->id,
                'user_id' => Auth::id(),
            ]);

            // Create the first post
            $post = Post::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'content' => $validated['content'],
                'is_first_post' => true,
            ]);

            // Update thread with first post ID
            $thread->update([
                'first_post_id' => $post->id,
                'latest_post_id' => $post->id,
            ]);

            // Update forum with latest thread ID
            $forum->update([
                'latest_thread_id' => $thread->id,
            ]);
        });

        return redirect()->route('forums.thread', [
            'forumSlug' => $forum->slug,
            'threadSlug' => $threadSlug,
        ])->with('success', 'Thread created successfully!');
    }

    /**
     * Store a new post (reply) in a thread
     *
     * @param Request $request The request object
     * @param string $forumSlug The forum slug
     * @param string $threadSlug The thread slug
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storePost(Request $request, string $forumSlug, string $threadSlug)
    {
        $forum = Forum::where('slug', $forumSlug)->firstOrFail();
        $thread = Thread::where('slug', $threadSlug)
            ->where('forum_id', $forum->id)
            ->firstOrFail();

        // Check if thread is locked
        if ($thread->is_locked) {
            return back()->with('error', 'This thread is locked and cannot be replied to.');
        }

        $validated = $request->validate([
            'content' => 'required|min:10',
        ]);

        DB::transaction(function () use ($thread, $validated) {
            // Create the post
            $post = Post::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'content' => $validated['content'],
            ]);

            // Update thread with latest post ID
            $thread->update([
                'latest_post_id' => $post->id,
            ]);

            // Update forum with latest thread ID (since this thread has new activity)
            $thread->forum->update([
                'latest_thread_id' => $thread->id,
            ]);
        });

        return redirect()->route('forums.thread', [
            'forumSlug' => $forumSlug,
            'threadSlug' => $threadSlug,
        ])->with('success', 'Reply posted successfully!');
    }
}