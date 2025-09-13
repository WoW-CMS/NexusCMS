<?php

namespace App\Services;

use App\Models\Forum;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForumService
{
    /**
     * Obtener categorías principales con sus subforos
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
     * Obtener un foro por slug con sus hilos
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
     * Obtener un hilo con sus posts
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
     * Crear un nuevo hilo con su primer post
     */
    public function createThread(Forum $forum, array $validated)
    {
        $threadSlug = Str::slug($validated['title']);

        // Si el slug ya existe lo hacemos único
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
     * Crear un nuevo post en un hilo
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
