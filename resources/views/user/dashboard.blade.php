@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Welcome Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Good {{ date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Here's an overview of your vaccination journey.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        {{-- Card 1: Total Bookings --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 flex items-center gap-4 transition-colors duration-200">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-950/40 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white leading-none">{{ $totalAppointments }}</p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-1.5 uppercase tracking-wider">Total Bookings</p>
            </div>
        </div>

        {{-- Card 2: Pending --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 flex items-center gap-4 transition-colors duration-200">
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-950/40 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white leading-none">{{ $pendingAppointments }}</p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-1.5 uppercase tracking-wider">Pending Approvals</p>
            </div>
        </div>

        {{-- Card 3: Completed --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 flex items-center gap-4 transition-colors duration-200">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/40 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-slate-800 dark:text-white leading-none">{{ $completedAppointments }}</p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-1.5 uppercase tracking-wider">Immunization Completed</p>
            </div>
        </div>
    </div>

    {{-- Layout Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Upcoming Appointments --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="font-semibold text-slate-800 dark:text-white">Upcoming Appointments</h2>
                <a href="{{ route('user.appointments.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">View all →</a>
            </div>

            @if($upcomingAppointments->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center px-6">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4 transition-colors">
                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">No upcoming appointments</p>
                    <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Book your first vaccination today</p>
                    <a href="{{ route('user.appointments.create') }}" class="mt-4 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                        Book Appointment
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($upcomingAppointments as $appointment)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                            <div class="w-10 h-10 bg-gradient-to-br from-sky-100 to-blue-100 dark:from-sky-950/20 dark:to-blue-950/20 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800 dark:text-white text-sm truncate">{{ $appointment->vaccine->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $appointment->center->name }} · Dose {{ $appointment->dose_number }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $appointment->appointment_date->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                            </div>
                            <span class="@if($appointment->status === 'confirmed') bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 @else bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 @endif text-xs font-semibold px-2.5 py-1 rounded-full capitalize shrink-0">
                                {{ $appointment->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick Actions & Sidebar Links --}}
        <div class="space-y-4">
            {{-- Quick book banner --}}
            <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-28 h-28 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-1 leading-none">Ready to vaccinate?</h3>
                    <p class="text-sky-100 text-xs mt-2 leading-relaxed">Book an appointment slot at an active vaccination center near your city.</p>
                    <a href="{{ route('user.appointments.create') }}"
                       class="block text-center bg-white text-blue-600 font-bold py-2.5 rounded-xl hover:bg-sky-50 transition-colors text-sm mt-5 shadow-sm active:scale-98">
                        + Book Now
                    </a>
                </div>
            </div>

            {{-- Quick Links Card --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-5 transition-colors duration-200">
                <h3 class="font-semibold text-slate-800 dark:text-white mb-4">Quick Links</h3>
                <div class="space-y-2">
                    <a href="{{ route('user.vaccines.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors text-sm text-slate-600 dark:text-slate-300 font-medium">
                        <div class="w-8 h-8 bg-sky-100 dark:bg-sky-950/40 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        Browse Vaccines
                    </a>
                    <a href="{{ route('user.appointments.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors text-sm text-slate-600 dark:text-slate-300 font-medium">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-950/40 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        My Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
