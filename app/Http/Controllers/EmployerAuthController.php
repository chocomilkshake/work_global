<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('employer_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('employer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('employer.dashboard'));
        }

        return back()->withErrors(["email" => 'These credentials do not match our records.'])->onlyInput('email');
    }
}
