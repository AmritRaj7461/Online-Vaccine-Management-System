<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellnessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'user_id',
        'fever',
        'soreness',
        'headache',
        'fatigue',
        'other_symptoms',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'fever' => 'boolean',
        'soreness' => 'boolean',
        'headache' => 'boolean',
        'fatigue' => 'boolean',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
