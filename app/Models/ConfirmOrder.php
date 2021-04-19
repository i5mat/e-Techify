<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'addresses_id',
        'payment_total',
        'payment_method',
        'created_at',
        'updated_at',
        'tracking_num',
    ];
}
