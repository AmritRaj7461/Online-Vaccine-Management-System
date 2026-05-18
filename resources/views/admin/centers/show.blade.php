@extends('layouts.app')

@section('title', 'Admin: ' . $center->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Back Link --}}
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.centers.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Centers List
        </a>
        <a href="{{ route('admin.centers.edit', $center) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-100 transition-colors">
            Edit Center Detail
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Side: Center Info --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden p-6 self-start">
            <div class="pb-4 mb-4 border-b border-slate-100">
                <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full capitalize mb-2 inline-block">
                    {{ $center->status }}
                </span>
                <h1 class="text-2xl font-bold text-slate-800 leading-tight">{{ $center->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $center->address }}</p>
                <p class="text-sm text-slate-500">{{ $center->city }}, {{ $center->state }} - {{ $center->pincode }}</p>
            </div>

            <div class="space-y-4">
                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Contact Info</h4>
                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">📞 {{ $center->phone }}</p>
                    @if($center->email)
                        <p class="text-sm text-slate-600 leading-relaxed">✉️ {{ $center->email }}</p>
                    @endif
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Operating Hours</h4>
                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                        🕒 {{ \Carbon\Carbon::parse($center->opening_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($center->closing_time)->format('h:i A') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Side: Booking List --}}
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-lg">Booking & Appointment History</h2>
                <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                    {{ $center->appointments->count() }} total bookings
                </span>
            </div>

            @if($center->appointments->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium">No bookings registered for this center yet</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase px-6 py-3">Patient</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase px-6 py-3">Vaccine</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase px-6 py-3">Date & Time</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase px-6 py-3">Dose</th>
                                <th class="text-left text-xs font-semibold text-slate-500 uppercase px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($center->appointments as $appt)
                                @php
                                    $colors = ['pending'=>'amber','confirmed'=>'blue','completed'=>'emerald','cancelled'=>'rose'];
                                    $c = $colors[$appt->status] ?? 'slate';
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-800">{{ $appt->user->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $appt->user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-semibold">{{ $appt->vaccine->name }}</td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-700">{{ $appt->appointment_date->format('d M Y') }}</p>
                                        <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700">Dose {{ $appt->dose_number }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-{{ $c }}-100 text-{{ $c }}-700 text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                            {{ $appt->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
