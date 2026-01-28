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
        Permission::create(['name' => 'access.admin.panel']);
        Permission::create(['name' => 'manage.users']);
        Permission::create(['name' => 'manage.news']);
        Permission::create(['name' => 'manage.settings']);
        Permission::create(['name' => 'manage.admin.users']);
        Permission::create(['name' => 'manage.roles']);
        Permission::create(['name' => 'manage.permissions']);
        Permission::create(['name' => 'manage.pages']);
        Permission::create(['name' => 'manage.announcements']);
        Permission::create(['name' => 'view.logs']);
        Permission::create(['name' => 'manage.backups']);
        Permission::create(['name' => 'manage.realms']);
        Permission::create(['name' => 'manage.bans']);
        Permission::create(['name' => 'manage.reports']);
        Permission::create(['name' => 'manage.store.products']);
        Permission::create(['name' => 'manage.store.categories']);
        Permission::create(['name' => 'view.store.transactions']);
        
        // Permisos para GameMasters
        Permission::create(['name' => 'access.gm.panel']);
        Permission::create(['name' => 'manage.characters']);
        Permission::create(['name' => 'manage.items']);
        Permission::create(['name' => 'moderate.chat']);
        
        // Permisos para usuarios normales
        Permission::create(['name' => 'access.ucp']);
        Permission::create(['name' => 'manage.own.account']);
        Permission::create(['name' => 'view.characters']);
        Permission::create(['name' => 'view.news']);
        Permission::create(['name' => 'comment.news']);
        Permission::create(['name' => 'forums.create.thread']);
        Permission::create(['name' => 'forums.reply.post']);
        Permission::create(['name' => 'forums.edit.own.post']);
        Permission::create(['name' => 'forums.delete.own.post']);
        Permission::create(['name' => 'store.browse']);
        Permission::create(['name' => 'store.purchase']);
        Permission::create(['name' => 'donate.browse']);
        Permission::create(['name' => 'donate.purchase']);

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $gmRole = Role::create(['name' => 'GameMaster']);
        $gmRole->givePermissionTo([
            'access.gm.panel',
            'manage.characters',
            'manage.items',
            'moderate.chat',
            'access.ucp',
            'manage.own.account',
            'view.characters'
        ]);

        $userRole = Role::create(['name' => 'User']);
        $userRole->givePermissionTo([
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
