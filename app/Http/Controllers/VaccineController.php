<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    
    public function index()
    {
        $vaccines = Vaccine::where('status', 'available')->get();
        return view('vaccines.index', compact('vaccines'));
    }

    
    public function show(Vaccine $vaccine)
    {
        return view('vaccines.show', compact('vaccine'));
    }

    
    public function create() {}
    public function store(Request $request) {}
    public function edit(Vaccine $vaccine) {}
    public function update(Request $request, Vaccine $vaccine) {}
    public function destroy(Vaccine $vaccine) {}
}
