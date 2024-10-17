<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
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
