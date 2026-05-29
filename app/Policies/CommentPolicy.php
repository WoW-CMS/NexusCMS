<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy
{
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->hasRole('Admin');
    }
}