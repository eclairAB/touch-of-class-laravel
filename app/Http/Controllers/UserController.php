<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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






    function search_staff(Request $request) {
        $searchTerms = explode(' ', $request->search);

        $users = User::where(function($query) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $query->orWhere('first_name', 'ILIKE', "%{$term}%")
                        ->orWhere('last_name', 'ILIKE', "%{$term}%");
                }
            })->with('role')->get();

        return response()->json($users);
    }

    function login(Request $request) {
        if(Auth::attempt($request->toArray())) {

            $user = Auth::user();
            $user['token'] = $user->createToken('api-token')->plainTextToken;
            $user['role'] = Role::find($user->id);

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
