<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\StaffDeduction;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchTerms = explode(' ', $request->search);

        $users = User::where(function($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('first_name', 'LIKE', "%{$term}%")
                        ->orWhere('last_name', 'LIKE', "%{$term}%");
                }
            })->where('active_employee', true)
            ->whereDoesntHave('role', function ($query) {
                $query->whereIn('id', [1, 2]); // Exclude users with roles 1 and 2
            });
        $users = $users->with('branch')->get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'nullable',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'assigned_branch_id' => 'required',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role_id'] = 5;
        $user = User::create($validated);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function emp_deduction(Request $request)
    {
        $vData = $request->validate([
            'isLate' => 'required|boolean',
            'deductDate' => 'required|date|before_or_equal:today',
            'deduction' => 'required|numeric|min:0|max:1000000',
        ]);
        $vData['deductDate'] = Carbon::createFromFormat('F d, Y', $vData['deductDate'])->format('Y-m-d');
        $deduction = new StaffDeduction();
        $deduction->staff_id = $request->id;
        $deduction->deduction_date = $vData['deductDate'];
        $deduction->is_late = $vData['isLate'];
        $deduction->deduction = $vData['deduction'];
        $deduction->save();

        return response()->json([
            'message' => 'Employee Staff Deduction Created!',
            'data' => $deduction,
        ], 201);
    }
}
