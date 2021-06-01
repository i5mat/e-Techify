<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'product_id',
        'product_order_quantity',
        'created_at',
        'updated_at'
    ];
}
