<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'amount_payable',
        'fully_paid',
    ];

    function client() {
        return $this->belongsTo(Client::class);
    }
    function appointment_packages() {
        return $this->hasMany(AppointmentPackage::class);
    }
    function appointment_combos() {
        return $this->hasMany(AppointmentCombo::class);
    }
    function appointment_services() {
        return $this->hasMany(AppointmentService::class);
    }
}
