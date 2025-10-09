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
        
        // Permisos para GameMasters
        Permission::create(['name' => 'access.gm.panel']);
        Permission::create(['name' => 'manage.characters']);
        Permission::create(['name' => 'manage.items']);
        Permission::create(['name' => 'moderate.chat']);
        
        // Permisos para usuarios normales
        Permission::create(['name' => 'access.ucp']);
        Permission::create(['name' => 'manage.own.account']);
        Permission::create(['name' => 'view.characters']);

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
            'view.characters'
        ]);
    }
}