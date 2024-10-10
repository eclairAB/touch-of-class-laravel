<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentServiceRedeem extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_service_id',
        'branch_id',
        'cashier_id',
        'stylist_id',
        'session_no',
        'paid',
    ];
}
