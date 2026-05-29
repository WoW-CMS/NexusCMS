<?php

namespace App\Policies;

use App\Models\User;

class RealmPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.realms') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.realms');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage.realms');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage.realms');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('manage.realms');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('manage.realms');
    }

    public function soapTest(User $user): bool
    {
        return $user->hasPermissionTo('manage.realms');
    }
}