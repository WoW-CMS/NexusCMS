<?php

namespace App\Policies;

use App\Models\User;
use App\Models\News;

class NewsPolicy
{
    /**
     * Only users with manage.news permission can perform any action.
     */
    public function before(User $user): ?bool
    {
        return $user->hasPermissionTo('manage.news') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function view(User $user, News $news): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function update(User $user, News $news): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function delete(User $user, News $news): bool
    {
        return $user->hasPermissionTo('manage.news');
    }

    public function restore(User $user, News $news): bool
    {
        return $user->hasPermissionTo('manage.news');
    }
}