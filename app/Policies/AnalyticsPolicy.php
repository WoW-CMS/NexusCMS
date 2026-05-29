<?php

namespace App\Policies;

use App\Models\User;

class AnalyticsPolicy
{
    public function before(User $user): ?bool
    {
        // Analytics uses a different permission: view.analytics
        return $user->hasPermissionTo('view.analytics') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view.analytics');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view.analytics');
    }
}