<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Center;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'        => User::where('role', 'user')->count(),
            'total_vaccines'     => Vaccine::count(),
            'total_centers'      => Center::count(),
            'total_appointments' => Appointment::count(),
            'pending'            => Appointment::where('status', 'pending')->count(),
            'confirmed'          => Appointment::where('status', 'confirmed')->count(),
            'completed'          => Appointment::where('status', 'completed')->count(),
            'cancelled'          => Appointment::where('status', 'cancelled')->count(),
        ];

        $recentAppointments = Appointment::with(['user', 'vaccine', 'center'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $topVaccines = Vaccine::withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentAppointments', 'topVaccines'));
    }

    
    public function profile()
    {
        $admin = auth()->user();
        
        
        $stats = [
            'total_users'        => \App\Models\User::where('role', 'user')->count(),
            'total_vaccines'     => \App\Models\Vaccine::count(),
            'total_centers'      => \App\Models\Center::count(),
            'total_appointments' => \App\Models\Appointment::count(),
        ];

        return view('admin.profile', compact('admin', 'stats'));
    }

    
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'dob'   => 'nullable|date',
        ]);

        $admin->update($validated);

        return redirect()->back()->with('success', 'Admin profile updated successfully!');
    }

    /**
     * Show the admin scanner portal view.
     */
    public function showScanner()
    {
        return view('admin.scanner');
    }

    /**
     * Get patient info for the scanner via AJAX.
     */
    public function getPatientInfo(User $user)
    {
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'dob' => $user->dob ? $user->dob->format('d M Y') : 'Not set',
            'aadhar_verified' => $user->aadhar_verified,
            'aadhar_number' => $user->aadhar_number,
            'reference_id' => '#PAT-' . str_pad($user->id, 5, '0', STR_PAD_LEFT)
        ]);
    }

    /**
     * Verify a patient's Aadhaar from the scanner submission via AJAX.
     */
    public function verifyPatientAadhar(Request $request, User $user)
    {
        $request->validate([
            'aadhar_number' => ['required', 'numeric', 'digits:12'],
        ]);

        $user->update([
            'aadhar_number' => $request->aadhar_number,
            'aadhar_verified' => true,
        ]);

        // Trigger notification to patient
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title'   => 'Aadhaar Verified Offline',
            'message' => 'Your Aadhaar card has been verified offline by the healthcare staff at the center. Online appointment bookings are now unlocked.',
            'type'    => 'success',
        ]);

        return response()->json([
            'success' => true,
            'message' => "Aadhaar verified successfully for {$user->name}!",
            'user' => $user
        ]);
    }
}
