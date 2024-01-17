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
            $excelDate = $row['acquisition_date'];
            $acquisitionDate = Carbon::createFromFormat('m/d/Y', '01/01/1900')->addDays($excelDate - 2)->toDateString();
            $assetType = substr($row['asset_no'], 0, 2);
            $assetCategory = AssetCategory::where('class', $assetType)->first();
            // Add the data directly to the $assetHeaders array
            $assetHeaders[] = [
                'asset_no' => $row['asset_no'],
                'desc' => $row['description'],
                'qty' => $row['quantity'],
                'uom' => $row['uom'],
                'asset_type' => $assetCategory->desc,
                'acq_date' => $acquisitionDate,
                'acq_cost' => $row['acquisition_cost'],
                'po_no' => $row['po_no'],
                'serial_no' => $row['serial_no'],
                'dept' => $row['department'],
                'plant' => $row['plant'],
                'loc' => $row['location'],
                'cost_center' => $row['cost_center'],
                'img' => $row['image'],
                'status' => $row['status'],
                'bv_endofyear' => $row['bv_end_of_year'],
            ];
        }
        
        // Save the array of models to the database
        AssetHeader::insert($assetHeaders);
        
    }
}
