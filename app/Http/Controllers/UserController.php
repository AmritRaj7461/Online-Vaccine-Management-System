<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();

        $totalAppointments    = $user->appointments()->count();
        $pendingAppointments  = $user->appointments()->where('status', 'pending')->count();
        $completedAppointments = $user->appointments()->where('status', 'completed')->count();
        $upcomingAppointments  = $user->appointments()
            ->with(['vaccine', 'center'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'user',
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'upcomingAppointments'
        ));
    }

    
    public function profile()
    {
        return view('user.profile', ['user' => Auth::user()]);
    }

    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'dob'           => 'nullable|date',
            'aadhar_number' => 'nullable|string|size:12',
        ]);

        $newAadhar = $request->input('aadhar_number');

        // Check if Aadhaar number is being changed or added
        if ($newAadhar !== $user->aadhar_number) {
            if (empty($newAadhar)) {
                $validated['aadhar_verified'] = false;
            } else {
                
                $verifiedNumber = session('aadhar_verified_number');
                if ($verifiedNumber !== $newAadhar) {
                    return redirect()->back()->withErrors([
                        'aadhar_number' => 'e-KYC Verification Required: Please verify your new Aadhaar details using the OTP gate first.'
                    ])->withInput();
                }
                // Mark as e-KYC verified
                $validated['aadhar_verified'] = true;
                session()->forget('aadhar_verified_number'); 
            }
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
