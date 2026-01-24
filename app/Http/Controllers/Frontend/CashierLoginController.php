<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierLoginController extends Controller
{
    /**
     * Show the Cashier Login Page
     */
    public function showLogin()
    {
        // Check if already logged in as a cashier
        if (Auth::guard('web')->check()) {
            return redirect('/pos');
        }
        return view('app'); // Serves the Vue SPA
    }

    /**
     * Handle the Login Request
     */
   public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Add 'status' => 'active' to ensure disabled users can't enter
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

    /**
     * Handle Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}