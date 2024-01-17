<?php

// AssetExport.php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExcelExport implements FromCollection, WithHeadings, WithEvents
{
    protected $note;

    public function __construct($note)
    {
        $this->note = $note;
    }

    public function collection()
    {
        // Return an empty collection, as we're only defining headers
        return collect();
    }

    public function headings(): array
    {
        return [
            'Asset No', 'Description', 'Quantity', 'UOM', 'Acquisition Date', 'Acquisition Cost',
            'PO No', 'Serial No', 'Department', 'Plant', 'Location', 'Cost Center', 'Image', 'Status', 'BV End of Year'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Add a note to cell E2
                $richText = $event->sheet->getDelegate()->getComment('E2')->getText();
                $richText->createTextRun($this->note);
            },
        ];
    }
}
