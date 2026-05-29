<?php

namespace App\Policies;

use App\Models\User;

class AdminUserPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.admin.users') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.admin.users');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage.admin.users');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage.admin.users');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('manage.admin.users');
    }

    public function delete(User $user): bool
    {
        // Admin role users cannot be deleted
        if ($user->hasRole('Admin')) {
            return false;
        }

        return $user->hasPermissionTo('manage.admin.users');
    }
}