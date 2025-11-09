<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use App\Services\ForumService;
use App\Models\Forum;
use App\Models\Thread;

class ForumsController extends Controller
{
    protected ForumService $forumService;

    protected array $views = [
        'index'         => 'forums.index',
        'forum'         => 'forums.forum',
        'thread'        => 'forums.thread',
        'create_thread' => 'forums.create_thread',
        'create_post'   => 'forums.create_post',
    ];

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    /**
     * Display the forum index with all categories.
     */
    public function index(Request $request, ?string $view = null): View
    {
        $categories = $this->forumService->getCategories();

        return $this->renderView($view ?? $this->views['index'], compact('categories'));
    }

    /**
     * Display a forum with its threads.
     */
    public function showForum(string $slug, ?string $view = null): View
    {
        $data = $this->forumService->getForumWithThreads($slug);

        return $this->renderView($view ?? $this->views['forum'], $data);
    }

    /**
     * Display a thread with its posts.
     */
    public function showThread(string $forumSlug, string $threadSlug, ?string $view = null): View
    {
        $data = $this->forumService->getThreadWithPosts($forumSlug, $threadSlug);

        return $this->renderView($view ?? $this->views['thread'], $data);
    }

    /**
     * Show the form for creating a new thread.
     */
    public function createThread(string $slug, ?string $view = null): View
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();

        return $this->renderView($view ?? $this->views['create_thread'], compact('forum'));
    }

    /**
     * Store a newly created thread.
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
     * Store a newly created post (reply).
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

    /**
     * Render a Blade view safely.
     *
     * This replaces the renderView() inherited from BaseController.
     */
    protected function renderView(string $view, array $data = []): View
    {
        if (!ViewFacade::exists($view)) {
            abort(404, "View [{$view}] not found.");
        }

        return view($view, $data);
    }
}
