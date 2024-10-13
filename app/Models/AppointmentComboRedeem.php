<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentComboRedeem extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_combo_id',
        'branch_id',
        'cashier_id',
        'stylist_id',
        'service_id',
        'paid',
    ];

    function service() {
        return $this->belongsTo(Service::class);
    }
    function stylist() {
        return $this->belongsTo(User::class, 'stylist_id', 'id');
    }
    function cashier() {
        return $this->belongsTo(User::class, 'cashier_id', 'id');
    }
    function branch() {
        return $this->belongsTo(Branch::class);
    }
}
