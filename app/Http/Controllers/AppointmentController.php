<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    function appointment_eloquent() {
        return [
            'client',
            // 'packages.package',
            // 'combos.combo',
            // 'services.service',
            'payments',
            'package_redeems.package',
            'combo_redeems.combo',
            'service_redeems.service',
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
            $appointment->packages()->createMany($payload['packages']);
            $this->map_package_redeems($appointment, $payload['packages']);
        }
        if(isset($payload['combos'])) {
            $appointment->combos()->createMany($payload['combos']);
            $this->map_combo_redeems($appointment, $payload['combos']);
        }
        if(isset($payload['services'])) {
            $appointment->services()->createMany($payload['services']);
            $this->map_service_redeems($appointment, $payload['services']);
        }
        $appointment->payments()->create($payment);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::with($this->appointment_eloquent())->find($id);
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


        function map_package_redeems($appointment, $payload) {
            # expects \Appointment, $payload->sessions

            foreach($payload as $key => $item) {
                for ($i=0; $i < $item['sessions']; $i++) {
                    $package_redeem[$key][] = [
                        'package_id'        => $item['id'],
                        'branch_id'         => null,
                        'stylist_id'        => null,
                        'session_no'        => 1 + $i,
                        'paid'              => false,
                    ];
                }
                $appointment->package_redeems()->createMany($package_redeem[$key]);
            }
        }
        function map_combo_redeems($appointment, $payload) {
            # expects \Appointment, $payload->sessions

            foreach($payload as $key => $item) {
                for ($i=0; $i < count($item['combo_services']); $i++) {
                    $combo_redeem[$key][] = [
                        'combo_id'          => $item['id'],
                        'branch_id'         => null,
                        'stylist_id'        => null,
                        'service_no'        => 1 + $i,
                        'paid'              => false,
                    ];
                }
                $appointment->combo_redeems()->createMany($combo_redeem[$key]);
            }
        }
        function map_service_redeems($appointment, $payload) {
            # expects \Appointment, $payload->sessions

            foreach($payload as $key => $item) {
                for ($i=0; $i < $item['sessions']; $i++) {
                    $service_redeems[$key][] = [
                        'service_id'        => $item['id'],
                        'branch_id'         => null,
                        'stylist_id'        => null,
                        // 'session_no'        => 1 + $i,
                        'paid'              => false,
                    ];
                }
                $appointment->service_redeems()->createMany($service_redeems[$key]);
            }
        }
}
