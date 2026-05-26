@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header with animation --}}
    <div class="mb-8 animate-fade-in-up">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Control Panel</span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">Admin Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Overview of the entire VacciCare vaccination system allocations.</p>
    </div>

    {{-- Stats Grid with animations --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $statCards = [
                ['label' => 'Total Users', 'value' => $stats['total_users'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'blue', 'accent' => 'dark:bg-blue-950/30 dark:text-blue-400', 'delay' => 'delay-100'],
                ['label' => 'Vaccines', 'value' => $stats['total_vaccines'], 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'color' => 'sky', 'accent' => 'dark:bg-sky-950/30 dark:text-sky-400', 'delay' => 'delay-200'],
                ['label' => 'Centers', 'value' => $stats['total_centers'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'violet', 'accent' => 'dark:bg-violet-950/30 dark:text-violet-400', 'delay' => 'delay-300'],
                ['label' => 'Appointments', 'value' => $stats['total_appointments'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'emerald', 'accent' => 'dark:bg-emerald-950/30 dark:text-emerald-400', 'delay' => 'delay-400'],
            ];
        @endphp

        @foreach($statCards as $card)
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-5 transition-all duration-300 hover:shadow-md animate-fade-in-up {{ $card['delay'] }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-{{ $card['color'] }}-100 {{ $card['accent'] }} rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-slate-800 dark:text-white leading-none">{{ $card['value'] }}</p>
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-400 mt-2 tracking-wide uppercase">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Appointment Status Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8 animate-fade-in-up delay-200">
        @foreach([
            ['label' => 'Pending', 'key' => 'pending', 'class' => 'amber', 'lightBg' => 'bg-amber-50 dark:bg-amber-950/15', 'lightBorder' => 'border-amber-100 dark:border-amber-900/20', 'textColor' => 'text-amber-700 dark:text-amber-400', 'subText' => 'text-amber-600 dark:text-amber-500'],
            ['label' => 'Confirmed', 'key' => 'confirmed', 'class' => 'blue', 'lightBg' => 'bg-blue-50 dark:bg-blue-950/15', 'lightBorder' => 'border-blue-100 dark:border-blue-900/20', 'textColor' => 'text-blue-700 dark:text-blue-400', 'subText' => 'text-blue-600 dark:text-blue-500'],
            ['label' => 'Completed', 'key' => 'completed', 'class' => 'emerald', 'lightBg' => 'bg-emerald-50 dark:bg-emerald-950/15', 'lightBorder' => 'border-emerald-100 dark:border-emerald-900/20', 'textColor' => 'text-emerald-700 dark:text-emerald-400', 'subText' => 'text-emerald-600 dark:text-emerald-500'],
            ['label' => 'Cancelled', 'key' => 'cancelled', 'class' => 'rose', 'lightBg' => 'bg-rose-50 dark:bg-rose-950/15', 'lightBorder' => 'border-rose-100 dark:border-rose-900/20', 'textColor' => 'text-rose-700 dark:text-rose-400', 'subText' => 'text-rose-600 dark:text-rose-500'],
        ] as $item)
            <div class="{{ $item['lightBg'] }} border {{ $item['lightBorder'] }} rounded-2xl p-4 text-center transition-all duration-200 hover:-translate-y-0.5 shadow-sm hover:shadow">
                <p class="text-2xl font-extrabold {{ $item['textColor'] }} leading-none">{{ $stats[$item['key']] }}</p>
                <p class="text-xs font-semibold {{ $item['subText'] }} mt-2 tracking-wide uppercase">{{ $item['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Interactive Visualization Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-in-up delay-200">
        {{-- Chart 1: Donut Breakdown --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 transition-all duration-300 hover:shadow-md">
            <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4">Appointment Status Allocation</h3>
            <div id="appointment-status-chart" class="w-full flex items-center justify-center min-h-[300px]"></div>
        </div>

        {{-- Chart 2: Popularity Bar Chart --}}
        <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 transition-all duration-300 hover:shadow-md">
            <h3 class="font-bold text-slate-800 dark:text-white text-base mb-4">Vaccine Booking Popularity</h3>
            <div id="vaccine-popularity-chart" class="w-full min-h-[300px]"></div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Appointments Table --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md animate-fade-in-up delay-300">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="font-bold text-slate-800 dark:text-white">Recent Booking Submissions</h2>
                <a href="{{ route('admin.appointments.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-medium">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                            <th class="text-left px-5 py-3 text-xs font-bold text-slate-400 dark:text-slate-405 uppercase tracking-wider">Patient</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-slate-400 dark:text-slate-405 uppercase tracking-wider">Vaccine</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-slate-400 dark:text-slate-405 uppercase tracking-wider">Date</th>
                            <th class="text-left px-5 py-3 text-xs font-bold text-slate-400 dark:text-slate-405 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40">
                        @forelse($recentAppointments as $appt)
                            @php
                                $colors = ['pending'=>'amber','confirmed'=>'blue','completed'=>'emerald','cancelled'=>'rose'];
                                $c = $colors[$appt->status] ?? 'slate';
                            @endphp
                            <tr class="hover:bg-slate-55 dark:hover:bg-slate-800/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-800 dark:text-white">{{ $appt->user->name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $appt->user->email }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">{{ $appt->vaccine->name }}</td>
                                <td class="px-5 py-3.5 text-slate-700 dark:text-slate-300 font-medium">{{ $appt->appointment_date->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="bg-{{ $c }}-100/80 dark:bg-{{ $c }}-950/30 text-{{ $c }}-700 dark:text-{{ $c }}-400 text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                        {{ $appt->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400 dark:text-slate-500">No appointments yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Vaccines + Quick Links --}}
        <div class="space-y-5 animate-fade-in-up delay-300">
            {{-- Top vaccines card --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="font-bold text-slate-800 dark:text-white mb-4">Most Booked Vaccines</h3>
                <div class="space-y-3.5">
                    @forelse($topVaccines as $index => $vaccine)
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 bg-blue-100 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-750 dark:text-slate-300 truncate leading-tight">{{ $vaccine->name }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $vaccine->appointments_count }} allocations</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-450 dark:text-slate-500">No stats gathered yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-5 transition-all duration-300 hover:shadow-md">
                <h3 class="font-bold text-slate-800 dark:text-white mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.vaccines.create') }}" class="flex items-center gap-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800/40 p-2.5 rounded-xl transition-colors font-semibold group">
                        <svg class="w-4 h-4 text-slate-405 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add New Vaccine
                    </a>
                    <a href="{{ route('admin.centers.create') }}" class="flex items-center gap-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800/40 p-2.5 rounded-xl transition-colors font-semibold group">
                        <svg class="w-4 h-4 text-slate-405 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add New Center
                    </a>
                    <a href="{{ route('admin.appointments.index') }}" class="flex items-center gap-2.5 text-sm text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800/40 p-2.5 rounded-xl transition-colors font-semibold group">
                        <svg class="w-4 h-4 text-slate-405 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Manage Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Dynamic color utility based on active theme
        const getChartColors = () => {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                text: isDark ? '#94a3b8' : '#64748b',
                grid: isDark ? '#1e293b' : '#f1f5f9',
                stroke: isDark ? '#151c2c' : '#ffffff',
                themeMode: isDark ? 'dark' : 'light'
            };
        };

        let colors = getChartColors();

        // 1. Appointment Status Donut Chart Configuration
        const statusOptions = {
            series: [
                {{ $stats['pending'] }},
                {{ $stats['confirmed'] }},
                {{ $stats['completed'] }},
                {{ $stats['cancelled'] }}
            ],
            chart: {
                type: 'donut',
                height: 300,
                background: 'transparent',
                foreColor: colors.text,
                parentHeightOffset: 0
            },
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
            colors: ['#f59e0b', '#3b82f6', '#10b981', '#f43f5e'],
            legend: {
                position: 'bottom',
                labels: { colors: colors.text }
            },
            stroke: {
                show: true,
                colors: [colors.stroke],
                width: 3
            },
            dataLabels: {
                enabled: true,
                dropShadow: { enabled: false }
            },
            theme: {
                mode: colors.themeMode
            },
            tooltip: {
                theme: colors.themeMode
            }
        };

        const statusChart = new ApexCharts(document.querySelector("#appointment-status-chart"), statusOptions);
        statusChart.render();

        // 2. Top Vaccines Horizontal Bar Chart Configuration
        const vaccineNames = [
            @foreach($topVaccines as $v)
                "{{ $v->name }}",
            @endforeach
        ];
        const vaccineBookings = [
            @foreach($topVaccines as $v)
                {{ $v->appointments_count }},
            @endforeach
        ];

        const vaccineOptions = {
            series: [{
                name: 'Allocated Bookings',
                data: vaccineBookings
            }],
            chart: {
                type: 'bar',
                height: 300,
                background: 'transparent',
                foreColor: colors.text,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    distributed: true,
                    barHeight: '50%'
                }
            },
            colors: ['#38bdf8', '#3b82f6', '#4f46e5', '#7c3aed', '#a855f7'],
            legend: { show: false },
            grid: {
                borderColor: colors.grid,
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            xaxis: {
                categories: vaccineNames,
                labels: { style: { colors: colors.text } }
            },
            yaxis: {
                labels: { style: { colors: colors.text } }
            },
            theme: {
                mode: colors.themeMode
            },
            tooltip: {
                theme: colors.themeMode
            }
        };

        const vaccineChart = new ApexCharts(document.querySelector("#vaccine-popularity-chart"), vaccineOptions);
        vaccineChart.render();
        
        // Listen to custom theme-changed event and redraw charts instantly
        window.addEventListener('theme-changed', function() {
            setTimeout(() => {
                const newColors = getChartColors();
                
                statusChart.updateOptions({
                    theme: { mode: newColors.themeMode },
                    legend: { labels: { colors: newColors.text } },
                    stroke: { colors: [newColors.stroke] },
                    tooltip: { theme: newColors.themeMode }
                });
                
                vaccineChart.updateOptions({
                    theme: { mode: newColors.themeMode },
                    grid: { borderColor: newColors.grid },
                    xaxis: { labels: { style: { colors: newColors.text } } },
                    yaxis: { labels: { style: { colors: newColors.text } } },
                    tooltip: { theme: newColors.themeMode }
                });
            }, 50);
        });
    });
</script>
@endpush
