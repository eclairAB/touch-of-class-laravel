<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id',
        'client_id',
        'package_redeem_id',
        'combo_redeem_id',
        'service_redeem_id',
        'commission_amount',
    ];
    function client() {
        return $this->belongsTo(Client::class);
    }
    function stylist() {
        return $this->belongsTo(User::class, 'stylist_id','id');
    }
    function appointment_package_redeem() {
        return $this->belongsTo(AppointmentPackageRedeem::class, 'package_redeem_id','id');
    }
    function appointment_combo_redeem() {
        return $this->belongsTo(AppointmentComboRedeem::class, 'combo_redeem_id','id');
    }
    function appointment_service_redeem() {
        return $this->belongsTo(AppointmentServiceRedeem::class, 'service_redeem_id','id');
    }
}
