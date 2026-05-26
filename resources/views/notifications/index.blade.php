@extends('layouts.app')

@section('title', 'Notifications Inbox')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">
    
    {{-- Header --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Alerts Inbox</h1>
        <p class="text-slate-505 dark:text-slate-400 mt-1 text-sm">Review your secure vaccine allocation alerts and milestones.</p>
    </div>

    {{-- Notifications Stack --}}
    <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-sm overflow-hidden animate-fade-in-up delay-100 transition-colors">
        @if($notifications->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="w-16 h-16 bg-slate-105 dark:bg-slate-800/50 rounded-full flex items-center justify-center mb-4 transition-colors">
                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-700 dark:text-slate-205 text-base mb-1">Inbox is empty</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium text-xs">You have no notification logs at the moment.</p>
            </div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-slate-800/40">
                @foreach($notifications as $notif)
                    @php
                        $notifColors = [
                            'success' => 'border-emerald-500/35 bg-emerald-500/5',
                            'warning' => 'border-rose-500/35 bg-rose-500/5',
                            'info'    => 'border-blue-500/35 bg-blue-500/5'
                        ];
                        $accentColors = [
                            'success' => 'bg-emerald-500',
                            'warning' => 'bg-rose-500',
                            'info'    => 'bg-blue-500'
                        ];
                        $isUnread = is_null($notif->read_at);
                    @endphp
                    <div class="p-5 flex items-start gap-4 transition-colors duration-150 {{ $isUnread ? 'bg-slate-50/40 dark:bg-slate-800/10' : '' }}">
                        {{-- Color Indicator Line --}}
                        <div class="w-1 h-10 rounded shrink-0 {{ $accentColors[$notif->type] ?? 'bg-slate-400' }}"></div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-3 mb-1">
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm leading-tight">
                                    {{ $notif->title }}
                                    @if($isUnread)
                                        <span class="inline-block w-1.5 h-1.5 bg-rose-550 rounded-full ml-1" title="Unread"></span>
                                    @endif
                                </h3>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-350 leading-relaxed font-semibold">{{ $notif->message }}</p>
                            
                            @if($isUnread)
                                <div class="mt-3 flex gap-2">
                                    <form method="POST" action="{{ route('user.notifications.read', $notif) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-650 dark:text-slate-300 font-bold text-[10px] uppercase tracking-wider rounded-lg border border-slate-200/60 dark:border-slate-800 transition-all cursor-pointer">
                                            Mark Read
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 transition-colors">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
