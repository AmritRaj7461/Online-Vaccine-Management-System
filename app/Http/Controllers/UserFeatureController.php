<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use App\Models\WellnessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class UserFeatureController extends Controller
{
    /**
     * Display user notifications inbox.
     */
    public function notifications()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a notification as read.
     */
    public function markNotificationRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized.');
        }

        $notification->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Render the digital Vaccine Wallet Pass.
     */
    public function walletPass(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status !== 'completed') {
            return redirect()->route('user.appointments.index')
                ->with('error', 'Wallet Pass is only available for completed vaccinations.');
        }

        $appointment->load(['vaccine', 'center', 'user']);

        // Generate signed verification URL for QR code scan
        $verificationUrl = URL::signedRoute('verify.certificate', ['appointment' => $appointment->id]);

        return view('appointments.pass', compact('appointment', 'verificationUrl'));
    }

    /**
     * Render the live queue tracking dashboard.
     */
    public function queueStatus(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return redirect()->route('user.appointments.index')
                ->with('error', 'Queue tracking is only available for scheduled appointments.');
        }

        $appointment->load(['vaccine', 'center']);

        // Queue Simulation math
        $userToken = ($appointment->id % 40) + 12; // Dynamic but deterministic token
        
        // Simulating serving token: slightly behind user token
        $servingToken = $userToken - 4;
        if ($servingToken < 1) {
            $servingToken = 1;
        }

        $peopleAhead = $userToken - $servingToken;
        $waitTimeMinutes = $peopleAhead * 5; // 5 mins per person

        $density = 'Low';
        if ($waitTimeMinutes > 25) {
            $density = 'High';
        } elseif ($waitTimeMinutes > 12) {
            $density = 'Medium';
        }

        return view('appointments.queue', compact('appointment', 'userToken', 'servingToken', 'peopleAhead', 'waitTimeMinutes', 'density'));
    }

    /**
     * Render the post-vaccination wellness / side-effects logger.
     */
    public function wellnessLogForm(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status !== 'completed') {
            return redirect()->route('user.appointments.index')
                ->with('error', 'Wellness logs are only available for completed vaccinations.');
        }

        $appointment->load(['vaccine', 'center', 'wellnessLog']);

        return view('appointments.wellness', compact('appointment'));
    }

    /**
     * Write wellness side-effect logs to database.
     */
    public function storeWellnessLog(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($appointment->status !== 'completed') {
            abort(400, 'Invalid appointment status.');
        }

        $validated = $request->validate([
            'fever'          => 'nullable|boolean',
            'soreness'       => 'nullable|boolean',
            'headache'       => 'nullable|boolean',
            'fatigue'        => 'nullable|boolean',
            'other_symptoms' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['logged_at'] = now();

        // Convert parameters to standard booleans (checkboxes send '1' or are omitted)
        $validated['fever'] = $request->has('fever');
        $validated['soreness'] = $request->has('soreness');
        $validated['headache'] = $request->has('headache');
        $validated['fatigue'] = $request->has('fatigue');

        // Create or update log
        WellnessLog::updateOrCreate(
            ['appointment_id' => $appointment->id],
            $validated
        );

        return redirect()->route('user.appointments.wellness', $appointment)
            ->with('success', 'Wellness details logged successfully! Check the medical guidance below.');
    }
}
