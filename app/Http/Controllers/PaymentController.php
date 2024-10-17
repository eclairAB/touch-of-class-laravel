<?php
namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(
            'appointment.package_redeems',
            'appointment.combo_redeems',
            'appointment.service_redeems',
            )->get();
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

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     $service = Payment::find($id);
    //     return response()->json($service);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     $service = Payment::findOrFail($id);
    //     $service->update($request->toArray());
    //     return response()->json(['message' => 'Payment updated successfully', 'service' => $service], 200);
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     Payment::where('id', $id)->delete();
    //     return response()->json(['message' => 'Payment deleted successfully'], 200);
    // }
}
