<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'comment',
        'is_active',
        'commentable_id',
        'commentable_type'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    /**
     * Get the parent commentable model (news, post, etc).
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}