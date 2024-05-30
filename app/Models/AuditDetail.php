<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditDetail extends Model
{
    use HasFactory;

    protected $fillable = ['audit_id', 'asset_id', 'condition', 'remark'];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
