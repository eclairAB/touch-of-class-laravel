<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentCombo extends Model
{
    use HasFactory;
    protected $fillable = [
        'combo_id',
        'balance',
    ];

    function appointment() {
        return $this->belongsTo(Appointment::class);
    }
    function combo() {
        return $this->belongsTo(Combo::class);
    }
    function payments() {
        return $this->hasMany(Payment::class);
    }
    function combo_redeems() {
        return $this->hasMany(AppointmentComboRedeem::class);
    }
}
