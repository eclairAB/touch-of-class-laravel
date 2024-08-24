<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'package_id',
        'bundle_id',
        'service_id',
        'amount_payable',
        'fully_paid',
    ];

    function client() {
        return $this->belongsTo(Client::class);
    }
    function package() {
        return $this->belongsTo(Package::class);
    }
    function bundle() {
        return $this->belongsTo(Bundle::class);
    }
    function service() {
        return $this->belongsTo(Service::class);
    }
    function payments() {
        return $this->hasMany(Payment::class);
    }
}
