<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    // ==========================================
    //  AUTHENTICATION (Login Controller)
    // ==========================================

    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect('/pos');
        }
        return view('app'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt([
            'email' => $credentials['email'], 
            'password' => $credentials['password'],
            'status' => 'active'
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/pos');
        }

        return back()->with('error', 'Invalid credentials or inactive account.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // ==========================================
    //  MANAGEMENT ( CashierController)
    // ==========================================

    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 5;
        $search  = $request->search ?? '';

        $query = Cashier::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        $cashiers = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'data' => $cashiers->items(),
            'current_page' => $cashiers->currentPage(),
            'last_page' => $cashiers->lastPage(),
            'per_page' => $cashiers->perPage(),
            'total' => $cashiers->total(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:cashiers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $cashier = Cashier::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'status'   => 'active',
        ]);

        return response()->json(['message' => 'Cashier created', 'cashier' => $cashier]);
    }

    public function update(Request $request, $id)
    {
        $cashier = Cashier::findOrFail($id);

        $rules = [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:cashiers,email,' . $id,
            'status' => 'required|in:active,inactive',
        ];

        if ($request->old_password || $request->new_password || $request->new_password_confirmation) {
            $rules['old_password'] = 'required|string';
            $rules['new_password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);

        if ($request->old_password) {
            if (!$cashier->checkPassword($request->old_password)) {
                return response()->json(['message' => 'Old password is incorrect.'], 422);
            }
            $cashier->password = $request->new_password;
        }

        $cashier->name = $request->name;
        $cashier->email = $request->email;
        $cashier->status = $request->status;
        $cashier->save();

        return response()->json(['message' => 'Cashier updated successfully.']);
    }

    public function destroy($id)
    {
        Cashier::findOrFail($id)->delete();
        return response()->json(['message' => 'Cashier deleted']);
    }

    public function toggleStatus($id)
    {
        $cashier = Cashier::findOrFail($id);
        $cashier->status = $cashier->status === 'active' ? 'inactive' : 'active';
        $cashier->save();

        return response()->json(['message' => 'Status updated']);
    }
}