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
            'Header Asset ID',
            'Asset No',
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
            'created_at',
            'updated_at',
            // Add more common columns as needed
        ];
    }
}
