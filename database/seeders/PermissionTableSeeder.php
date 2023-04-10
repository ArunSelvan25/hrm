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
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }
        $role = Role::create(['name' => 'Admin', 'guard_name' => 'admin']);
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
        $admin = Admin::find(1);
        $admin->assignRole([$role->id]);
    }
}
