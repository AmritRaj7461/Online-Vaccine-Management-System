<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view the appointment.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete/cancel the appointment.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $user->id === $appointment->user_id || $user->isAdmin();
    }
}
