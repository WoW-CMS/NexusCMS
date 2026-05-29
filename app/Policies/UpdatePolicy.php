<?php

namespace App\Policies;

use App\Models\User;

class UpdatePolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.settings') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.settings');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage.settings');
    }

    public function apply(User $user): bool
    {
        return $user->hasPermissionTo('manage.settings');
    }
}