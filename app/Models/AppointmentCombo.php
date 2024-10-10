<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentCombo extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'combo_id',
        'branch_id',
        'stylist_id',
        'session_no',
        'paid',
    ];

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
