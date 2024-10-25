<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentServiceRedeem extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_service_id',
        'branch_id',
        'cashier_id',
        'stylist_id',
        'session_no',
        'paid',
    ];
    function appointment_service() {
        return $this->belongsTo(AppointmentService::class);
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
