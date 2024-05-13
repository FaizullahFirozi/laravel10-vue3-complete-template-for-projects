<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'test-read',
            'test-create',
            'test-edit',
            'test-delete',

            'user-read',
            'user-create',
            'user-edit',
            'user-delete',

            'super-admin',
            'admin',
            'user',

        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }



        
        // ***************
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // Super Admin not need to permissions or Roles*****
        Role::create(['name' => 'Super Admin']);

        
        // ***************
        $role = Role::create(['name' => 'Admin']);

        $adminPermissions = [
            'test-read',
            'test-create',
            'admin',
        ];

        foreach ($adminPermissions as $permission) {
            $role->givePermissionTo($permission);
        }



        
        // *****************
        $role = Role::create(['name' => 'User']);

        $userPermissions = [
            'test-read',
            'user',
        ];

        foreach ($userPermissions as $permission) {
            $role->givePermissionTo($permission);
        }


        
        // ************** USER
        $user = User::create([
            'name' => 'فیض الله متقي user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        $user->assignRole('User');



        // ************ SUPER ADMIN
        $super_admin =  User::create([
            'name' => 'super admin',
            'email' => 'super_admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        $super_admin->assignRole('Super Admin');


        
        // ************ ADMIN
        $admin =  User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
        ]);

        $admin->assignRole('Admin');



    }
}
