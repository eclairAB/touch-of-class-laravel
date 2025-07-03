<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Branch::get();
        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $branch = Branch::create($request->toArray());
        return response()->json($branch, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = Branch::find($id);
        return response()->json($branch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->update($request->toArray());
        return response()->json(['message' => 'Branch updated successfully', 'branch' => $branch], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Branch::where('id', $id)->delete();
        return response()->json(['message' => 'Branch deleted successfully'], 200);
    }
}
