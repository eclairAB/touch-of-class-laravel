<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPackageRedeem extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'package_id',
        'branch_id',
        'stylist_id',
        'session_no',
        'paid',
    ];

    function package() {
        return $this->belongsTo(Package::class);
    }
}
