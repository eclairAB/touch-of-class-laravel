<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentCombo extends Model
{
    use HasFactory;
    protected $fillable=[
        'combo_id',
    ];

    function package() {
        return $this->belongsTo(Combo::class);
    }
}
