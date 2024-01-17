<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'permission.view', 'description' => 'Puede ver permisos']);
        Permission::create(['name' => 'permission.asign', 'description' => 'Puede otorgar o quitar permisos']);
        Permission::create(['name' => 'role.view', 'description' => 'Puede ver permisos']);
        Permission::create(['name' => 'role.asign', 'description' => 'Puede otorgar o quitar permisos']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'admin', 'description' => 'Administrador supremo']);
        $role1->givePermissionTo('permission.view');
        $role1->givePermissionTo('permission.asign');
        $role1->givePermissionTo('role.view');
        $role1->givePermissionTo('role.asign');
        
        Permission::create(['name'=>'documentacion', 'description'=>'Puede ver documentación']);
        $role1->givePermissionTo('documentacion'); 

        $role2 = Role::create(['name'=>'guest', 'description'=>'Invitado']);
        
    }
}