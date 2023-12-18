<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocDetail extends Model
{
    protected $fillable = ['loc_header_id', 'name'];

    public function locHeader()
    {
        return $this->belongsTo(LocHeader::class);
    }
}
