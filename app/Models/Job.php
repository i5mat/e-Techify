<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_name',
        'job_salary',
        'job_location',
        'job_type',
        'created_at',
        'updated_at',
        'status'
    ];
}
