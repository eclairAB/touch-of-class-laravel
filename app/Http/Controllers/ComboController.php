<?php
namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;

class ComboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $combos = Combo::with('service')->get();
        return response()->json($combos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $combo = Combo::create($request->toArray());
        return response()->json($combo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $combo = Combo::with('service')->find($id);
        return response()->json($combo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $combo = Combo::findOrFail($id);
        $combo->update($request->toArray());
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
