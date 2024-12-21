<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'discount_name',
        'percentage',
        'amount',
        'appointment_package_id',
        'appointment_combo_id',
        'appointment_service_id',
    ];
}
