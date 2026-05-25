<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Appointment;
use App\Models\Center;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Auth::user()
            ->appointments()
            ->with(['vaccine', 'center'])
            ->orderByDesc('appointment_date')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $vaccines = Vaccine::where('status', 'available')->where('stock', '>', 0)->get();
        $centers  = Center::where('status', 'active')->get();
        $selectedVaccine = $request->query('vaccine_id') ? Vaccine::find($request->query('vaccine_id')) : null;

        return view('appointments.create', compact('vaccines', 'centers', 'selectedVaccine'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vaccine_id'       => ['required', 'exists:vaccines,id'],
            'center_id'        => ['required', 'exists:centers,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required'],
            'dose_number'      => ['required', 'integer', 'min:1', 'max:5'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending';

        $appointment = Appointment::create($validated);

        // Decrement vaccine stock
        $vaccine = Vaccine::find($validated['vaccine_id']);
        $vaccine->decrement('stock');

        try {
            Mail::to(Auth::user()->email)->send(new BookingConfirmation($appointment->load(['vaccine', 'center', 'user'])));
        } catch (\Exception $e) {
            \Log::warning('Email not sent: ' . $e->getMessage());
        }

        return redirect()->route('user.appointments.index')
            ->with('success', 'Appointment booked successfully! A confirmation email has been sent.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        $appointment->load(['vaccine', 'center']);
        return view('appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Completed appointments cannot be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        $appointment->vaccine->increment('stock');

        return redirect()->route('user.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }

    public function edit(Appointment $appointment) {}
    public function update(Request $request, Appointment $appointment) {}
}
