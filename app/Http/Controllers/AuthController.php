<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('auth.login', ['isRegister' => false]);
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Store user info in session
            $request->session()->put('user_name', Auth::user()->name);
            $request->session()->put('user_role', Auth::user()->role);

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin!');
            }

            return redirect()->route('user.dashboard')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput($request->only('email'))
            ->with('auth_mode', 'login'); // Keep state on error
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        return view('auth.login', ['isRegister' => true]);
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:15'],
            'dob'      => ['nullable', 'date', 'before:today'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'dob'      => $validated['dob'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('user_name', $user->name);
        $request->session()->put('user_role', $user->role);

        return redirect()->route('user.dashboard')
            ->with('success', 'Account created successfully! Welcome, ' . $user->name . '!');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
