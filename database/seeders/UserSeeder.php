<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user_data = [
        //     'name' => 'فیض الله',
        //     'last_name' => 'متقي',
        //     'father_name' => 'حاجی',
        //     'dob' => '1998', 
        //     'nic' => '1398002441',
        //     'hire_date' => '2010-03-04',
        //     'email' => 'admin@gmail.com',
        //     'phone' => '0780002528',
        //     'avatar' => 'تصویر',
        //     'account_status' => 1,
        //     'password' => bcrypt('admin123'),
        // ];

        // DB::table('users')->insert($user_data);
    }
}
