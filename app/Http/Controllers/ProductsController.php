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
            $total_commission = number_format($total_commission, 2, '.', '');
            $commision = New CommissionHistory();
            $commision->client_id = $client_id;
            $commision->package_redeem_id = $package_redeem_id;
            $commision->commission_amount = $total_commission;
            $commision->stylist_id = $parameter['stylist_id'];
            $commision->gross_sale = number_format($comm, 2, '.', '');
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
        if($combo_redeem) {
            $combo = $combo_redeem->appointment_combo->combo;
            $client_id = $combo_redeem->appointment_combo->appointment->client->id;
            $combo_redeem_id = $combo_redeem->id;
            $combo_price = $combo->price;
            $combo_div = $combo->combo_services->count();
            $total_combo = $combo_price / $combo_div;
            $overall_commission = 0;
            $combo_service = $combo_redeem->service;
            $gross_sale = $combo_redeem->service->price;
            $commission_percentage = $combo_service->commission_percentage;
            $total_commission = $total_combo * ($commission_percentage / 100);
            $total_commission = number_format($total_commission, 2, '.', '');
            $overall_commission += $total_commission; 
            $commision = New CommissionHistory();
            $commision->client_id = $client_id;
            $commision->combo_redeem_id = $combo_redeem_id;
            $commision->commission_amount = $overall_commission;
            $commision->stylist_id = $parameter['stylist_id'];
            $commision->gross_sale = number_format($gross_sale, 2, '.', '');
            $commision->save();
            $combo_redeem->update([
                'branch_id' => $parameter['branch_id'],
                'cashier_id' => $this->user()->id,
                'stylist_id' => $parameter['stylist_id'],
            ]);
        }
    }
    function avail_service (Request $request) {
        $parameter = $request->toArray();
        $service_redeem = AppointmentServiceRedeem::where('appointment_service_id', $parameter['id'])->first();
        if($service_redeem) {
            $serv = $service_redeem->appointment_service->service;
            $client_id = $service_redeem->appointment_service->appointment->client->id;
            $service_redeem_id = $service_redeem->id;
            $comm = $serv->price;
            $total_commission = $comm * ($serv->commission_percentage / 100);
            $total_commission = number_format($total_commission, 2, '.', '');
            $commision = New CommissionHistory();
            $commision->client_id = $client_id;
            $commision->service_redeem_id = $service_redeem_id;
            $commision->commission_amount = $total_commission;
            $commision->stylist_id = $parameter['stylist_id'];
            $commision->gross_sale = number_format($comm, 2, '.', '');
            $commision->save();
            $service_redeem->update([
                'branch_id' => $parameter['branch_id'],
                'cashier_id' => $this->user()->id,
                'stylist_id' => $parameter['stylist_id'],
            ]);
        }
    }
}
