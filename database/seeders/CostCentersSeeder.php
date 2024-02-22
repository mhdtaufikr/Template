<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostCentersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the data you want to insert
        $data = [
            [
                'cost_ctr' => 30000,
                'coar' => 3000,
                'cocd' => 3000,
                'cctc' => 'L',
                'pic' => 'Tony S',
                'user_pic' => '',
                'remarks' => 'CORPORATE',
            ],
            [
                'cost_ctr' => 30101,
                'coar' => 3000,
                'cocd' => 3000,
                'cctc' => 'W',
                'pic' => 'Rahmat W',
                'user_pic' => '',
                'remarks' => 'HR DEV & HR MGT',
            ],
            [
                'cost_ctr' => 30201,
                'coar' => 3000,
                'cocd' => 3000,
                'cctc' => 'W',
                'pic' => 'Rahmat W',
                'user_pic' => '',
                'remarks' => 'Company Policy',
            ],
            [
                'cost_ctr' => 30301,
                'coar' => 3000,
                'cocd' => 3000,
                'cctc' => 'W',
                'pic' => 'Muh Muhtarom',
                'user_pic' => '',
                'remarks' => 'CONTROLLING',
            ],
            // Add more data as needed
        ];

        // Insert the data into the cost_centers table
        DB::table('cost_centers')->insert($data);
    }
}

