<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExportDetail implements FromCollection, WithHeadings, WithEvents
{
    protected $note;

    public function __construct($note)
    {
        $this->note = $note;
    }

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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Add a note to cell E2
                $event->sheet->getDelegate()->getComment('F2')->getText()->createTextRun($this->note);
            },
        ];
    }
}
