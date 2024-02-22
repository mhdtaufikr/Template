<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownsTableSeeder extends Seeder
{
    public function run()
    {
        // Insert new data for "Role" category
        DB::table('dropdowns')->insert([
            [
                'category' => 'Role',
                'name_value' => 'Super Admin',
                'code_format' => 'SPA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'User',
                'code_format' => 'US',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Role',
                'name_value' => 'IT',
                'code_format' => 'IT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert new data for "UOM" category
        DB::table('dropdowns')->insert([
            [
                'category' => 'UOM',
                'name_value' => 'Lot',
                'code_format' => 'LT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'UOM',
                'name_value' => 'Unit',
                'code_format' => 'UT',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert new data for "Status" category
        DB::table('dropdowns')->insert([
            [
                'category' => 'Status',
                'name_value' => 'Active',
                'code_format' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Status',
                'name_value' => 'Deactive',
                'code_format' => '0',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Status',
                'name_value' => 'Disposal',
                'code_format' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
