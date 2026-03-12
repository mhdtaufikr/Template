<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditDetail extends Model
{
    use HasFactory;

    protected $guarded=[
        'id'
    ];


   // Di AuditDetail model, pastikan foreign key & owner key benar
   public function asset(){
    return $this->belongsTo(AssetHeader::class, 'asset_id', 'asset_no');
}


    public function audit(){
        return $this->belongsTo(Audit::class, 'audit_id');
    }
}
