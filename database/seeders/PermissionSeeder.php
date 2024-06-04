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




        // ***************Super Admin
        // gets all permissions via Gate::before rule; see AuthServiceProvider
        // Super Admin not need to permissions or Roles*****
        Role::create(['name' => 'Super Admin']);


        // ***************Admin
        $role = Role::create(['name' => 'Admin']);

        $adminPermissions = [
            'test-read',
            'test-create',
            'admin',
        ];

        foreach ($adminPermissions as $permission) {
            $role->givePermissionTo($permission);
        }




        // *****************User
        $role = Role::create(['name' => 'User']);

        $userPermissions = [
            'test-read',
            'user',
        ];

        foreach ($userPermissions as $permission) {
            $role->givePermissionTo($permission);
        }



        // ************ SUPER ADMIN
        $super_admin =  User::create([
            'name' => 'super admin فیض الله',
            'last_name' => 'متقي',
            'father_name' => 'حاجی',
            'dob' => '1998',
            'nic' => '1398002441',
            'hire_date' => '2010-03-04',
            'email' => 'super_admin@gmail.com',
            'phone' => '0780002528',
            'account_status' => 1,
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
        ]);


        $super_admin->assignRole('Super Admin');



        // ************ ADMIN
        $admin =  User::create([
            'name' => 'admin',
            'last_name' => 'فیروزی',
            'father_name' => 'حاجی',
            'dob' => '1998',
            'nic' => '139800444',
            'hire_date' => '2010-03-04',
            'email' => 'admin@gmail.com',
            'phone' => '0780002555',
            'account_status' => 1,
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('Admin');



        // ************** USER
        $user = User::create([
            'name' => 'فیض الله متقي user',
            'last_name' => 'اتل',
            'father_name' => 'حاجی',
            'dob' => '1998',
            'nic' => '13980066',
            'hire_date' => '2010-03-04',
            'email' => 'user@gmail.com',
            'phone' => '0780002566',
            'account_status' => 1,
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('User');
    }
}
