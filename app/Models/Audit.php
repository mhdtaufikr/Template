<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $guarded=[
        'id'
    ];

    public function auditDetails()
    {
        return $this->hasMany(AuditDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
