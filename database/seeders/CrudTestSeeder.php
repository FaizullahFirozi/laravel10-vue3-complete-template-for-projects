<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrudTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $crud_tests_data = [
            [
                'name' => 'فیض الله متقي',
                'email' => 'faizullah.firozi@gmail.com',
                'phone' => '0780002528',
                'description' => 'د کمپیوتر ساینس په برخه کی کارکوونکی',
                'created_at' => date('Y-m-d'),

            ],
            [
                'name' => 'مصباح الدین',
                'email' => 'mesbah.noori@gmail.com',
                'phone' => '0780002222',
                'description' => 'د کمپیوتر ساینس په برخه کی کارکوونکی',
                'created_at' => date('Y-m-d'),

            ],
            [
                'name' => 'شیرزی',
                'email' => 'shirzai.hanafi@gmail.com',
                'phone' => '0780005528',
                'description' => 'د کمپیوتر ساینس په برخه کی کارکوونکی',
                'created_at' => date('Y-m-d'),

            ],
        ];

        // Add static data
        DB::table('crud_tests')->insert($crud_tests_data);
    }
}
