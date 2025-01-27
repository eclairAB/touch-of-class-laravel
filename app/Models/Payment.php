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
        'reference_no',
        'biller_name',
        'discount_paid',
        'discount_id',
    ];

    function branch() {
        return $this->belongsTo(Branch::class);
    }
    function cashier() {
        return $this->belongsTo(User::class);
    }
    function appointment_package() {
        return $this->belongsTo(AppointmentPackage::class);
    }
    function appointment_combo() {
        return $this->belongsTo(AppointmentCombo::class);
    }
    function appointment_service() {
        return $this->belongsTo(AppointmentService::class);
    }
}
