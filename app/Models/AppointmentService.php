<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'service_id',
        'branch_id',
        'stylist_id',
        'session_no',
        'paid',
    ];

    function service() {
        return $this->belongsTo(Service::class);
    }
    function payments() {
        return $this->hasMany(Payment::class);
    }
    function service_redeems() {
        return $this->hasMany(AppointmentServiceRedeem::class);
    }
}
