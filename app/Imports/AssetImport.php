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

                $mainAssetNo = trim((string) ($row['main_asset'] ?? ''));
                $assetNo = trim((string) ($row['asset_no'] ?? ''));
                $subAsset = trim((string) ($row['sub_asset'] ?? ''));
                $flag = trim((string) ($row['flag'] ?? ''));

                // Validasi field 'main_asset'
                if ($mainAssetNo === '') {
                    throw new \Exception("Field 'main_asset' tidak boleh null di row " . ($index + 1));
                }

                if ($assetNo === '' && $flag === '') {
                    throw new \Exception("Field 'asset_no' atau 'flag' harus diisi di row " . ($index + 1));
                }

                // Ambil 2 karakter pertama dari 'main_asset'
                $assetClass = substr($mainAssetNo, 0, 2);

                // Query kategori aset
                $assetCategory = AssetCategory::where('class', $assetClass)->first();

                // Ambil jumlah (quantity)
                $quantity = !empty($row['quantity']) ? $row['quantity'] : 0;
                $acquisitionCost = $this->cleanNumber($row['acquis_val'] ?? 0);
                $bookValue = $this->cleanNumber($row['book_value_at_end_of_year'] ?? 0);

                // Kalau Main Asset sama dengan Asset No, row ini adalah asset utama.
                // Beberapa template lama mengisi Sub Asset dengan 0/1; itu bukan detail.
                if ($assetNo !== '' && $mainAssetNo === $assetNo) {
                    AssetHeader::updateOrCreate(
                        ['asset_no' => $assetNo],
                        [
                            'desc' => $row['asset_description'],
                            'qty' => $quantity,
                            'uom' => $row['bun'],
                            'asset_type' => $assetCategory->desc ?? null,
                            'acq_date' => $acquisitionDate,
                            'acq_cost' => $acquisitionCost,
                            'po_no' => $row['po_no'],
                            'serial_no' => $row['serial_no'],
                            'dept' => $row['department'],
                            'plant' => $row['plant'],
                            'loc' => $row['location'],
                            'cost_center' => $row['cost_center'],
                            'segment' => $row['part_no'],
                            'status' => $row['status'],
                            'remarks' => $row['remarks'],
                            'bv_endofyear' => $bookValue,
                        ]
                    );
                } elseif ($subAsset !== '') {
                    $mainAsset = AssetHeader::where('asset_no', $mainAssetNo)->first();
                    if (!$mainAsset) {
                        throw new \Exception("Asset dengan 'main_asset' {$mainAssetNo} tidak ditemukan.");
                    }

                    AssetDetail::updateOrCreate(
                        [
                            'asset_header_id' => $mainAsset->id,
                            'asset_no' => $assetNo,
                            'sub_asset' => $subAsset,
                        ],
                        [
                            'desc' => $row['asset_description'],
                            'qty' => $quantity,
                            'uom' => $row['bun'],
                            'asset_type' => $assetCategory->desc ?? null,
                            'date' => $acquisitionDate,
                            'cost' => $acquisitionCost,
                            'po_no' => $row['po_no'],
                            'serial_no' => $row['serial_no'],
                            'status' => $row['status'],
                            'remarks' => $row['remarks'],
                            'bv_endofyear' => $bookValue,
                        ]
                    );
                } elseif ($flag !== '') {
                    $mainAsset = AssetHeader::where('asset_no', $mainAssetNo)->first();
                    if (!$mainAsset) {
                        throw new \Exception("Asset dengan 'main_asset' {$mainAssetNo} tidak ditemukan.");
                    }

                    AssetDetail::updateOrCreate(
                        [
                            'asset_header_id' => $mainAsset->id,
                            'asset_no' => $flag,
                            'sub_asset' => null,
                        ],
                        [
                            'desc' => $row['asset_description'],
                            'qty' => $quantity,
                            'uom' => $row['bun'],
                            'asset_type' => $assetCategory->desc ?? null,
                            'date' => $acquisitionDate,
                            'cost' => $acquisitionCost,
                            'po_no' => $row['po_no'],
                            'serial_no' => $row['serial_no'],
                            'status' => $row['status'],
                            'remarks' => $row['remarks'],
                            'bv_endofyear' => $bookValue,
                        ]
                    );
                } else {
                    AssetHeader::updateOrCreate(
                        ['asset_no' => $assetNo],
                        [
                            'desc' => $row['asset_description'],
                            'qty' => $quantity,
                            'uom' => $row['bun'],
                            'asset_type' => $assetCategory->desc ?? null,
                            'acq_date' => $acquisitionDate,
                            'acq_cost' => $acquisitionCost,
                            'po_no' => $row['po_no'],
                            'serial_no' => $row['serial_no'],
                            'dept' => $row['department'],
                            'plant' => $row['plant'],
                            'loc' => $row['location'],
                            'cost_center' => $row['cost_center'],
                            'segment' => $row['part_no'],
                            'status' => $row['status'],
                            'remarks' => $row['remarks'],
                            'bv_endofyear' => $bookValue,
                        ]
                    );
                }
            } catch (\Exception $e) {
                throw new \Exception("Row " . ($index + 1) . ": " . $e->getMessage(), 0, $e);
            }
        }
    }

    private function cleanNumber($value): int
    {
        return (int) preg_replace('/[^0-9-]/', '', (string) $value);
    }
}
