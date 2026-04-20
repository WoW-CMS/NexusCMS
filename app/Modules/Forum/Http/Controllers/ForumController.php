<?php

namespace Modules\Forum\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Forum\Domain\Models\Forum;
use Modules\Forum\Services\ForumService;

class ForumController extends Controller
{
    protected ForumService $forumService;

    protected array $views = [
        'index'         => 'forum::index',
        'forum'         => 'forum::show',
        'thread'        => 'forum::thread',
        'create_thread' => 'forum::create-thread',
    ];

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    public function index(): View
    {
        $categories = $this->forumService->getCategories();

        return view($this->views['index'], compact('categories'));
    }

    public function forum(string $slug): View
    {
        $data = $this->forumService->getForumWithThreads($slug);

        return view($this->views['forum'], $data);
    }

    public function thread(string $forumSlug, string $threadSlug): View
    {
        $data = $this->forumService->getThreadWithPosts($forumSlug, $threadSlug);

        return view($this->views['thread'], $data);
    }

    public function createThread(string $slug): View
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();

        return view($this->views['create_thread'], compact('forum'));
    }

    public function storeThread(Request $request, string $slug)
    {
        $forum = Forum::where('slug', $slug)->where('is_category', false)->firstOrFail();

        $validated = $request->validate([
            'title'   => 'required|min:3|max:255',
            'content' => 'required|min:10',
        ]);

        $threadSlug = $this->forumService->createThread($forum, $validated);

        return redirect()->route('forum.thread', [
            'forumSlug'  => $forum->slug,
            'threadSlug' => $threadSlug,
        ])->with('success', 'Thread created successfully!');
    }

    public function storeReply(Request $request, string $forumSlug, string $threadSlug)
    {
        $data = $this->forumService->getThreadWithPosts($forumSlug, $threadSlug);

        $validated = $request->validate([
            'content' => 'required|min:2',
        ]);

        $this->forumService->createPost($data['thread'], $validated);

        return redirect()->route('forum.thread', [
            'forumSlug'  => $forumSlug,
            'threadSlug' => $threadSlug,
        ])->with('success', 'Reply posted!');
    }
}
