<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'qouta_nurse',
        'rent_expense',
    ];

    function user() {
        return $this->hasMany(User::class, 'assigned_branch_id', 'id');
    }
}
