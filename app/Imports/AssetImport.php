<?php

// app/Imports/AssetImport.php

namespace App\Imports;

use App\Models\AssetHeader;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use App\Models\AssetDetail;
use App\Models\AssetCategory;
// ...

class AssetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Convert the 'first_acq' date string to a Carbon instance
            $excelDate = $row['first_acq'];
            $acquisitionDate = Carbon::createFromFormat('m/d/Y', '01/01/1900')->addDays($excelDate - 2)->toDateString();
            // Get the first two numbers of 'asset_no'
            $assetClass = substr($row['asset_no'], 0, 2);

            // Query 'asset_categories' to get the 'class'
            $assetCategory = AssetCategory::where('class', $assetClass)->first();

            // Use the 'class' value as 'asset_type'
            $assetType = $assetCategory ? $assetCategory->class : null;
            if (empty($row['sub_asset'])) {
                // Insert into asset_headers
                    $header = AssetHeader::create([
                        'asset_no' => $row['asset_no'],
                        'desc' => $row['asset_description'],
                        'qty' => $row['quantity'],
                        'uom' => $row['bun'],
                        'asset_type' => $assetCategory->desc,
                        'acq_date' => $acquisitionDate,
                        'acq_cost' => $row['acquis_val'],
                        'po_no' => $row['po_no'],
                        'serial_no' => $row['serial_no'],
                        'dept' => $row['department'],
                        'plant' => $row['plant'],
                        'loc' => $row['location'],
                        'cost_center' => $row['cost_center'],
                        'segment' =>$row['part_no'],
                        'status' => $row['status'],
                        'remarks' => $row['remarks'],
                        'bv_endofyear' => $row['book_value_at_end_of_year'],
                    ]);
            } else {
                
                $mainAsset = AssetHeader::where('asset_no', $row['main_asset'])->first();
                
                AssetDetail::create([
                    'asset_header_id' => $mainAsset->id,
                    'asset_no' => $row['asset_no'],
                    'sub_asset' => $row['sub_asset'],
                    'desc' => $row['asset_description'],
                    'qty' => $row['quantity'],
                    'uom' => $row['bun'],
                    'asset_type' => $assetCategory->desc,
                    'date' => $acquisitionDate,
                    'cost' => $row['acquis_val'],
                    'po_no' => $row['po_no'],
                    'serial_no' => $row['serial_no'],
                    'status' => $row['status'],
                    'remarks' => $row['remarks'],
                    'bv_endofyear' => $row['book_value_at_end_of_year'],
                ]);
            }
        }
    }
}