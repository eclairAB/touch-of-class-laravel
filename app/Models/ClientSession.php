<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'client_id',
        'completed',
    ];
}
