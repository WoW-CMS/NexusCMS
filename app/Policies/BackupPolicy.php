<?php

namespace App\Policies;

use App\Models\User;

class BackupPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.backups') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.backups');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage.backups');
    }

    public function download(User $user): bool
    {
        return $user->hasPermissionTo('manage.backups');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('manage.backups');
    }
}