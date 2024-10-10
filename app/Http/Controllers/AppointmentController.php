<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentPackageRedeem;
use App\Models\AppointmentComboRedeem;
use App\Models\AppointmentServiceRedeem;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    function appointment_eloquent() {
        return [
            'client',
            // 'payments',
            'packages.package_redeems.package',
            'combos.combo_redeems.combo.combo_services.service',
            'services.service_redeems.service',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with($this->appointment_eloquent())->get();
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = $request->toArray();
        $payment = [
            'amount_paid' => $request->amount_paid,
            'branch_id' => $request->branch_id,
        ];
        if($request->amount_paid >= $request->amount_payable) {
            $payload['fully_paid'] = true;
        }

        $appointment = Appointment::create($payload);
        if(isset($payload['packages'])) {
            $appointment_packages = $appointment->packages()->createMany($payload['packages']);
            $this->map_package_redeems($appointment_packages[0]['id'], $payload['packages'], $payload['branch_id']);
        }
        if(isset($payload['combos'])) {
            $appointment_combos = $appointment->combos()->createMany($payload['combos']);
            $this->map_combo_redeems($appointment_combos[0]['id'], $payload['combos'], $payload['branch_id']);
        }
        if(isset($payload['services'])) {
            $appointment_services = $appointment->services()->createMany($payload['services']);
            $this->map_service_redeems($appointment_services[0]['id'], $payload['services'], $payload['branch_id']);
        }
        // $appointment->payments()->create($payment);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::with($this->appointment_eloquent())->where('client_id', $id)->get();
        return response()->json($appointment);
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     $appointment = Appointment::findOrFail($id);
    //     $appointment->update($request->toArray());
    //     return response()->json(['message' => 'Appointment updated successfully', 'appointment' => $appointment], 200);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Appointment::where('id', $id)->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }


        function map_package_redeems($appointment_package_id, $payload, $branch_id) {
            # expects \Appointment, $payload->sessions

            foreach($payload as $key => $item) {
                for ($i=0; $i < $item['sessions']; $i++) {
                    $package_redeem[$key][] = [
                        'appointment_package_id'    => $appointment_package_id,
                        'branch_id'                 => $branch_id,
                        'stylist_id'                => null,
                        'session_no'                => 1 + $i,
                        'paid'                      => false,
                    ];
                }
                AppointmentPackageRedeem::insert($package_redeem[$key]);
            }
        }
        function map_combo_redeems($appointment_combo_id, $payload, $branch_id) {
            # expects \Appointment, $payload->sessions

            foreach($payload as $key => $item) {
                for ($i=0; $i < count($item['combo_services']); $i++) {
                    $combo_redeem[$key][] = [
                        'appointment_combo_id'  => $appointment_combo_id,
                        'branch_id'             => $branch_id,
                        'stylist_id'            => null,
                        'service_no'            => 1 + $i,
                        'paid'                  => false,
                    ];
                }
                AppointmentComboRedeem::insert($combo_redeem[$key]);
            }
        }
        function map_service_redeems($appointment_service_id, $payload, $branch_id) {
            # expects \Appointment, $payload->sessions

            foreach($payload as $key => $item) {
                // for ($i=0; $i < $item['services']; $i++) {
                    $service_redeems[$key][] = [
                        'appointment_service_id'    => $appointment_service_id,
                        'branch_id'                 => $branch_id,
                        'stylist_id'                => null,
                        // 'session_no'                => 1 + $i,
                        'paid'                      => false,
                    ];
                // }
                AppointmentServiceRedeem::insert($service_redeems[$key]);
            }
        }
}
