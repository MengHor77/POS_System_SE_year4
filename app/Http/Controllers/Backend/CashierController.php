<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    // List cashiers with pagination
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 5; // default per page
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
            'last_page'    => $cashiers->lastPage(),
            'per_page'     => $cashiers->perPage(),
            'total'        => $cashiers->total(),
        ]);
    }

    // Create cashier
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:cashiers,email',
            'password' => 'required|min:6',
        ]);

        $cashier = Cashier::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'status'   => 'active',
        ]);

        return response()->json(['message' => 'Cashier created', 'cashier' => $cashier]);
    }

    // Update cashier
public function update(Request $request, $id)
{
    $cashier = Cashier::findOrFail($id);

    // Always validate name, email, status
    $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:cashiers,email,' . $id,
        'status'=> 'required|in:active,inactive',
    ]);

    // Only validate password if user wants to change it
    if ($request->old_password || $request->new_password || $request->new_password_confirmation) {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed', // Laravel expects new_password_confirmation
        ]);

        // Check old password
        if (!$cashier->checkPassword($request->old_password)) {
            return response()->json(['message' => 'Old password is incorrect.'], 422);
        }

        // Set new password (mutator will hash automatically)
        $cashier->password = $request->new_password;
    }

    // Update other fields
    $cashier->name = $request->name;
    $cashier->email = $request->email;
    $cashier->status = $request->status;
    $cashier->save();

    return response()->json(['message' => 'Cashier updated successfully.']);
}



    // Delete cashier
    public function destroy($id)
    {
        Cashier::findOrFail($id)->delete();
        return response()->json(['message' => 'Cashier deleted']);
    }

    // Toggle status
    public function toggleStatus($id)
    {
        $cashier = Cashier::findOrFail($id);
        $cashier->status = $cashier->status === 'active' ? 'inactive' : 'active';
        $cashier->save();

        return response()->json(['message' => 'Status updated']);
    }
}