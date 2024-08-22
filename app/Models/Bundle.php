<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'service_id',
        'commission_percentage',
        'price',
    ];
}
