@extends('layouts.app')

@section('title', 'My Appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">My Appointments</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Manage and track all your vaccination appointments.</p>
        </div>
        <a href="{{ route('user.appointments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md hover:shadow-blue-500/20 active:scale-98 hover:scale-[1.02]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Book New
        </a>
    </div>

    {{-- Table / Card Wrapper --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-200 animate-fade-in-up delay-100">
        @if($appointments->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-5 transition-colors animate-float">
                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-700 dark:text-slate-200 text-lg mb-1">No appointments yet</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Book your first vaccination appointment to get started.</p>
                <a href="{{ route('user.appointments.create') }}"
                   class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm active:scale-98">
                    Book your first appointment →
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Vaccine</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Center</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Date & Time</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Dose</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                            <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach($appointments as $appointment)
                            @php
                                $statusClasses = [
                                    'pending'   => 'bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400',
                                    'confirmed' => 'bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400',
                                    'completed' => 'bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400',
                                    'cancelled' => 'bg-rose-100 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400',
                                ];
                                $dotColors = [
                                    'pending'   => 'bg-amber-500',
                                    'confirmed' => 'bg-blue-500',
                                    'completed' => 'bg-emerald-500',
                                    'cancelled' => 'bg-rose-500',
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                                <td class="px-6 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs">#{{ $appointment->id }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $appointment->vaccine->name }}</p>
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
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/60 px-2 py-1 rounded-lg">
                                        Dose {{ $appointment->dose_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 {{ $statusClasses[$appointment->status] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }} text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$appointment->status] ?? 'bg-slate-500' }}"></span>
                                        {{ $appointment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!in_array($appointment->status, ['completed', 'cancelled']))
                                        <form method="POST" action="{{ route('user.appointments.destroy', $appointment) }}"
                                              onsubmit="return confirm('Cancel this appointment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 font-semibold bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-950/50 px-2.5 py-1.5 rounded-lg transition-all cursor-pointer">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif($appointment->status === 'completed')
                                        <a href="{{ route('user.appointments.certificate', $appointment) }}" target="_blank"
                                           class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-950/50 px-2.5 py-1.5 rounded-lg transition-all cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Certificate 📄
                                        </a>
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
