<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDeduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'deduction_date',
        'is_late',
        'deduction',
    ];
}
