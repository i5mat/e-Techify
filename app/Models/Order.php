<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'created_at',
        'updated_at',
        'order_status',
    ];

    protected $dates = ['deleted_at'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
