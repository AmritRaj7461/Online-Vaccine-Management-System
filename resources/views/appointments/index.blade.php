@extends('layouts.app')

@section('title', 'My Appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">My Appointments</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage and track all your vaccination appointments.</p>
        </div>
        <a href="{{ route('user.appointments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm active:scale-98">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Book New
        </a>
    </div>

    {{-- Table / Card Wrapper --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        @if($appointments->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 transition-colors">
                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 dark:text-slate-400 font-medium">No appointments booked yet</p>
                <a href="{{ route('user.appointments.create') }}" class="mt-4 text-sm text-blue-600 dark:text-blue-400 font-semibold hover:underline">Book your first appointment →</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">#</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Vaccine</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Center</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Date & Time</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Dose</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Status</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/40">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs">#{{ $appointment->id }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800 dark:text-white">{{ $appointment->vaccine->name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $appointment->vaccine->manufacturer }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $appointment->center->name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $appointment->center->city }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $appointment->appointment_date->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-semibold">Dose {{ $appointment->dose_number }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'pending'   => 'bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400',
                                            'confirmed' => 'bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400',
                                            'completed' => 'bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400',
                                            'cancelled' => 'bg-rose-100 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400',
                                        ];
                                    @endphp
                                    <span class="{{ $statusClasses[$appointment->status] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }} text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!in_array($appointment->status, ['completed', 'cancelled']))
                                        <form method="POST" action="{{ route('user.appointments.destroy', $appointment) }}"
                                              onsubmit="return confirm('Cancel this appointment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-355 font-bold hover:underline cursor-pointer">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
