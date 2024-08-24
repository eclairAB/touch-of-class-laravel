<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id',
        'amount_paid',
    ];
    function appointment() {
        return $this->belongsTo(Appointment::class);
    }
}
