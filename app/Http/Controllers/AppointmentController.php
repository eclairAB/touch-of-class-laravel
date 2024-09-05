<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('client', 'packages.package', 'combos.combo', 'services.service', 'payments', )->get();
        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = $request->toArray();
        $payment = [
            'amount_paid' => $request->initial_payment,
        ];
        if($request->initial_payment >= $request->amount_payable) {
            $payload['fully_paid'] = true;
        }

        $appointment = Appointment::create($payload);
        if(isset($payload['packages'])) { $appointment->packages()->createMany($payload['packages']); }
        if(isset($payload['combos'])) { $appointment->combos()->createMany($payload['combos']); }
        if(isset($payload['services'])) { $appointment->services()->createMany($payload['services']); }
        $appointment->payments()->create($payment);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::find($id);
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
}
