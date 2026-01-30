<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * 🔹 List all admins (for table in Index.vue)
     */
    public function list()
    {
        return response()->json(Admin::select('id','name','email')->get());
    }

    /**
     * 🔹 Show single admin (for Edit.vue)
     */
    public function show($id)
    {
        return response()->json(Admin::findOrFail($id));
    }

    /**
     * 🔹 Update admin (with old password check)
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'old_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed', 
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        // If changing password, check old password
        if ($request->filled('password')) {
            if (!$request->filled('old_password') || !Hash::check($request->old_password, $admin->password)) {
                return response()->json(['message' => 'Old password is incorrect'], 422);
            }
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return response()->json([
            'message' => 'Admin updated successfully',
            'admin' => $admin,
        ]);
    }
}