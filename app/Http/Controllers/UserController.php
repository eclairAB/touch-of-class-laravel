<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use App\Models\CommissionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();
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
            'role_id' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->toArray());
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function stylist_commissions(Request $req) {
        $user = Auth::user();
        $startDate = $req->input('start_date');
        $endDate = $req->input('end_date');
        $comq = CommissionHistory::where('stylist_id', $user->id);
    
        if ($startDate) {
            $comq->whereDate('created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $comq->whereDate('created_at', '<=', $endDate);
        }
    
        $commissions = $comq->get();
        $data = [];
        $totcom = 0;
    
        foreach ($commissions as $com) {
            $comamount = $com->commission_amount;
            $totcom += $comamount;
            $data[] = [
                'commission_amount' => $com->commission_amount,
                'client' => $com->client->first_name . ' ' . $com->client->last_name,
                'package' => $com->appointment_package_redeem?->appointment_package?->package?->name,
                'combo' => $com->appointment_combo_redeem?->appointment_combo?->combo?->name ?? 'N/A',
                'services' => $com->appointment_service_redeem?->appointment_service?->service?->name ?? 'N/A',
                'cashier' => $com->appointment_package_redeem->cashier->first_name.' '.$com->appointment_package_redeem->cashier->last_name,
                'created_at' => Carbon::parse($com->created_at)->format('F d, Y h:i A'),
            ];
        }
    
        return response()->json([
            'data' => $data,
            'total_commission' => number_format($totcom, 2)
        ]);
    }    

    function search_staff(Request $request) {
        $searchTerms = explode(' ', $request->search);

        $users = User::where(function($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('first_name', 'ILIKE', "%{$term}%")
                        ->orWhere('last_name', 'ILIKE', "%{$term}%");
                }
            })->where('active_employee', true);
        if(isset($request->role)) {
            $users->whereHas('role', function($query) use($request) {
                $query->where('name', $request->role);
            });
        }
        $users = $users->with('role', 'branch')->get();

        return response()->json($users);
    }

    function login(Request $request) {
        if(Auth::attempt($request->toArray())) {

            $user = Auth::user();
            $user['token'] = $user->createToken('api-token')->plainTextToken;
            $user['role'] = Role::find($user->role_id);
            $user['branch'] = Branch::whereHas('user', function ($q) use($user) {
                                $q->where('id', $user->id);
                            })->first();

            return response()->json($user);
        }
        else {
            return response()->json('Incorrect Credentials', 401);
        }
    }

    function logout() {
        Auth::user()->currentAccessToken()->delete();
        return response()->json('Logout successful');
    }
}
