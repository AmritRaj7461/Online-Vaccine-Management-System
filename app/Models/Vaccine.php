<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer',
        'description',
        'doses_required',
        'days_between_doses',
        'status',
        'stock',
        'price',
        'image',
    ];

    /**
     * One vaccine has many appointments.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
