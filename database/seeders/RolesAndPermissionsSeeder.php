<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Resetear roles y permisos en caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        // Permisos para administración general
        Permission::firstOrCreate(['name' => 'access.admin.panel']);
        Permission::firstOrCreate(['name' => 'manage.users']);
        Permission::firstOrCreate(['name' => 'manage.news']);
        Permission::firstOrCreate(['name' => 'manage.settings']);
        Permission::firstOrCreate(['name' => 'manage.admin.users']);
        Permission::firstOrCreate(['name' => 'manage.roles']);
        Permission::firstOrCreate(['name' => 'manage.permissions']);
        Permission::firstOrCreate(['name' => 'manage.pages']);
        Permission::firstOrCreate(['name' => 'manage.announcements']);
        Permission::firstOrCreate(['name' => 'view.logs']);
        Permission::firstOrCreate(['name' => 'manage.backups']);
        Permission::firstOrCreate(['name' => 'manage.realms']);
        Permission::firstOrCreate(['name' => 'manage.bans']);
        Permission::firstOrCreate(['name' => 'manage.reports']);
        Permission::firstOrCreate(['name' => 'manage.store.products']);
        Permission::firstOrCreate(['name' => 'manage.store.categories']);
        Permission::firstOrCreate(['name' => 'view.store.transactions']);
        Permission::firstOrCreate(['name' => 'view.analytics']);
        // Permisos para GameMasters
        Permission::firstOrCreate(['name' => 'access.gm.panel']);
        Permission::firstOrCreate(['name' => 'manage.characters']);
        Permission::firstOrCreate(['name' => 'manage.items']);
        Permission::firstOrCreate(['name' => 'moderate.chat']);
        
        // Permisos para usuarios normales
        Permission::firstOrCreate(['name' => 'access.ucp']);
        Permission::firstOrCreate(['name' => 'manage.own.account']);
        Permission::firstOrCreate(['name' => 'view.characters']);
        Permission::firstOrCreate(['name' => 'view.news']);
        Permission::firstOrCreate(['name' => 'comment.news']);
        Permission::firstOrCreate(['name' => 'forums.create.thread']);
        Permission::firstOrCreate(['name' => 'forums.reply.post']);
        Permission::firstOrCreate(['name' => 'forums.edit.own.post']);
        Permission::firstOrCreate(['name' => 'forums.delete.own.post']);
        Permission::firstOrCreate(['name' => 'store.browse']);
        Permission::firstOrCreate(['name' => 'store.purchase']);
        Permission::firstOrCreate(['name' => 'donate.browse']);
        Permission::firstOrCreate(['name' => 'donate.purchase']);

        // Crear roles y asignar permisos
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        $gmRole = Role::firstOrCreate(['name' => 'GameMaster']);
        $gmRole->syncPermissions([
            'access.gm.panel',
            'manage.characters',
            'manage.items',
            'moderate.chat',
            'access.ucp',
            'manage.own.account',
            'view.characters'
        ]);

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->syncPermissions([
            'access.ucp',
            'manage.own.account',
            'view.characters',
            'view.news',
            'comment.news',
            'forums.create.thread',
            'forums.reply.post',
            'forums.edit.own.post',
            'forums.delete.own.post',
            'store.browse',
            'store.purchase',
            'donate.browse',
            'donate.purchase',
        ]);
    }
}
