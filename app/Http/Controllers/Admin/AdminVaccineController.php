<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vaccine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVaccineController extends Controller
{
    public function index()
    {
        $vaccines = Vaccine::withCount('appointments')->orderBy('name')->paginate(15);
        return view('admin.vaccines.index', compact('vaccines'));
    }

    public function create()
    {
        return view('admin.vaccines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'manufacturer'       => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'doses_required'     => ['required', 'integer', 'min:1', 'max:10'],
            'days_between_doses' => ['required', 'integer', 'min:0'],
            'status'             => ['required', 'in:available,unavailable'],
            'stock'              => ['required', 'integer', 'min:0'],
            'price'              => ['required', 'numeric', 'min:0'],
            'image'              => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vaccines', 'public');
        }

        Vaccine::create($validated);

        return redirect()->route('admin.vaccines.index')
            ->with('success', 'Vaccine "' . $validated['name'] . '" added successfully.');
    }

    public function show(Vaccine $vaccine)
    {
        $vaccine->load('appointments.user');
        return view('admin.vaccines.show', compact('vaccine'));
    }

    public function edit(Vaccine $vaccine)
    {
        return view('admin.vaccines.edit', compact('vaccine'));
    }

    public function update(Request $request, Vaccine $vaccine)
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'manufacturer'       => ['required', 'string', 'max:255'],
            'description'        => ['required', 'string'],
            'doses_required'     => ['required', 'integer', 'min:1', 'max:10'],
            'days_between_doses' => ['required', 'integer', 'min:0'],
            'status'             => ['required', 'in:available,unavailable'],
            'stock'              => ['required', 'integer', 'min:0'],
            'price'              => ['required', 'numeric', 'min:0'],
            'image'              => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($vaccine->image) {
                Storage::disk('public')->delete($vaccine->image);
            }
            $validated['image'] = $request->file('image')->store('vaccines', 'public');
        }

        $vaccine->update($validated);

        return redirect()->route('admin.vaccines.index')
            ->with('success', 'Vaccine updated successfully.');
    }

    public function destroy(Vaccine $vaccine)
    {
        if ($vaccine->image) {
            Storage::disk('public')->delete($vaccine->image);
        }
        $vaccine->delete();

        return redirect()->route('admin.vaccines.index')
            ->with('success', 'Vaccine deleted successfully.');
    }
}
