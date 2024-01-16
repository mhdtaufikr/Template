<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ExcelExportDetail implements FromCollection, WithHeadings
{
    private $id;


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Return a collection with a single row containing the values
        return collect([
            [
                'asset_no' => null,
                'sub_asset' => null,
                'desc' => null,
                'qty' => null,
                'uom' => null,
                'asset_type' => null,
                'date' => null,
                'cost' => null,
                'po_no' => null,
                'serial_no' => null,
                'img' => null,
                'status' => null,
                'remarks' => null,
                'bv_endofyear' => null,
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Asset No.',
            'Sub Asset',
            'Description',
            'Qty',
            'UOM',
            'Asset Category',
            'Date',
            'Accuisition Cost',
            'PO No.',
            'Serial No.',
            'img',
            'Status',
            'Remarks',
            'BV End of Year',
        ];
    }
}
