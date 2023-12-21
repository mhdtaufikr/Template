<?php

// AssetExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Return an empty collection, as we're only defining headers
        return collect();
    }

    public function headings(): array
    {
        return  [
            'Asset No', 'Description', 'Quantity', 'UOM', 'Asset Type', 'Acquisition Date', 'Acquisition Cost',
            'PO No', 'Serial No', 'Department', 'Plant', 'Location', 'Cost Center', 'Image', 'Status', 'BV End of Year'
        ];
    }
}
