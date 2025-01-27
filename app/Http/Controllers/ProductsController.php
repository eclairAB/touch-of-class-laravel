<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AppointmentPackage;
use App\Models\AppointmentCombo;
use App\Models\AppointmentService;
use App\Models\AppointmentPackageRedeem;
use App\Models\AppointmentComboRedeem;
use App\Models\AppointmentServiceRedeem;
use App\Models\CommissionHistory;
use App\Models\Package;

class ProductsController extends Controller
{
    function user() {
        return Auth::user();
    }

    function avail_package (Request $request) {
        $parameter = $request->toArray();
        $package_redeem = AppointmentPackageRedeem::where('appointment_package_id', $parameter['id'])
        ->whereNull('branch_id')
        ->first();
        if($package_redeem) {
            $pck = $package_redeem->appointment_package->package;
            $client_id = $package_redeem->appointment_package->appointment->client->id;
            $package_redeem_id = $package_redeem->id;
            $comm = $pck->price /  $pck->sessions;
            $total_commission = $comm * ($pck->commission_percentage / 100);
            $total_commission = number_format($total_commission, 2);
            $commision = New CommissionHistory();
            $commision->client_id = $client_id;
            $commision->package_redeem_id = $package_redeem_id;
            $commision->commission_amount = $total_commission;
            $commision->stylist_id = $parameter['stylist_id'];
            $commision->save();
            $package_redeem->update([
                'branch_id' => $parameter['branch_id'],
                'cashier_id' => $this->user()->id,
                'stylist_id' => $parameter['stylist_id'],
            ]);
        }
    }
    function avail_combo (Request $request) {
        $parameter = $request->toArray();
        $combo_redeem = AppointmentComboRedeem::where('appointment_combo_id', $parameter['id'])
                    ->whereNull('branch_id')
                    ->first();

        $combo_redeem->update([
            'branch_id' => $parameter['branch_id'],
            'cashier_id' => $this->user()->id,
            'stylist_id' => $parameter['stylist_id'],
        ]);
    }
    function avail_service (Request $request) {
        $parameter = $request->toArray();
        $service_redeem = AppointmentServiceRedeem::where('appointment_service_id', $parameter['id'])->first();
        $service_redeem->update([
            'branch_id' => $parameter['branch_id'],
            'cashier_id' => $this->user()->id,
            'stylist_id' => $parameter['stylist_id'],
        ]);
    }
}
