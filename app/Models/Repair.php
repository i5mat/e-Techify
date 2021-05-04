<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $table = 'repairs';

    protected $fillable = [
        'addresses_id',
        'product_id',
        'user_id',
        'sn_no',
        'date_of_purchase',
        'file_path',
        'reason',
        'status',
        'created_at',
        'updated_at'
    ];
}
