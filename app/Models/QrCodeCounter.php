<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCodeCounter extends Model
{
    use HasFactory;

    protected $table = 'qr_code_counter';

    protected $fillable = ['last_qr_number'];
}
