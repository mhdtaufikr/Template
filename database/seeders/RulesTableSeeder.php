<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RulesTableSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data from the table
        DB::table('rules')->truncate();

        // Insert new data
        DB::table('rules')->insert([
            [
                'rule_name' => 'UrlQr',
                'rule_value' => 'http://172.17.215.44/mkm/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'rule_name' => 'UrlQrDetail',
                'rule_value' => 'http://172.17.215.44/mkm/dtl/',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

