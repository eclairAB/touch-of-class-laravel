<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'service_id',
        'price',
    ];

    function service() {
        return $this->belongsTo(Service::class);
    }
}
