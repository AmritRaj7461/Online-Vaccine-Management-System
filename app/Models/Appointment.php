<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vaccine_id',
        'center_id',
        'appointment_date',
        'appointment_time',
        'dose_number',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * Appointment belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Appointment belongs to a vaccine.
     */
    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    /**
     * Appointment belongs to a center.
     */
    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
