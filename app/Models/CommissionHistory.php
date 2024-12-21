<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id',
        'client_id',
        'package_redeem_id',
        'combo_redeem_id',
        'service_redeem_id',
        'commission_amount',
    ];
}
