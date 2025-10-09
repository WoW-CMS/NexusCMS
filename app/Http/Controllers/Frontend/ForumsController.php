<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ForumService;
use App\Models\Forum;
use App\Models\Thread;

/**
 * Handles frontend forum functionality.
 *
 * This controller manages forum-related operations such as listing categories,
 * displaying forums and threads, creating new discussion threads, and posting
 * replies. It delegates business logic to the {@see ForumService}.
 *
 * @category Controllers
 * @package  App\Http\Controllers\Frontend
 * @author   NexusCMS <noreply@wow-cms.com>
 * @license  GNU General Public License (GPL)
 * @version  1.0.0
 * @link     https://wow-cms.com
 * @since    1.0.0
 * @api
 */
class ForumsController extends BaseController
{
    /**
     * Forum service instance for handling forum logic.
     *
     * @var ForumService
     * @since 1.0.0
     */
    protected ForumService $forumService;

    /**
     * Default views for forum pages.
     *
     * @var array<string, string> Associative array mapping actions to Blade views
     * @since 1.0.0
     */
    protected $views = [
        'index'         => 'forums.index',
        'forum'         => 'forums.forum',
        'thread'        => 'forums.thread',
        'create_thread' => 'forums.create_thread',
        'create_post'   => 'forums.create_post',
    ];

    /**
     * Create a new ForumsController instance.
     *
     * @param ForumService $forumService The service responsible for forum operations
     * @since 1.0.0
     */
    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    /**
     * Display the forum index with all categories.
     *
     * Retrieves forum categories via the {@see ForumService} and renders the index view.
     *
     * @param Request     $request The current HTTP request
     * @param string|null $view    Optional custom view to render
     * @return View The rendered forum index view
     * @since 1.0.0
     */
    public function index(Request $request, ?string $view = null): View
    {
        $categories = $this->forumService->getCategories();
        return $this->renderView($view ?? $this->views['index'], compact('categories'));
    }

    /**
     * Display a forum with its threads.
     *
     * Loads forum details and threads based on the provided slug.
     *
     * @param string      $slug The forum slug identifier
     * @param string|null $view Optional custom view to render
     * @return View The rendered forum detail view
     * @since 1.0.0
     */
    public function showForum(string $slug, ?string $view = null): View
    {
        $data = $this->forumService->getForumWithThreads($slug);
        return $this->renderView($view ?? $this->views['forum'], $data);
    }

    /**
     * Display a thread with its posts.
     *
     * Retrieves a thread and its associated posts from a specific forum.
     *
     * @param string      $forumSlug  The slug of the parent forum
     * @param string      $threadSlug The slug of the thread
     * @param string|null $view       Optional custom view to render
     * @return View The rendered thread detail view
     * @since 1.0.0
     */
    public function showThread(string $forumSlug, string $threadSlug, ?string $view = null): View
    {
        $data = $this->forumService->getThreadWithPosts($forumSlug, $threadSlug);
        return $this->renderView($view ?? $this->views['thread'], $data);
    }

    /**
     * Show the form for creating a new thread.
     *
     * Loads the forum by slug and renders the create thread view.
     *
     * @param string      $slug The forum slug identifier
     * @param string|null $view Optional custom view to render
     * @return View The rendered create thread form view
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If forum not found
     * @since 1.0.0
     */
    public function createThread(string $slug, ?string $view = null): View
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();
        return $this->renderView($view ?? $this->views['create_thread'], compact('forum'));
    }

    /**
     * Store a newly created thread in the database.
     *
     * Validates the request data, creates a new thread, and redirects
     * to the created thread page.
     *
     * @param Request $request The incoming HTTP request containing thread data
     * @param string  $slug    The forum slug identifier
     * @return \Illuminate\Http\RedirectResponse Redirects to the created thread view
     * @throws \Illuminate\Validation\ValidationException When validation fails
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If forum not found
     * @since 1.0.0
     */
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

    /**
     * Store a newly created post (reply) in the database.
     *
     * Validates the reply content, creates a new post inside the thread,
     * and redirects back to the thread view.
     *
     * @param Request $request    The incoming HTTP request containing post content
     * @param string  $forumSlug  The slug of the parent forum
     * @param string  $threadSlug The slug of the thread
     * @return \Illuminate\Http\RedirectResponse Redirects back to the thread view
     * @throws \Illuminate\Validation\ValidationException When validation fails
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If forum or thread not found
     * @since 1.0.0
     */
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