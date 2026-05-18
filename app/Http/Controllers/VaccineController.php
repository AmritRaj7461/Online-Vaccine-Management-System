<?php

namespace App\Http\Controllers;

use App\Models\Vaccine;
use Illuminate\Http\Request;

class VaccineController extends Controller
{
    /**
     * Display a listing of available vaccines (public / user view).
     */
    public function index()
    {
        $vaccines = Vaccine::where('status', 'available')->paginate(9);
        return view('vaccines.index', compact('vaccines'));
    }

    /**
     * Display the specified vaccine.
     */
    public function show(Vaccine $vaccine)
    {
        return view('vaccines.show', compact('vaccine'));
    }

    // The rest of the resource methods are handled by Admin controllers
    public function create() {}
    public function store(Request $request) {}
    public function edit(Vaccine $vaccine) {}
    public function update(Request $request, Vaccine $vaccine) {}
    public function destroy(Vaccine $vaccine) {}
}
