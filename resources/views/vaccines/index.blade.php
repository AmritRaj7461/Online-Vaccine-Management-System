@extends('layouts.app')

@section('title', 'Available Vaccines')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200"
     x-data="{
         search: '',
         doseFilter: 'all',
         priceFilter: 'all',
         sortBy: 'name',
         vaccines: [
             @foreach($vaccines as $vaccine)
             {
                 id: {{ $vaccine->id }},
                 name: '{{ addslashes($vaccine->name) }}',
                 manufacturer: '{{ addslashes($vaccine->manufacturer) }}',
                 description: '{{ addslashes($vaccine->description) }}',
                 doses_required: {{ $vaccine->doses_required }},
                 stock: {{ $vaccine->stock }},
                 price: {{ $vaccine->price }},
                 status: '{{ $vaccine->status }}',
                 price_formatted: '{{ $vaccine->price > 0 ? '₹' . number_format($vaccine->price, 2) : 'Free of charge' }}',
                 book_url: '{{ route('user.appointments.create', ['vaccine_id' => $vaccine->id]) }}'
             },
             @endforeach
         ],
         get filteredVaccines() {
             let result = this.vaccines.filter(v => {
                 let matchesSearch = v.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                     v.manufacturer.toLowerCase().includes(this.search.toLowerCase()) ||
                                     v.description.toLowerCase().includes(this.search.toLowerCase());
                 
                 let matchesDoses = this.doseFilter === 'all' || 
                                    (this.doseFilter === '1' && v.doses_required === 1) || 
                                    (this.doseFilter === '2' && v.doses_required === 2) || 
                                    (this.doseFilter === 'multi' && v.doses_required > 2);
                 
                 let matchesPrice = this.priceFilter === 'all' || 
                                    (this.priceFilter === 'free' && v.price == 0) || 
                                    (this.priceFilter === 'paid' && v.price > 0);
                 
                 return matchesSearch && matchesDoses && matchesPrice;
             });

             if (this.sortBy === 'name') {
                 result.sort((a, b) => a.name.localeCompare(b.name));
             } else if (this.sortBy === 'price_asc') {
                 result.sort((a, b) => a.price - b.price);
             } else if (this.sortBy === 'price_desc') {
                 result.sort((a, b) => b.price - a.price);
             } else if (this.sortBy === 'stock_desc') {
                 result.sort((a, b) => b.stock - a.stock);
             }

             return result;
         }
     }">

    {{-- Header with entry animation --}}
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">Available Vaccines</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Browse active vaccine options and check dosage interval configurations.</p>
    </div>

    {{-- Search and Filter Controls Panel --}}
    <div class="bg-white dark:bg-[#151c2c] border border-slate-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm mb-8 animate-fade-in-up delay-100 transition-colors">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            
            {{-- Search Bar --}}
            <div class="relative w-full md:max-w-md">
                <span class="absolute left-4 top-3 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" 
                       x-model="search" 
                       placeholder="Search name, manufacturer, or details..." 
                       class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-405 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
            </div>

            {{-- Select filters --}}
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                
                {{-- Dose Filter --}}
                <div class="flex-1 sm:flex-initial">
                    <select x-model="doseFilter" 
                            class="w-full bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 py-2.5 px-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                        <option value="all">All Dosage Schemes</option>
                        <option value="1">Single Dose Only</option>
                        <option value="2">2 Doses Required</option>
                        <option value="multi">Multiple (>2) Doses</option>
                    </select>
                </div>

                {{-- Price Filter --}}
                <div class="flex-1 sm:flex-initial">
                    <select x-model="priceFilter" 
                            class="w-full bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 py-2.5 px-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                        <option value="all">All Pricing Types</option>
                        <option value="free">Free of Charge</option>
                        <option value="paid">Paid Vaccines</option>
                    </select>
                </div>

                {{-- Sort By --}}
                <div class="flex-1 sm:flex-initial">
                    <select x-model="sortBy" 
                            class="w-full bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-350 py-2.5 px-4 rounded-xl text-sm font-semibold focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer">
                        <option value="name">Sort by Name</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="stock_desc">Available Stock: High to Low</option>
                    </select>
                </div>

            </div>
        </div>
    </div>

    {{-- Vaccine Grid --}}
    <div x-show="filteredVaccines.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up delay-200">
        
        <template x-for="vaccine in filteredVaccines" :key="vaccine.id">
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden flex flex-col justify-between group">
                
                <div>
                    {{-- Card Header --}}
                    <div class="h-32 bg-gradient-to-br from-sky-400 to-blue-600 dark:from-sky-500 dark:to-blue-700 relative flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <div class="absolute top-3 right-3">
                            <span class="bg-emerald-500/90 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full select-none" 
                                  x-text="vaccine.status"></span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5">
                        <h3 class="font-bold text-slate-800 dark:text-white text-lg leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" x-text="vaccine.name"></h3>
                        <p class="text-xs text-slate-450 dark:text-slate-500 mt-1">by <span x-text="vaccine.manufacturer"></span></p>

                        <p class="text-sm text-slate-600 dark:text-slate-350 mt-3 line-clamp-2 leading-relaxed h-[42px]" x-text="vaccine.description"></p>

                        {{-- Details --}}
                        <div class="grid grid-cols-2 gap-3 mt-4 select-none">
                            <div class="bg-slate-50 dark:bg-[#0b0f19]/45 border border-slate-100 dark:border-slate-800 rounded-xl p-3 text-center">
                                <p class="text-[9px] text-slate-400 dark:text-slate-500 mb-0.5 font-bold uppercase tracking-wider">Required Doses</p>
                                <p class="font-extrabold text-slate-700 dark:text-slate-200" x-text="vaccine.doses_required"></p>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#0b0f19]/45 border border-slate-100 dark:border-slate-800 rounded-xl p-3 text-center">
                                <p class="text-[9px] text-slate-400 dark:text-slate-500 mb-0.5 font-bold uppercase tracking-wider">Stock Available</p>
                                <p class="font-extrabold" 
                                   :class="vaccine.stock < 20 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-700 dark:text-slate-200'"
                                   x-text="vaccine.stock"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="px-5 pb-5">
                    <div class="flex items-center justify-between border-t border-slate-50 dark:border-slate-800/40 pt-4">
                        <span class="text-sm font-bold text-slate-800 dark:text-slate-200" 
                              :class="vaccine.price === 0 ? 'text-emerald-600 dark:text-emerald-400 font-extrabold' : ''"
                              x-text="vaccine.price_formatted"></span>
                        
                        @if(auth()->user() && !auth()->user()->isAdmin())
                            <a :href="vaccine.book_url"
                               class="px-4 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm active:scale-97">
                                Book Slot
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </template>

    </div>

    {{-- Fallback for empty results --}}
    <div x-show="filteredVaccines.length === 0" 
         class="text-center py-16 bg-white dark:bg-[#151c2c] border border-slate-100 dark:border-slate-800 rounded-2xl p-8"
         style="display: none;">
        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800/45 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-slate-500 dark:text-slate-400 font-medium text-lg">No matching vaccines found</p>
        <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Try modifying your filters, spelling, or search criteria.</p>
    </div>

</div>
@endsection
