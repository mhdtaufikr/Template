<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetsExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No. Asset',
            'Qty'
        ];
    }
}

