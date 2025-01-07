<?php

namespace App\Imports;

use App\Models\AssetHeader;
use App\Models\AssetDetail;
use App\Models\AssetCategory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

class AssetImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {

                // Konversi tanggal 'first_acq'
                $acquisitionDate = null;
                if (isset($row['first_acq'])) {
                    if (is_numeric($row['first_acq'])) {
                        // Format angka Excel (e.g., 27395)
                        $acquisitionDate = Carbon::instance(Date::excelToDateTimeObject($row['first_acq']))->toDateString();
                    } elseif (is_string($row['first_acq'])) {
                        // Format string tanggal
                        try {
                            $acquisitionDate = Carbon::createFromFormat('m/d/Y', $row['first_acq'])->toDateString();
                        } catch (\Exception $e) {
                            $acquisitionDate = null;
                        }
                    }
                }

                // Validasi field 'main_asset'
                if (empty($row['main_asset'])) {
                    throw new \Exception("Field 'main_asset' tidak boleh null di row " . ($index + 1));
                }

                // Ambil 2 karakter pertama dari 'main_asset'
                $assetClass = substr($row['main_asset'], 0, 2);

                // Query kategori aset
                $assetCategory = AssetCategory::where('class', $assetClass)->first();

                // Ambil jumlah (quantity)
                $quantity = !empty($row['quantity']) ? $row['quantity'] : 0;

                // Cek apakah sub_asset ada
                if (!empty($row['sub_asset'])) {
                    $mainAsset = AssetHeader::where('asset_no', $row['main_asset'])->first();
                    if (!$mainAsset) {
                        throw new \Exception("Asset dengan 'main_asset' {$row['main_asset']} tidak ditemukan.");
                    }

                    AssetDetail::create([
                        'asset_header_id' => $mainAsset->id,
                        'asset_no' => $row['asset_no'],
                        'sub_asset' => $row['sub_asset'],
                        'desc' => $row['asset_description'],
                        'qty' => $quantity,
                        'uom' => $row['bun'],
                        'asset_type' => $assetCategory->desc ?? null,
                        'date' => $acquisitionDate,
                        'cost' => $row['acquis_val'],
                        'po_no' => $row['po_no'],
                        'serial_no' => $row['serial_no'],
                        'status' => $row['status'],
                        'remarks' => $row['remarks'],
                        'bv_endofyear' => $row['book_value_at_end_of_year'],
                    ]);
                } elseif (!empty($row['flag'])) {
                    $mainAsset = AssetHeader::where('asset_no', $row['main_asset'])->first();
                    if (!$mainAsset) {
                        throw new \Exception("Asset dengan 'main_asset' {$row['main_asset']} tidak ditemukan.");
                    }

                    AssetDetail::create([
                        'asset_header_id' => $mainAsset->id,
                        'asset_no' => $row['flag'],
                        'sub_asset' => null,
                        'desc' => $row['asset_description'],
                        'qty' => $quantity,
                        'uom' => $row['bun'],
                        'asset_type' => $assetCategory->desc ?? null,
                        'date' => $acquisitionDate,
                        'cost' => $row['acquis_val'],
                        'po_no' => $row['po_no'],
                        'serial_no' => $row['serial_no'],
                        'status' => $row['status'],
                        'remarks' => $row['remarks'],
                        'bv_endofyear' => $row['book_value_at_end_of_year'],
                    ]);
                } else {
                    AssetHeader::create([
                        'asset_no' => $row['asset_no'],
                        'desc' => $row['asset_description'],
                        'qty' => $quantity,
                        'uom' => $row['bun'],
                        'asset_type' => $assetCategory->desc ?? null,
                        'acq_date' => $acquisitionDate,
                        'acq_cost' => $row['acquis_val'],
                        'po_no' => $row['po_no'],
                        'serial_no' => $row['serial_no'],
                        'dept' => $row['department'],
                        'plant' => $row['plant'],
                        'loc' => $row['location'],
                        'cost_center' => $row['cost_center'],
                        'segment' => $row['part_no'],
                        'status' => $row['status'],
                        'remarks' => $row['remarks'],
                        'bv_endofyear' => $row['book_value_at_end_of_year'],
                    ]);
                }
            } catch (\Exception $e) {
                // Debug error dan iterasi
                dd([
                    'error' => $e->getMessage(),
                    'row_data' => $row,
                    'iteration' => $index + 1,
                ]);
            }
        }
    }
}
