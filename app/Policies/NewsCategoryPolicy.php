<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NewsCategory;

class NewsCategoryPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.news') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function update(User $user, NewsCategory $newsCategory): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function delete(User $user, NewsCategory $newsCategory): bool
    {
        return $user->hasPermissionTo('manage.news');
    }
}