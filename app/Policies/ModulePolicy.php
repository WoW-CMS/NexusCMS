<?php

namespace App\Policies;

use App\Models\User;

class ModulePolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.modules') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }

    public function toggle(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }

    public function migrate(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }

    public function uninstall(User $user): bool
    {
        return $user->hasPermissionTo('manage.modules');
    }
}