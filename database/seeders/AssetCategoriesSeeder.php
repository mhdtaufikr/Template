<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssetCategoriesSeeder extends Seeder
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
            ['class' => 10, 'desc' => 'Land'],
            ['class' => 11, 'desc' => 'Land Rights'],
            ['class' => 20, 'desc' => 'Building Permanent'],
            ['class' => 21, 'desc' => 'Building Non - Permanent'],
            ['class' => 30, 'desc' => 'Machinery'],
            ['class' => 41, 'desc' => 'Vehicle'],
            ['class' => 50, 'desc' => 'Tools'],
            ['class' => 51, 'desc' => 'Jig'],
            ['class' => 52, 'desc' => 'Dies'],
            ['class' => 53, 'desc' => 'Standard Model'],
            ['class' => 60, 'desc' => 'Furniture & Fixture - Group 2 - 25%'],
            ['class' => 70, 'desc' => 'Furniture & Fixture - Group 1 - 50%'],
            ['class' => 80, 'desc' => 'Construction In Progress'],
            ['class' => 90, 'desc' => 'Low Value Asset'],
            ['class' => 91, 'desc' => 'Intangible Asset - Other'],
        ];

        // Insert the data into the asset_categories table
        DB::table('asset_categories')->insert($data);
    }
}

