<?php

namespace App\Providers;

use App\Models\User;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UpdateLog;
use App\Models\Backup;
use App\Models\Module;
use App\Models\Log;
use App\Models\Realm;
use App\Models\AnalyticsSession;
use App\Policies\PermissionPolicy;
use App\Policies\NewsPolicy;
use App\Policies\NewsCategoryPolicy;
use App\Policies\RolePolicy;
use App\Policies\AdminUserPolicy;
use App\Policies\RealmPolicy;
use App\Policies\BackupPolicy;
use App\Policies\UpdatePolicy;
use App\Policies\ModulePolicy;
use App\Policies\LogPolicy;
use App\Policies\AnalyticsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class            => AdminUserPolicy::class,
        News::class            => NewsPolicy::class,
        NewsCategory::class    => NewsCategoryPolicy::class,
        Role::class            => RolePolicy::class,
        Permission::class      => PermissionPolicy::class,
        UpdateLog::class       => UpdatePolicy::class,
        Backup::class          => BackupPolicy::class,
        Module::class          => ModulePolicy::class,
        Log::class             => LogPolicy::class,
        Realm::class           => RealmPolicy::class,
        AnalyticsSession::class => AnalyticsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}