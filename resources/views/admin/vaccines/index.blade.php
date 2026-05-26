@extends('layouts.app')

@section('title', 'Manage Vaccines')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-fade-in-up">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold bg-sky-100 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Admin</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Vaccines</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Manage all vaccines in the system.</p>
        </div>
        <a href="{{ route('admin.vaccines.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm hover:shadow-md hover:shadow-blue-500/20 active:scale-98 hover:scale-[1.02]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add Vaccine
        </a>
    </div>

    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-all duration-200 animate-fade-in-up delay-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">#</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Vaccine</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Manufacturer</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Doses</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Stock</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Price</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Status</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Bookings</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider px-6 py-3.5">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($vaccines as $vaccine)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/20 transition-all duration-150 group">
                            <td class="px-6 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $vaccine->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-sky-400 to-blue-600 rounded-lg flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-200">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-slate-800 dark:text-white">{{ $vaccine->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-medium">{{ $vaccine->manufacturer }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 text-xs font-bold px-2 py-1 rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $vaccine->doses_required }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="@if($vaccine->stock < 20) text-rose-600 dark:text-rose-400 font-black @else text-slate-700 dark:text-slate-300 font-semibold @endif">
                                    @if($vaccine->stock < 20)
                                        <span class="inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            {{ $vaccine->stock }}
                                        </span>
                                    @else
                                        {{ $vaccine->stock }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-semibold">
                                @if($vaccine->price > 0)
                                    ₹{{ number_format($vaccine->price, 2) }}
                                @else
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Free</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full capitalize
                                    {{ $vaccine->status === 'available'
                                        ? 'bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400'
                                        : 'bg-rose-100 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $vaccine->status === 'available' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $vaccine->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-700 dark:text-slate-300 font-semibold">{{ $vaccine->appointments_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.vaccines.edit', $vaccine) }}"
                                       class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold bg-blue-50 dark:bg-blue-950/30 hover:bg-blue-100 dark:hover:bg-blue-950/50 px-2.5 py-1.5 rounded-lg transition-all">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.vaccines.destroy', $vaccine) }}" onsubmit="return confirm('Delete this vaccine?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 text-xs text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 font-semibold bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-950/50 px-2.5 py-1.5 rounded-lg transition-all cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-400 dark:text-slate-500 font-medium">No vaccines found</p>
                                    <a href="{{ route('admin.vaccines.create') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline font-semibold">Add the first vaccine →</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vaccines->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
                {{ $vaccines->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
