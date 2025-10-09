# Forums Module for NexusCMS

This module adds a complete forum system to NexusCMS, allowing users to create and participate in discussions.

## Features

- Hierarchical forum structure with categories and subforums
- Thread creation and management
- Post/reply system
- User integration with existing authentication system
- Sticky and locked threads
- View counts and activity tracking

## Installation

1. Run the migrations to create the necessary database tables:

```bash
php artisan migrate
```

2. Seed the database with initial forum categories and sample content:

```bash
php artisan db:seed --class=ForumsSeeder
```

## Usage

### User Interface

- **Forums Index**: Browse all forum categories and subforums at `/forums`
- **Forum View**: View threads in a specific forum at `/forums/{forum-slug}`
- **Thread View**: Read and reply to a thread at `/forums/{forum-slug}/{thread-slug}`
- **Create Thread**: Start a new discussion at `/forums/{forum-slug}/create`

### Permissions

The forums module uses the existing role-based permissions system:

- **Guests** can view forums and threads
- **Authenticated Users** can create threads and post replies
- **Moderators** can sticky, lock, and manage threads (to be implemented)
- **Administrators** have full control over all forum content (to be implemented)

## Models

### Forum

Represents a forum category or subforum.

- `name`: The display name of the forum
- `slug`: URL-friendly version of the name
- `description`: Brief description of the forum's purpose
- `parent_id`: ID of the parent forum (null for top-level categories)
- `order`: Position for sorting forums
- `is_category`: Whether this forum is a category
- `latest_thread_id`: Reference to the most recently active thread

### Thread

Represents a discussion topic within a forum.

- `title`: The title of the thread
- `slug`: URL-friendly version of the title
- `forum_id`: The forum this thread belongs to
- `user_id`: The user who created the thread
- `is_sticky`: Whether the thread is pinned to the top
- `is_locked`: Whether the thread is closed to new replies
- `view_count`: Number of times the thread has been viewed
- `first_post_id`: Reference to the initial post
- `latest_post_id`: Reference to the most recent post

### Post

Represents an individual message within a thread.

- `thread_id`: The thread this post belongs to
- `user_id`: The user who created the post
- `content`: The post content
- `is_first_post`: Whether this is the initial post of the thread

## Future Enhancements

- Rich text editor for posts
- File attachments
- Post reactions/likes
- Thread subscriptions and notifications
- Advanced moderation tools
- User activity statistics