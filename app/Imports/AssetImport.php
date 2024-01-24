<?php

// app/Imports/AssetImport.php

namespace App\Imports;

use App\Models\AssetHeader;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\AssetCategory;

class AssetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Create an empty array to store individual models
        $assetHeaders = [];

        // Loop through each row
        foreach ($rows->toArray() as $row) {
            // Trim keys and make them case-insensitive
            $row = array_change_key_case($row, CASE_LOWER);

            // Convert Excel date to a human-readable date
            $excelDate = $row['first_acq'];
            $acquisitionDate = Carbon::createFromFormat('m/d/Y', '01/01/1900')->addDays($excelDate - 2)->toDateString();

            // Add the data directly to the $assetHeaders array
            $assetHeaders[] = [
                'asset_no' => $row['asset_no'],
                'desc' => $row['asset_description'],
                'qty' => $row['quantity'],
                'uom' => $row['bun'],
                'asset_type' => $row['asset_type'],
                'acq_date' => $acquisitionDate,
                'acq_cost' => $row['acquis_val'],
                'po_no' => $row['po_no'],
                'serial_no' => $row['serial_no'],
                'dept' => $row['department'],
                'plant' => $row['plant'],
                'loc' => $row['location'],
                'cost_center' => $row['cost_center'],
                'segment' => $row['part_no'],
                'status' => $row['status'],
                'remarks' => $row['remarks'],
                'bv_endofyear' => $row['book_value_at_end_of_year'],
            ];
        }

        // Save the array of models to the database
        AssetHeader::insert($assetHeaders);
    }
}

