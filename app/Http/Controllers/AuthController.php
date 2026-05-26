<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function showLogin()
    {
        return view('auth.login', ['isRegister' => false]);
    }

    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            
            $request->session()->put('user_name', Auth::user()->name);
            $request->session()->put('user_role', Auth::user()->role);

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, Admin!')
                    ->with('celebrate', true);
            }

            return redirect()->route('user.dashboard')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!')
                ->with('celebrate', true);
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->withInput($request->only('email'))
            ->with('auth_mode', 'login'); 
    }

    
    public function showRegister()
    {
        return view('auth.login', ['isRegister' => true]);
    }

    
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
            ->with('success', 'Account created successfully! Welcome, ' . $user->name . '!')
            ->with('celebrate', true);
    }

    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
