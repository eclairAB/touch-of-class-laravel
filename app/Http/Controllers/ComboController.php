<?php
namespace App\Http\Controllers;

use App\Models\Combo;
use App\Models\ComboService;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $combos = Combo::with('combo_services.service')->get();
        return response()->json($combos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $services = [];
        foreach ($request->services as $item) {
            $services[] = [
                'service_id' => $item,
            ];
        }

        $combo = Combo::create($request->toArray())->combo_services()->createMany($services);
        return response()->json($combo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $combo = Combo::with('combo_services.service')->find($id);
        return response()->json($combo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $services = [];
        foreach ($request->services as $item) {
            $services[] = [
                'combo_id' => $id,
                'service_id' => $item,
            ];
        }

        $combo = Combo::findOrFail($id);
        $combo->update($request->toArray());

        $combo_service = ComboService::where('combo_id', $id);
        $combo_service->delete();
        $combo_service->insert($services);

        return response()->json(['message' => 'Combo updated successfully', 'combo' => $combo], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Combo::where('id', $id)->delete();
        return response()->json(['message' => 'Combo deleted successfully'], 200);
    }
}
