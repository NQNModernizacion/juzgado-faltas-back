<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->command?->call('permission:cache-reset');


        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.permission.view', 'description' => 'Puede ver permisos']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.permission.asign', 'description' => 'Puede otorgar o quitar permisos']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.role.view', 'description' => 'Puede ver permisos']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.role.asign', 'description' => 'Puede otorgar o quitar permisos']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.role-permission.view', 'description' => 'Puede ver permisos de roles']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.role-permission.asign', 'description' => 'Puede otorgar o quitar permisos a roles']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.activity.log', 'description' => 'Puede ver los activity logs']);
        Permission::create(['guard_name' => 'sanctum', 'name' => 'admin.users.view', 'description' => 'Puede ver el listado de usuarios']);

        $admin = Role::create(['guard_name' => 'sanctum', 'name' => 'admin']);
        $adminApp = Role::create(['guard_name' => 'sanctum', 'name' => 'admin.app']);

        $admin->givePermissionTo(Permission::findByName('admin.permission.view'));
        $admin->givePermissionTo(Permission::findByName('admin.permission.asign'));
        $admin->givePermissionTo(Permission::findByName('admin.role.view'));
        $admin->givePermissionTo(Permission::findByName('admin.role.asign'));
        $admin->givePermissionTo(Permission::findByName('admin.role-permission.view'));
        $admin->givePermissionTo(Permission::findByName('admin.role-permission.asign'));
    }
}
