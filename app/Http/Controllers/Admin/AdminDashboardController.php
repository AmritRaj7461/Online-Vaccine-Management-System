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
}
