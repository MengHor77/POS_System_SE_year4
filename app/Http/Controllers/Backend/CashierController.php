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

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:cashiers,email,' . $id,
            'password' => 'nullable|min:6',
            'status'   => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'email', 'status']);
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $cashier->update($data);

        return response()->json(['message' => 'Cashier updated']);
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
