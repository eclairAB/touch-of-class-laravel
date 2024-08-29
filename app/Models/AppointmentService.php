<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    use HasFactory;
    protected $fillable=[
        'service_id',
    ];

    function service() {
        return $this->belongsTo(Service::class);
    }
}
