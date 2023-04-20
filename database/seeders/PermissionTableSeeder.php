<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};
use App\Models\Admin;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guards = [
            'admin',
            'house-owner',
            'tenant',
            'web'
        ];

        $roles = [
            'admin',
            'house-owner',
            'tenant',
            'web'
        ];

        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'owner-list',
            'owner-create',
            'owner-edit',
            'owner-delete',
            'property-list',
            'property-create',
            'property-edit',
            'property-delete',
            'tenant-list',
            'tenant-create',
            'tenant-edit',
            'tenant-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
        ];

        foreach($guards as $guard) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission, 'guard_name' => $guard]);
            }
        }

        foreach($roles as $role){
            Role::create(['name' => $role, 'guard_name' => $role]);
        }
        $role = Role::where('name','admin')->first();
        $permissions = Permission::where('guard_name','admin')->get();
        $role->syncPermissions($permissions);
        $admin = Admin::find(1);
        $admin->assignRole([$role->id]);
    }
}
