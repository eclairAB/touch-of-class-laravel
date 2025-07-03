<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Auth;
class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchTerms = explode(' ', $request->search);

        $expense = Expense::where(function($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('expense_name', 'LIKE', "%{$term}%");
                }
            });
        $expense = $expense->get();
        return response()->json($expense);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cashier_id = Auth::user()->id;
        $data = $request->toArray(); 
        $data['cashier_id'] = $cashier_id; 

        $expense = Expense::create($data);
        return response()->json($expense, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = Expense::find($id);
        return response()->json($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->update($request->toArray());
        return response()->json(['message' => 'Expense updated successfully', 'expense' => $expense], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Expense::where('id', $id)->delete();
        return response()->json(['message' => 'Expense deleted successfully'], 200);
    }
}
