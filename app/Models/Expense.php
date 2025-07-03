<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id',
        'expense_name',
        'amount',
    ];
    function cashier() {
        return $this->belongsTo(User::class, 'cashier_id','id');
    }
}
