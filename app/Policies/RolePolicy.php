<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.roles') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.roles');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage.roles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage.roles');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('manage.roles');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('manage.roles');
    }
}