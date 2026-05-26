<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Models\Appointment;
use App\Models\Center;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

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
        if (!Auth::user()->aadhar_verified) {
            return redirect()->route('user.aadhar.exemption-pass');
        }

        $vaccines = Vaccine::where('status', 'available')->where('stock', '>', 0)->get();
        $centers  = Center::where('status', 'active')->get();
        $selectedVaccine = $request->query('vaccine_id') ? Vaccine::find($request->query('vaccine_id')) : null;

        return view('appointments.create', compact('vaccines', 'centers', 'selectedVaccine'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->aadhar_verified) {
            return redirect()->route('user.aadhar.exemption-pass');
        }

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

        // Trigger Notification
        \App\Models\Notification::create([
            'user_id' => Auth::id(),
            'title'   => 'Appointment Booked',
            'message' => "Your appointment for {$vaccine->name} at {$appointment->center->name} has been booked successfully for {$appointment->appointment_date->format('d M Y')} at " . \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') . ". It is currently pending center approval.",
            'type'    => 'info',
        ]);

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

    public function certificate(Appointment $appointment)
    {
        if (Auth::id() !== $appointment->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status !== 'completed') {
            return redirect()->route('user.appointments.index')
                ->with('error', 'Certificate is only available for completed vaccinations.');
        }

        $appointment->load(['vaccine', 'center', 'user']);

        // Build the signed URL using the actual request host, not APP_URL.
        // This prevents signature mismatches when APP_URL=http://localhost but
        // the app is accessed from a different host (e.g. Render, ngrok, phone scan).
        $verificationUrl = URL::signedRoute(
            'verify.certificate',
            ['appointment' => $appointment->id],
            null,       // no expiry
            true        // absolute = true
        );

        // Swap out the APP_URL host with the actual request host so that
        // the QR code works from any device that can reach the server.
        $appHost   = parse_url(config('app.url'), PHP_URL_HOST);
        $realHost  = request()->getSchemeAndHttpHost();
        if ($appHost && $appHost !== request()->getHost()) {
            $verificationUrl = str_replace(
                config('app.url'),
                $realHost,
                $verificationUrl
            );
        }

        return view('appointments.certificate', compact('appointment', 'verificationUrl'));
    }

    public function verifyCertificate(Request $request, Appointment $appointment)
    {
        // Validate the signed URL signature. We also normalise the URL to
        // tolerate host mismatches that occur when APP_URL differs from the
        // actual server host (e.g. localhost vs. Render deployment URL).
        $valid = $request->hasValidSignature();

        if (!$valid) {
            // Re-check by swapping the real host back to APP_URL's host and
            // re-testing, to tolerate host mismatch in older signed URLs.
            try {
                $appUrl    = rtrim(config('app.url'), '/');
                $realUrl   = rtrim($request->getSchemeAndHttpHost(), '/');
                $fullUrl   = $request->fullUrl();
                $swapped   = str_replace($realUrl, $appUrl, $fullUrl);
                $fakeReq   = \Illuminate\Http\Request::create($swapped);
                $valid     = URL::hasValidSignature($fakeReq);
            } catch (\Throwable $e) {
                $valid = false;
            }
        }

        if (!$valid) {
            return response()->view('appointments.verify', [
                'success' => false,
                'message' => 'Invalid or Tampered Certificate Signature! The record authenticity could not be verified.'
            ], 403);
        }

        if ($appointment->status !== 'completed') {
            return response()->view('appointments.verify', [
                'success' => false,
                'message' => 'This vaccination appointment is not completed yet.'
            ], 400);
        }

        $appointment->load(['vaccine', 'center', 'user']);

        return view('appointments.verify', [
            'success' => true,
            'appointment' => $appointment
        ]);
    }
}
