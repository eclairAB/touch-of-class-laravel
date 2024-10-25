<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_package_id',
        'appointment_combo_id',
        'appointment_service_id',
        'amount_paid',
        'branch_id',
        'cashier_id',
        'payment_milestone',
        'payment_method',
    ];

    function branch() {
        return $this->belongsTo(Branch::class);
    }
    function cashier() {
        return $this->belongsTo(User::class);
    }
}
