<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPackage extends Model
{
    use HasFactory;
    protected $fillable=[
        'package_id',
        'balance',
    ];

    function appointment() {
        return $this->belongsTo(Appointment::class);
    }
    function package() {
        return $this->belongsTo(Package::class);
    }
    function payments() {
        return $this->hasMany(Payment::class);
    }
    function package_redeems() {
        return $this->hasMany(AppointmentPackageRedeem::class);
    }
}
