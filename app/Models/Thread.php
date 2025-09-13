<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'forum_id',
        'user_id',
        'is_sticky',
        'is_locked',
        'view_count',
    ];

    /**
     * Get all posts for this thread
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the forum this thread belongs to
     */
    public function forum(): BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }

    /**
     * Get the user who created this thread
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the first post of this thread
     */
    public function firstPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'first_post_id');
    }

    /**
     * Get the latest post of this thread
     */
    public function latestPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'latest_post_id');
    }
}