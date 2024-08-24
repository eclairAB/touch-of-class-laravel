<?php
namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bundles = Bundle::with('service')->get();
        return response()->json($bundles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bundle = Bundle::create($request->toArray());
        return response()->json($bundle, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bundle = Bundle::with('service')->find($id);
        return response()->json($bundle);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bundle = Bundle::findOrFail($id);
        $bundle->update($request->toArray());
        return response()->json(['message' => 'Bundle updated successfully', 'bundle' => $bundle], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Bundle::where('id', $id)->delete();
        return response()->json(['message' => 'Bundle deleted successfully'], 200);
    }
}
