@extends('layouts.app')

@section('title', 'Admin Appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header --}}
    <div class="mb-8 animate-fade-in-up">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Admin Panel</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">All Appointments</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">View, track, and manage all patient vaccination appointments.</p>
    </div>

    {{-- Stats Row --}}
    @php
        $statsConfig = [
            'total'     => ['label' => 'Total', 'value' => $stats['total'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg' => 'bg-slate-100 dark:bg-slate-800/60', 'text' => 'text-slate-700 dark:text-slate-300', 'iconBg' => 'bg-slate-200 dark:bg-slate-700', 'iconColor' => 'text-slate-600 dark:text-slate-300'],
            'pending'   => ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-amber-50 dark:bg-amber-950/20', 'text' => 'text-amber-700 dark:text-amber-400', 'iconBg' => 'bg-amber-100 dark:bg-amber-900/40', 'iconColor' => 'text-amber-600 dark:text-amber-400'],
            'confirmed' => ['label' => 'Confirmed', 'value' => $stats['confirmed'], 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg' => 'bg-blue-50 dark:bg-blue-950/20', 'text' => 'text-blue-700 dark:text-blue-400', 'iconBg' => 'bg-blue-100 dark:bg-blue-900/40', 'iconColor' => 'text-blue-600 dark:text-blue-400'],
            'completed' => ['label' => 'Completed', 'value' => $stats['completed'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-50 dark:bg-emerald-950/20', 'text' => 'text-emerald-700 dark:text-emerald-400', 'iconBg' => 'bg-emerald-100 dark:bg-emerald-900/40', 'iconColor' => 'text-emerald-600 dark:text-emerald-400'],
            'cancelled' => ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-rose-50 dark:bg-rose-950/20', 'text' => 'text-rose-700 dark:text-rose-400', 'iconBg' => 'bg-rose-100 dark:bg-rose-900/40', 'iconColor' => 'text-rose-600 dark:text-rose-400'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6 animate-fade-in-up delay-100">
        @foreach($statsConfig as $s)
            <div class="{{ $s['bg'] }} rounded-xl p-4 text-center transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm border border-transparent dark:border-slate-800/40">
                <div class="w-10 h-10 {{ $s['iconBg'] }} rounded-xl flex items-center justify-center mx-auto mb-2.5">
                    <svg class="w-5 h-5 {{ $s['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-2xl font-extrabold {{ $s['text'] }} leading-none">{{ $s['value'] }}</p>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-500 mt-1.5 tracking-wide uppercase">{{ $s['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.appointments.index') }}"
          class="flex flex-wrap gap-3 mb-6 animate-fade-in-up delay-200">
        <div class="relative flex-1 min-w-48">
            <span class="absolute left-3.5 top-3 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name or email..."
                class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
        </div>

        <select name="status" class="px-4 py-2.5 bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
            <option value="">All Statuses</option>
            @foreach(['pending','confirmed','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm active:scale-98 cursor-pointer hover:shadow-md">
            Filter
        </button>

        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.appointments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all active:scale-98">
                Clear
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-200 animate-fade-in-up delay-300">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">#</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Patient</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Vaccine</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Center</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Date & Time</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Dose</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Status</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-5 py-3.5">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($appointments as $appt)
                        @php
                            $pillColors = [
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
                            $pillClass = $pillColors[$appt->status] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300';
                            $dotClass  = $dotColors[$appt->status] ?? 'bg-slate-500';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                            <td class="px-5 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs">#{{ $appt->id }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                        {{ strtoupper(substr($appt->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 dark:text-white leading-tight">{{ $appt->user->name }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 leading-none">{{ $appt->user->email }}</p>
                                        @if($appt->user->aadhar_verified)
                                            <span class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-600 dark:text-emerald-450 mt-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1-1 0 00-1.414-1.414L9 10.586 7.707 9.293a1-1 0 00-1.414 1.414l2 2a1-1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                Aadhaar: {{ $appt->user->aadhar_number ? substr($appt->user->aadhar_number, 0, 4) . ' ' . substr($appt->user->aadhar_number, 4, 4) . ' ' . substr($appt->user->aadhar_number, 8, 4) : 'Verified' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[9px] font-black text-rose-600 dark:text-rose-400 mt-1 uppercase tracking-wider">
                                                ⚠️ Exemption Pass User
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-700 dark:text-slate-300 font-semibold">{{ $appt->vaccine->name }}</td>
                            <td class="px-5 py-4">
                                <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $appt->center->name }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $appt->center->city }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-700 dark:text-slate-300">{{ $appt->appointment_date->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800/60 px-2 py-1 rounded-lg">
                                    Dose {{ $appt->dose_number }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 {{ $pillClass }} text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ $appt->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.appointments.update', $appt) }}" class="flex flex-col gap-2">
                                    @csrf @method('PUT')
                                    <div class="flex items-center gap-1.5">
                                        <select name="status"
                                                class="text-xs px-2 py-1.5 bg-slate-50 dark:bg-[#0b0f19] text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30 transition-colors">
                                            @foreach(['pending','confirmed','completed','cancelled'] as $s)
                                                <option value="{{ $s }}" {{ $appt->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                                class="text-xs px-3 py-1.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-lg hover:from-sky-600 hover:to-blue-700 font-bold transition-all cursor-pointer active:scale-95 shadow-sm hover:shadow-md">
                                            Save
                                        </button>
                                    </div>
                                    @if(!$appt->user->aadhar_verified)
                                        <div class="flex flex-col gap-1 mt-0.5 p-2 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100 dark:border-rose-900/20 rounded-xl max-w-[160px]">
                                            <span class="text-[9px] font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wider block">Verify Aadhaar details</span>
                                            <input type="text" name="aadhar_number" placeholder="12-digit Aadhaar" maxlength="12"
                                                   class="text-[10px] px-2 py-1 bg-white dark:bg-[#0b0f19] text-slate-800 dark:text-white border border-slate-200 dark:border-slate-700 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500/30 w-full" />
                                            <label class="flex items-center gap-1 text-[9px] font-bold text-slate-600 dark:text-slate-400 cursor-pointer mt-0.5">
                                                <input type="checkbox" name="aadhar_verified" value="1" class="rounded border-slate-350 text-blue-600 focus:ring-blue-500 w-3 h-3" />
                                                Mark Verified
                                            </label>
                                        </div>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 dark:text-slate-500 font-medium">No appointments found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
