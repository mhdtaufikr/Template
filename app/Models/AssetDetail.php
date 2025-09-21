<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetDetail extends Model
{
    protected $fillable = [
        'asset_header_id', 'asset_no', 'sub_asset', 'desc', 'qty', 'uom', 'asset_type',
        'date', 'cost', 'po_no', 'serial_no', 'img', 'status', 'bv_endofyear'
    ];

    public function assetHeader()
    {
        return $this->belongsTo(AssetHeader::class);
    }
}
