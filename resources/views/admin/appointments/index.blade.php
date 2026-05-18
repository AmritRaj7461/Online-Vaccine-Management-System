@extends('layouts.app')

@section('title', 'Admin Appointments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">All Appointments</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">View, track, and manage all patient vaccination appointments.</p>
    </div>

    {{-- Stats Row --}}
    @php
        $statsConfig = [
            'total'     => ['label' => 'Total Bookings', 'value' => $stats['total'], 'bg' => 'bg-slate-50 dark:bg-slate-900/30', 'border' => 'border-slate-150 dark:border-slate-800/40', 'text' => 'text-slate-700 dark:text-slate-300', 'sub' => 'text-slate-500 dark:text-slate-400'],
            'pending'   => ['label' => 'Pending', 'value' => $stats['pending'], 'bg' => 'bg-amber-50 dark:bg-amber-950/20', 'border' => 'border-amber-100 dark:border-amber-900/30', 'text' => 'text-amber-700 dark:text-amber-400', 'sub' => 'text-amber-600 dark:text-amber-500'],
            'confirmed' => ['label' => 'Confirmed', 'value' => $stats['confirmed'], 'bg' => 'bg-blue-50 dark:bg-blue-950/20', 'border' => 'border-blue-100 dark:border-blue-900/30', 'text' => 'text-blue-700 dark:text-blue-400', 'sub' => 'text-blue-600 dark:text-blue-500'],
            'completed' => ['label' => 'Completed', 'value' => $stats['completed'], 'bg' => 'bg-emerald-50 dark:bg-emerald-950/20', 'border' => 'border-emerald-100 dark:border-emerald-900/30', 'text' => 'text-emerald-700 dark:text-emerald-400', 'sub' => 'text-emerald-600 dark:text-emerald-500'],
            'cancelled' => ['label' => 'Cancelled', 'value' => $stats['cancelled'], 'bg' => 'bg-rose-50 dark:bg-rose-950/20', 'border' => 'border-rose-100 dark:border-rose-900/30', 'text' => 'text-rose-700 dark:text-rose-400', 'sub' => 'text-rose-600 dark:text-rose-500'],
        ];
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        @foreach($statsConfig as $s)
            <div class="{{ $s['bg'] }} border {{ $s['border'] }} rounded-xl p-3 text-center transition-colors duration-200">
                <p class="text-2xl font-extrabold {{ $s['text'] }} leading-none">{{ $s['value'] }}</p>
                <p class="text-xs font-semibold {{ $s['sub'] }} mt-2 tracking-wide uppercase">{{ $s['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('admin.appointments.index') }}" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name or email..."
            class="flex-1 min-w-48 px-4 py-2.5 bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
        
        <select name="status" class="px-4 py-2.5 bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
            <option value="">All Statuses</option>
            @foreach(['pending','confirmed','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm active:scale-98 cursor-pointer">Filter</button>
        
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.appointments.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-transparent text-slate-700 dark:text-slate-300 text-sm font-semibold rounded-xl transition-all active:scale-98">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">#</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Patient</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Vaccine</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Center</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Date & Time</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Dose</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Status</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase px-5 py-3">Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/40">
                    @forelse($appointments as $appt)
                        @php
                            $pillColors = [
                                'pending' => 'bg-amber-100 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400',
                                'confirmed' => 'bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400',
                                'completed' => 'bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400',
                                'cancelled' => 'bg-rose-100 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400',
                            ];
                            $pillClass = $pillColors[$appt->status] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300';
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-5 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs">#{{ $appt->id }}</td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800 dark:text-white">{{ $appt->user->name }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $appt->user->email }}</p>
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
                            <td class="px-5 py-4 text-slate-700 dark:text-slate-300 font-mono text-xs">{{ $appt->dose_number }}</td>
                            <td class="px-5 py-4">
                                <span class="{{ $pillClass }} text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                    {{ $appt->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.appointments.update', $appt) }}" class="flex items-center gap-1.5">
                                    @csrf @method('PUT')
                                    <select name="status" class="text-xs px-2 py-1.5 bg-slate-50 dark:bg-[#0b0f19] text-slate-800 dark:text-white border border-slate-200 dark:border-slate-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                                        @foreach(['pending','confirmed','completed','cancelled'] as $s)
                                            <option value="{{ $s }}" {{ $appt->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition-colors cursor-pointer active:scale-95">Save</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                No appointments found.
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
