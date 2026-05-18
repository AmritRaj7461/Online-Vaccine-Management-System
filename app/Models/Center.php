<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'pincode',
        'phone',
        'email',
        'opening_time',
        'closing_time',
        'status',
    ];

    /**
     * One center has many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
