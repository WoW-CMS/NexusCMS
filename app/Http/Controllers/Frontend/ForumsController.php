<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ForumService;
 
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
    protected ForumService $forumService;

    /** * Default views for the forums */ 
    protected $views = [ 
        'index' => 'forums.index', 
        'forum' => 'forums.forum', 
        'thread' => 'forums.thread', 
        'create_thread' => 'forums.create_thread', 
        'create_post' => 'forums.create_post'
    ];

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    public function index(Request $request, ?string $view = null)
    {
        $categories = $this->forumService->getCategories();
        return $this->renderView($view ?? $this->views['index'], compact('categories'));
    }

    public function showForum(string $slug, ?string $view = null)
    {
        $data = $this->forumService->getForumWithThreads($slug);
        return $this->renderView($view ?? $this->views['forum'], $data);
    }

    public function showThread(string $forumSlug, string $threadSlug, ?string $view = null)
    {
        $data = $this->forumService->getThreadWithPosts($forumSlug, $threadSlug);
        return $this->renderView($view ?? $this->views['thread'], $data);
    }

    public function createThread(string $slug, ?string $view = null)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();
        return $this->renderView($view ?? $this->views['create_thread'], compact('forum'));
    }

    public function storeThread(Request $request, string $slug)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();
        $validated = $request->validate([
            'title'   => 'required|min:3|max:255',
            'content' => 'required|min:10',
        ]);

        $threadSlug = $this->forumService->createThread($forum, $validated);

        return redirect()->route('forums.thread', [
            'forumSlug'  => $forum->slug,
            'threadSlug' => $threadSlug,
        ])->with('success', 'Thread created successfully!');
    }

    public function storePost(Request $request, string $forumSlug, string $threadSlug)
    {
        $forum = Forum::where('slug', $forumSlug)->firstOrFail();
        $thread = Thread::where('slug', $threadSlug)
            ->where('forum_id', $forum->id)
            ->firstOrFail();

        $validated = $request->validate([
            'content' => 'required|min:10',
        ]);

        try {
            $this->forumService->createPost($thread, $validated);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('forums.thread', [
            'forumSlug'  => $forumSlug,
            'threadSlug' => $threadSlug,
        ])->with('success', 'Reply posted successfully!');
    }
}