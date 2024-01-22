<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class AssetExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Convert the header and detail assets into a single collection for export
        $exportData = new Collection();
        
        foreach ($this->data['headerAssets'] as $headerAsset) {
            $row = [
                'Header Asset ID' => $headerAsset->id,
                'Asset No' => $headerAsset->asset_no,
                'Description' => $headerAsset->desc,
                'Quantity' => $headerAsset->qty,
                'Unit of Measure' => $headerAsset->uom,
                'Asset Type' => $headerAsset->asset_type,
                'Acquisition Date' => $headerAsset->acq_date,
                'Acquisition Cost' => $headerAsset->acq_cost,
                'Purchase Order No' => $headerAsset->po_no,
                'Serial No' => $headerAsset->serial_no,
                'Department' => $headerAsset->dept,
                'Plant' => $headerAsset->plant,
                'Location' => $headerAsset->loc,
                'Cost Center' => $headerAsset->cost_center,
                'Flag' => $headerAsset->flag,
                'Segment' => $headerAsset->segment,
                'Image' => $headerAsset->img,
                'Status' => $headerAsset->status,
                'Remarks' => $headerAsset->remarks,
                'Book Value at the End of Year' => $headerAsset->bv_endofyear,
                // Add more header columns as needed
            ];

            // Add detail asset data for the current header asset
            foreach ($this->data['detailAssets'] as $detailAssets) {
                foreach ($detailAssets as $detailAsset) {
                    if ($detailAsset->asset_header_id == $headerAsset->id) {
                        $detailRow = $row;
                        $detailRow['Detail Asset ID'] = $detailAsset->id;
                        $detailRow['Sub Asset'] = $detailAsset->sub_asset;
                        $detailRow['Detail Desc'] = $detailAsset->desc;
                        $detailRow['Detail Quantity'] = $detailAsset->qty;
                        $detailRow['Detail Unit of Measure'] = $detailAsset->uom;
                        $detailRow['Detail Asset Type'] = $detailAsset->asset_type;
                        $detailRow['Detail Date'] = $detailAsset->date;
                        $detailRow['Detail Cost'] = $detailAsset->cost;
                        $detailRow['Detail Purchase Order No'] = $detailAsset->po_no;
                        $detailRow['Detail Serial No'] = $detailAsset->serial_no;
                        $detailRow['Detail Image'] = $detailAsset->img;
                        $detailRow['Detail Status'] = $detailAsset->status;
                        $detailRow['Detail Remarks'] = $detailAsset->remarks;
                        $detailRow['Detail Book Value at End of Year'] = $detailAsset->bv_endofyear;
                        // Add more detail columns as needed

                        // Add the detail row to the collection
                        $exportData->push($detailRow);
                    }
                }
            }
        }

        return $exportData;
    }

    public function headings(): array
    {
        // Define the column headings for the Excel file
        return [
            'Header Asset ID',
            'Asset No',
            'Description',
            'Quantity',
            'Unit of Measure',
            'Asset Type',
            'Acquisition Date',
            'Acquisition Cost',
            'Purchase Order No',
            'Serial No',
            'Department',
            'Plant',
            'Location',
            'Cost Center',
            'Flag',
            'Segment',
            'Image',
            'Status',
            'Remarks',
            'Book Value at the End of Year',
            'Detail Asset ID',
            'Sub Asset',
            'Detail Desc',
            'Detail Quantity',
            'Detail Unit of Measure',
            'Detail Asset Type',
            'Detail Date',
            'Detail Cost',
            'Detail Purchase Order No',
            'Detail Serial No',
            'Detail Image',
            'Detail Status',
            'Detail Remarks',
            'Detail Book Value at End of Year',
            // Add more headings as needed
        ];
    }
    
}
