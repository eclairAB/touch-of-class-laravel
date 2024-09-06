<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
    ];

    function combo_services() {
        return $this->hasMany(ComboService::class);
    }
}
