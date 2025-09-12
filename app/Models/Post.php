<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'user_id',
        'content',
        'is_first_post',
    ];

    /**
     * Get the thread this post belongs to
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Get the user who created this post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}