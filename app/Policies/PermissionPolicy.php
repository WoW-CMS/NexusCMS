<?php
namespace App\Policies;

use App\Models\User;

class PermissionPolicy
{
    public function access(User $user, string $permission)
    {
        return $user->hasPermissionTo($permission);
    }
}