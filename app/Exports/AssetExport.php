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
        return $this->data;
    }

    public function headings(): array
    {
        // Define the column headings for the Excel file
        return [
            'Main Asset',
            'Asset No',
            'Sub Asset',
            'Desc',
            'Quantity',
            'Unit of Measure',
            'Asset Type',
            'Date',
            'Cost',
            'Purchase Order No',
            'Serial No',
            'Image',
            'Status',
            'Remarks',
            'Book Value at End of Year',
            'Department',
            'Plant',
            'Location',
            'Cost Center',
            'Part.No',
            'Image',
            'Status',
            'Remarks',
            'Book Value at the End of Year',
            'created_at',
            'updated_at',
            // Add more common columns as needed
        ];
    }
}
