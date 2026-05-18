<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use Illuminate\Http\Request;

class AdminCenterController extends Controller
{
    public function index()
    {
        $centers = Center::withCount('appointments')->orderBy('name')->paginate(15);
        return view('admin.centers.index', compact('centers'));
    }

    public function create()
    {
        return view('admin.centers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'address'      => ['required', 'string', 'max:500'],
            'city'         => ['required', 'string', 'max:100'],
            'state'        => ['required', 'string', 'max:100'],
            'pincode'      => ['required', 'string', 'max:10'],
            'phone'        => ['required', 'string', 'max:15'],
            'email'        => ['nullable', 'email', 'max:255'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
            'status'       => ['required', 'in:active,inactive'],
        ]);

        Center::create($validated);

        return redirect()->route('admin.centers.index')
            ->with('success', 'Center "' . $validated['name'] . '" added successfully.');
    }

    public function show(Center $center)
    {
        $center->load('appointments.user');
        return view('admin.centers.show', compact('center'));
    }

    public function edit(Center $center)
    {
        return view('admin.centers.edit', compact('center'));
    }

    public function update(Request $request, Center $center)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'address'      => ['required', 'string', 'max:500'],
            'city'         => ['required', 'string', 'max:100'],
            'state'        => ['required', 'string', 'max:100'],
            'pincode'      => ['required', 'string', 'max:10'],
            'phone'        => ['required', 'string', 'max:15'],
            'email'        => ['nullable', 'email', 'max:255'],
            'opening_time' => ['required'],
            'closing_time' => ['required'],
            'status'       => ['required', 'in:active,inactive'],
        ]);

        $center->update($validated);

        return redirect()->route('admin.centers.index')
            ->with('success', 'Center updated successfully.');
    }

    public function destroy(Center $center)
    {
        $center->delete();

        return redirect()->route('admin.centers.index')
            ->with('success', 'Center deleted successfully.');
    }
}
