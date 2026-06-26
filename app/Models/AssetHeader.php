<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetHeader extends Model
{
    protected $table = 'asset_headers'; // pastikan nama tabel benar
    protected $primaryKey = 'id';       // pastikan primary key benar

    protected $fillable = [
        'asset_no',
        'desc',
        'qty',
        'uom',
        'asset_type',
        'acq_date',
        'acq_cost',
        'po_no',
        'serial_no',
        'dept',
        'plant',
        'loc',
        'cost_center',
        'segment',
        'img',
        'status',
        'remarks',
        'bv_endofyear',
    ];


    public function details(): HasMany
    {
        return $this->hasMany(AssetDetail::class, 'asset_header_id');
    }

}
