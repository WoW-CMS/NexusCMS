<?php

namespace App\Policies;

use App\Models\User;

class LogPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('view.logs') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view.logs');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view.logs');
    }
}