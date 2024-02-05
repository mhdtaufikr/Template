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
        'Main Asset', 'Asset No', 'Sub Asset', 'Asset Description', 'Quantity', 'Bun',
         'First Acq.', 'Acquis. Val.', 'PO No.', 'Serial No', 'Status',
        'Remarks', 'Book Value at End of Year', 'Department', 'Plant', 'Location',
        'Cost Center', 'Part No.'
    ];
}


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Add a note to cell E2
               $event->sheet->getDelegate()->getComment('H1')->getText()->createTextRun($this->note);
               $event->sheet->getDelegate()->getComment('L1')->getText()->createTextRun("2 = disposal, 0 = deactive, 1 = active");

            },
        ];
    }
}
