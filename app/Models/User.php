<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'dob',
        'aadhar_number',
        'aadhar_verified',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'aadhar_verified' => 'boolean',
        ];
    }

    /**
     * One user has many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
