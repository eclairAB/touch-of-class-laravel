<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentComboRedeem extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'combo_id',
        'branch_id',
        'cashier_id',
        'stylist_id',
        'session_no',
        'paid',
    ];

    function combo() {
        return $this->belongsTo(Combo::class);
    }
}
