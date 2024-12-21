<?php

namespace App\Http\Controllers;

use App\Models\CommissionHistory;
use App\Models\AppointmentPackageRedeem;
use App\Models\AppointmentComboRedeem;
use App\Models\AppointmentServiceRedeem;
use App\Models\Package;
use App\Models\Combo;
use App\Models\Service;

use Illuminate\Http\Request;

class CommissionController extends Controller
{
    # $product_type = service, combo, package

    function log_commission($product_type, $ammount, ) {

        $array = [
            ''
        ];
    }
}
