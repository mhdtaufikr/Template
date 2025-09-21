<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportSearch implements FromCollection, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection()
    {
        // Return an empty collection as there is no actual data to export
        return new Collection([]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define the headings for your Excel export
        return ['Asset No.'];
    }
}
