<?php

namespace App\Services;

use App\Models\Forum;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Service for managing forums, threads, and posts.
 * 
 * Provides methods to:
 * - Retrieve categories and subforums.
 * - Retrieve forums with their threads.
 * - Retrieve threads with their posts.
 * - Create threads and posts.
 */
class ForumService
{
    /**
     * Get main categories with their subforums.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Forum[]
     */
    public function getCategories()
    {
        return Forum::where('is_category', true)
            ->orderBy('order')
            ->with(['subforums' => function ($query) {
                $query->orderBy('order');
            }])
            ->get();
    }

    /**
     * Get a forum by slug with its threads.
     *
     * @param string $slug
     * @return array{forum: Forum, threads: \Illuminate\Contracts\Pagination\LengthAwarePaginator}
     */
    public function getForumWithThreads(string $slug)
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

        return compact('forum', 'threads');
    }

    /**
     * Get a thread with its posts.
     *
     * @param string $forumSlug
     * @param string $threadSlug
     * @return array{forum: Forum, thread: Thread, posts: \Illuminate\Contracts\Pagination\LengthAwarePaginator}
     */
    public function getThreadWithPosts(string $forumSlug, string $threadSlug)
    {
        $forum = Forum::where('slug', $forumSlug)->firstOrFail();
        $thread = Thread::where('slug', $threadSlug)
            ->where('forum_id', $forum->id)
            ->firstOrFail();

        $thread->increment('view_count');

        $posts = Post::where('thread_id', $thread->id)
            ->orderBy('created_at')
            ->with('user')
            ->paginate(15);

        return compact('forum', 'thread', 'posts');
    }

    /**
     * Create a new thread with its first post.
     *
     * @param Forum $forum
     * @param array $validated Validated thread data ['title' => string, 'content' => string]
     * @return string The unique slug of the created thread
     * @throws \Throwable
     */
    public function createThread(Forum $forum, array $validated)
    {
        $threadSlug = Str::slug($validated['title']);

        // Make slug unique if it already exists
        $count = Thread::where('slug', $threadSlug)->count();
        if ($count > 0) {
            $threadSlug .= '-' . time();
        }

        DB::transaction(function () use ($forum, $validated, $threadSlug) {
            $thread = Thread::create([
                'title'    => $validated['title'],
                'slug'     => $threadSlug,
                'forum_id' => $forum->id,
                'user_id'  => Auth::id(),
            ]);

            $post = Post::create([
                'thread_id'     => $thread->id,
                'user_id'       => Auth::id(),
                'content'       => $validated['content'],
                'is_first_post' => true,
            ]);

            $thread->update([
                'first_post_id'  => $post->id,
                'latest_post_id' => $post->id,
            ]);

            $forum->update([
                'latest_thread_id' => $thread->id,
            ]);
        });

        return $threadSlug;
    }

    /**
     * Create a new post in a thread.
     *
     * @param Thread $thread
     * @param array $validated Validated post data ['content' => string]
     * @return void
     * @throws \Exception If the thread is locked
     * @throws \Throwable
     */
    public function createPost(Thread $thread, array $validated)
    {
        if ($thread->is_locked) {
            throw new \Exception('Thread is locked.');
        }

        DB::transaction(function () use ($thread, $validated) {
            $post = Post::create([
                'thread_id' => $thread->id,
                'user_id'   => Auth::id(),
                'content'   => $validated['content'],
            ]);

            $thread->update([
                'latest_post_id' => $post->id,
            ]);

            $thread->forum->update([
                'latest_thread_id' => $thread->id,
            ]);
        });
    }
}
