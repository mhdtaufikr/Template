<?php

namespace App\Imports;

use App\Models\AssetDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\AssetCategory;

class AssetDetailImport implements ToCollection, WithHeadingRow
{
    private $assetHeaderId;

    public function __construct($assetHeaderId)
    {
        $this->assetHeaderId = $assetHeaderId;
    }

    public function collection(Collection $rows)
    {
        // Create an empty array to store individual models
        $assetDetails = [];

        // Loop through each row
        foreach ($rows->toArray() as $row) {
            // Trim keys and make them case-insensitive
            $row = array_change_key_case($row, CASE_LOWER);

            // Convert Excel date to a human-readable date
            $excelDate = $row['date'];
            $date = Carbon::createFromFormat('m/d/Y', '01/01/1900')->addDays($excelDate - 2)->toDateString();
            $assetType = substr($row['asset_no'], 0, 2);
            $assetCategory = AssetCategory::where('class', $assetType)->first();
            // Add the data directly to the $assetDetails array
            $assetDetails[] = [
                'asset_header_id' => $this->assetHeaderId,
                'asset_no' => $row['asset_no'],
                'sub_asset' => $row['sub_asset'],
                'desc' => $row['description'],
                'qty' => $row['qty'],
                'uom' => $row['uom'],
                'asset_type' => $assetCategory,
                'date' => $date,
                'cost' => $row['accuisition_cost'], // Adjust column names if needed
                'po_no' => $row['po_no'],
                'serial_no' => $row['serial_no'],
                'img' => $row['img'],
                'status' => $row['status'],
                'remarks' => $row['remarks'],
                'bv_endofyear' => $row['bv_end_of_year'],
                // Add other columns as needed
            ];
        }

        // Save the array of models to the database
        AssetDetail::insert($assetDetails);
    }
}
