<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'user_id',
        'product_name',
        'product_sn',
        'product_image_path',
        'product_category',
        'product_brand',
        'product_warranty_duration',
        'created_at',
        'updated_at',
        'product_price',
        'product_link',
    ];
}
