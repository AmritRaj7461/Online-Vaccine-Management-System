<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Center;
use App\Models\User;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class AdminAppointmentController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'vaccine', 'center']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"));
        }

        $appointments = $query->orderByDesc('appointment_date')->paginate(15);

        
        $stats = [
            'total'     => Appointment::count(),
            'pending'   => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'stats'));
    }

    
    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'vaccine', 'center']);
        return view('admin.appointments.show', compact('appointment'));
    }

    
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $appointment->update(['status' => $request->status]);

        // Trigger Notification
        $statusLabels = [
            'confirmed' => 'Approved & Scheduled',
            'completed' => 'Immunization Completed',
            'cancelled' => 'Cancelled',
            'pending'   => 'Pending Approval',
        ];
        $label = $statusLabels[$request->status] ?? ucfirst($request->status);

        $messages = [
            'confirmed' => "Your appointment for {$appointment->vaccine->name} at {$appointment->center->name} has been confirmed. Please check the Live Queue Tracker on your dashboard before arrival.",
            'completed' => "Congratulations! Your vaccination dose #{$appointment->dose_number} of {$appointment->vaccine->name} is complete. Your official digital e-certificate and Vaccine Wallet Pass are now available.",
            'cancelled' => "Your appointment for {$appointment->vaccine->name} scheduled on {$appointment->appointment_date->format('d M Y')} has been cancelled. Allocation stock has been restored.",
            'pending'   => "Your appointment is set back to pending review by the center administrator.",
        ];
        $msg = $messages[$request->status] ?? "Your appointment status was updated to {$request->status}.";

        $type = 'info';
        if ($request->status === 'completed' || $request->status === 'confirmed') {
            $type = 'success';
        } elseif ($request->status === 'cancelled') {
            $type = 'warning';
        }

        \App\Models\Notification::create([
            'user_id' => $appointment->user_id,
            'title'   => "Appointment {$label}",
            'message' => $msg,
            'type'    => $type,
        ]);

        return back()->with('success', 'Appointment status updated to ' . ucfirst($request->status) . '.');
    }

    public function create() {}
    public function store(Request $request) {}
    public function edit(Appointment $appointment) {}
    public function destroy(Appointment $appointment) {}
}
