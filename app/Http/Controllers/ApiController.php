<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Vaccine;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    
    public function vaccines()
    {
        $vaccines = Vaccine::where('status', 'available')
            ->select('id', 'name', 'manufacturer', 'description', 'doses_required', 'price', 'stock')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $vaccines,
            'total'   => $vaccines->count(),
        ]);
    }

    
    public function appointments()
    {
        $appointments = Appointment::with(['user:id,name,email', 'vaccine:id,name', 'center:id,name,city'])
            ->select('id', 'user_id', 'vaccine_id', 'center_id', 'appointment_date', 'appointment_time', 'status', 'dose_number')
            ->orderByDesc('appointment_date')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $appointments,
            'total'   => $appointments->count(),
        ]);
    }
}
