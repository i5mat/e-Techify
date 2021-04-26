<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tracking_no',
        'created_at',
        'updated_at',
        'current_status'
    ];
}
