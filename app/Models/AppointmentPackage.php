<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentPackage extends Model
{
    use HasFactory;
    protected $fillable=[
        'package_id',
    ];

    function package() {
        return $this->belongsTo(Package::class);
    }
}
