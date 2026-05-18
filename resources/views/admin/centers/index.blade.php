@extends('layouts.app')

@section('title', 'Manage Centers')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Vaccination Centers</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage all vaccination centers inside the system.</p>
        </div>
        <a href="{{ route('admin.centers.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-sm active:scale-98">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Add Center
        </a>
    </div>

    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/40 border-b border-slate-100 dark:border-slate-800 transition-colors duration-200">
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">#</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Name</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Location</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Phone</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Hours</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Status</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Bookings</th>
                        <th class="text-left text-xs font-bold text-slate-400 dark:text-slate-400 uppercase tracking-wide px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/40">
                    @forelse($centers as $center)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 text-slate-400 dark:text-slate-500 font-mono text-xs">{{ $center->id }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800 dark:text-white">{{ $center->name }}</td>
                            <td class="px-6 py-4">
                                <p class="text-slate-700 dark:text-slate-300 font-medium">{{ $center->city }}, {{ $center->state }}</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $center->pincode }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-medium">{{ $center->phone }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs">
                                {{ \Carbon\Carbon::parse($center->opening_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($center->closing_time)->format('h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ $center->status === 'active' ? 'bg-emerald-100 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400' }} text-xs font-semibold px-2.5 py-1 rounded-full capitalize">
                                    {{ $center->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-medium">{{ $center->appointments_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.centers.edit', $center) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold">Edit</a>
                                    <form method="POST" action="{{ route('admin.centers.destroy', $center) }}" onsubmit="return confirm('Delete this center?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold cursor-pointer">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                No centers found. <a href="{{ route('admin.centers.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold">Add one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($centers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 transition-colors duration-200">
                {{ $centers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
