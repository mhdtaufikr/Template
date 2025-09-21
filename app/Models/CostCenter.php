<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    protected $fillable = ['cost_ctr', 'coar', 'cocd', 'cctc', 'pic', 'user_pic', 'remarks'];
}
