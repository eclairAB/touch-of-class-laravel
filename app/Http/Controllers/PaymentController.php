<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\AppointmentPackageRedeem;
use App\Models\AppointmentComboRedeem;
use App\Models\AppointmentServiceRedeem;
use App\Models\AppointmentPackage;
use App\Models\AppointmentCombo;
use App\Models\AppointmentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(
                'branch',
                'cashier',
                'appointment_package.appointment.client',
                'appointment_combo.appointment.client',
                'appointment_service.appointment.client'
            )->orderBy('id','desc')->get();
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $service = Payment::create($request->toArray());
        return response()->json($service, 201);
    }

    public function make_payment(Request $request) {
        $payment_payload = [
            'amount_paid' => $request->amount,
            'payment_milestone' => 'Follow Up payment',
            'payment_method' => $request->payment_type,
            'reference_no' => $request->reference,
            'biller_name' => $request->biller,
        ];

        if(isset($request->package_redeem_id)) {
            $redeem = AppointmentPackage::find($request->package_redeem_id);
            $redeem->balance-=$request->amount;
            $redeem->save();

            $payment_payload['appointment_package_id'] = $redeem->id;

            $product = AppointmentPackageRedeem::whereHas('appointment_package', function($q) use($request) {
                $q->where('id', $request->package_redeem_id);
            })->where('paid', false)->first();
            if($product) {
                $product->paid = true;
                $product->save();
            }
        }
        elseif(isset($request->combo_redeem_id)) {
            $redeem = AppointmentCombo::find($request->combo_redeem_id);
            $redeem->balance-=$request->amount;
            $redeem->save();

            $payment_payload['appointment_combo_id'] = $redeem->id;

            $product = AppointmentComboRedeem::whereHas('appointment_combo', function($q) use($request) {
                $q->where('id', $request->combo_redeem_id);
            })->where('paid', false)->first();
            if($product) {
                $product->paid = true;
                $product->save();
            }
        }
        elseif(isset($request->service_redeem_id)) {
            $redeem = AppointmentService::find($request->service_redeem_id);
            $redeem->balance-=$request->amount;
            $redeem->save();

            $payment_payload['appointment_service_id'] = $redeem->id;

            $product = AppointmentServiceRedeem::whereHas('appointment_service', function($q) use($request) {
                $q->where('id', $request->service_redeem_id);
            })->where('paid', false)->first();
            if($product) {
                $product->paid = true;
                $product->save();
            }
        }

        Payment::create($payment_payload);
        return response()->json(200);
    }
}
