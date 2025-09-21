<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forum;
use App\Models\Thread;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class ForumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create forum categories
        $announcements = Forum::create([
            'name' => 'Announcements',
            'slug' => 'announcements',
            'description' => 'Official announcements about the server and community',
            'is_category' => true,
            'order' => 1,
        ]);

        $general = Forum::create([
            'name' => 'General',
            'slug' => 'general',
            'description' => 'General discussion forums',
            'is_category' => true,
            'order' => 2,
        ]);

        $support = Forum::create([
            'name' => 'Support',
            'slug' => 'support',
            'description' => 'Support forums for technical issues and help',
            'is_category' => true,
            'order' => 3,
        ]);

        // Create subforums
        $news = Forum::create([
            'name' => 'News & Updates',
            'slug' => 'news-updates',
            'description' => 'Latest news and updates about the server',
            'parent_id' => $announcements->id,
            'order' => 1,
        ]);

        $events = Forum::create([
            'name' => 'Events',
            'slug' => 'events',
            'description' => 'Information about upcoming and ongoing events',
            'parent_id' => $announcements->id,
            'order' => 2,
        ]);

        $generalDiscussion = Forum::create([
            'name' => 'General Discussion',
            'slug' => 'general-discussion',
            'description' => 'Discuss anything related to the server',
            'parent_id' => $general->id,
            'order' => 1,
        ]);

        $suggestions = Forum::create([
            'name' => 'Suggestions',
            'slug' => 'suggestions',
            'description' => 'Share your ideas and suggestions for the server',
            'parent_id' => $general->id,
            'order' => 2,
        ]);

        $recruitment = Forum::create([
            'name' => 'Guild Recruitment',
            'slug' => 'guild-recruitment',
            'description' => 'Find a guild or recruit members for your guild',
            'parent_id' => $general->id,
            'order' => 3,
        ]);

        $technicalSupport = Forum::create([
            'name' => 'Technical Support',
            'slug' => 'technical-support',
            'description' => 'Get help with technical issues',
            'parent_id' => $support->id,
            'order' => 1,
        ]);

        $accountSupport = Forum::create([
            'name' => 'Account Support',
            'slug' => 'account-support',
            'description' => 'Get help with account-related issues',
            'parent_id' => $support->id,
            'order' => 2,
        ]);

        // Get admin user or create one if not exists
        $admin = User::role('Admin')->first();
        if (!$admin) {
            $admin = User::first();
        }

        // Create sample threads and posts
        $this->createSampleThread(
            $news,
            $admin,
            'Welcome to NexusCMS Forums',
            'Welcome to the official NexusCMS forums! This is the place to discuss everything related to our server. Please follow the forum rules and enjoy your stay!'
        );

        $this->createSampleThread(
            $events,
            $admin,
            'Upcoming Server Launch Event',
            'We are excited to announce our server launch event! Join us on Saturday at 8 PM server time for special rewards and activities.'
        );

        $this->createSampleThread(
            $generalDiscussion,
            $admin,
            'Introduce Yourself',
            'Use this thread to introduce yourself to the community. Tell us about your character, your experience with WoW, and what you are looking forward to on our server.'
        );

        $this->createSampleThread(
            $technicalSupport,
            $admin,
            'Common Connection Issues and Solutions',
            'This thread contains information about common connection issues and their solutions. Please check this thread before creating a new support ticket.'
        );
    }

    /**
     * Create a sample thread with a first post
     */
    private function createSampleThread(Forum $forum, User $user, string $title, string $content): void
    {
        $thread = Thread::create([
            'title' => $title,
            'slug' => Str::slug($title),
            'forum_id' => $forum->id,
            'user_id' => $user->id,
        ]);

        $post = Post::create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
            'content' => $content,
            'is_first_post' => true,
        ]);

        $thread->update([
            'first_post_id' => $post->id,
            'latest_post_id' => $post->id,
        ]);

        $forum->update([
            'latest_thread_id' => $thread->id,
        ]);
    }
}