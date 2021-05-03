<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'addresses_id',
        'product_id',
        'sn_no',
        'date_of_purchase',
        'file_path',
        'reason',
        'created_at',
        'updated_at'
    ];
}
