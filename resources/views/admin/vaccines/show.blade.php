@extends('layouts.app')

@section('title', 'Vaccine: ' . $vaccine->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Back & Edit Row --}}
    <div class="mb-6 flex items-center justify-between animate-fade-in-up">
        <a href="{{ route('admin.vaccines.index') }}"
           class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Vaccines
        </a>
        <a href="{{ route('admin.vaccines.edit', $vaccine) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 text-sm font-semibold rounded-xl hover:bg-blue-100 dark:hover:bg-blue-950/50 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit Vaccine
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Vaccine Info Card --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden self-start animate-fade-in-up delay-100 transition-colors">
            {{-- Card Header Banner --}}
            <div class="h-24 bg-gradient-to-br from-sky-400 to-blue-600 dark:from-sky-500 dark:to-blue-700 flex items-center justify-center relative">
                <svg class="w-14 h-14 text-white/25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <div class="absolute top-3 right-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full {{ $vaccine->status === 'available' ? 'bg-emerald-500/90 text-white' : 'bg-rose-500/90 text-white' }}">
                        {{ $vaccine->status }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <h1 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">{{ $vaccine->name }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">by {{ $vaccine->manufacturer }}</p>

                <p class="text-sm text-slate-750 dark:text-slate-300 mt-4 leading-relaxed border-t border-slate-100 dark:border-slate-800 pt-4">{{ $vaccine->description }}</p>

                <div class="grid grid-cols-2 gap-3 mt-4">
                    @foreach([
                        ['label' => 'Doses Required', 'value' => $vaccine->doses_required],
                        ['label' => 'Days Between', 'value' => $vaccine->days_between_doses ?: 'None'],
                        ['label' => 'Stock', 'value' => $vaccine->stock . ' units', 'warn' => $vaccine->stock < 20],
                        ['label' => 'Price', 'value' => $vaccine->price > 0 ? '₹' . number_format($vaccine->price, 2) : 'Free'],
                    ] as $stat)
                    <div class="bg-slate-50 dark:bg-[#0b0f19]/50 border border-slate-100 dark:border-slate-800 rounded-xl p-3">
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider mb-1">{{ $stat['label'] }}</p>
                        <p class="font-bold text-sm {{ isset($stat['warn']) && $stat['warn'] ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200' }}">{{ $stat['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Booking History --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden animate-fade-in-up delay-200 transition-colors">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 dark:text-white">Booking & Appointment History</h2>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold px-2.5 py-1 rounded-full">
                    {{ $vaccine->appointments->count() }} total
                </span>
            </div>

            @if($vaccine->appointments->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">No bookings registered for this vaccine yet</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800">
                                <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3">Patient</th>
                                <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3">Date & Time</th>
                                <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3">Dose</th>
                                <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                            @foreach($vaccine->appointments as $appt)
                                @php
                                    $pillColors = [
                                        'pending'   => 'bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400',
                                        'confirmed' => 'bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400',
                                        'completed' => 'bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400',
                                        'cancelled' => 'bg-rose-100 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400',
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/20 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($appt->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800 dark:text-white leading-tight">{{ $appt->user->name }}</p>
                                                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $appt->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $appt->appointment_date->format('d M Y') }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/60 px-2 py-1 rounded-lg">
                                            Dose {{ $appt->dose_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full capitalize {{ $pillColors[$appt->status] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
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
