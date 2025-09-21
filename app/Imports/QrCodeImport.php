<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class QrCodeImport implements ToArray
{
    public $rows = [];

    /**
     * @param array $array
     */
    public function array(array $array)
    {
        // Skip the header row
        $data = array_slice($array, 1);

        // Map the rows to the desired structure
        foreach ($data as $row) {
            $this->rows[] = [
                'no_asset' => $row[0],
                'sub_asset' => $row[1],
                'qty' => $row[2]
            ];
        }
    }

    public function getRows()
    {
        return $this->rows;
    }
}

