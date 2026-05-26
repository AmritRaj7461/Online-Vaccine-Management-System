{{-- Shared form fields for vaccine create/edit --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-5">
    <div>
        <label for="name" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Vaccine Name <span class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $vaccine->name ?? '') }}" placeholder="e.g. Covaxin" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('name') border-rose-450 dark:border-rose-500/40 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
        @error('name')<p class="mt-1.5 text-xs text-rose-500 font-semibold" id="name-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="manufacturer" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Manufacturer <span class="text-rose-500">*</span></label>
        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $vaccine->manufacturer ?? '') }}" placeholder="e.g. Bharat Biotech" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('manufacturer') border-rose-450 dark:border-rose-500/40 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
        @error('manufacturer')<p class="mt-1.5 text-xs text-rose-500 font-semibold" id="manufacturer-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mb-5">
    <label for="description" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Description <span class="text-rose-500">*</span></label>
    <textarea name="description" id="description" rows="3" placeholder="e.g. A whole-virion inactivated vaccine for COVID-19 with high efficacy and safety profile..." required
        class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('description') border-rose-450 dark:border-rose-500/40 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all resize-none">{{ old('description', $vaccine->description ?? '') }}</textarea>
    @error('description')<p class="mt-1.5 text-xs text-rose-500 font-semibold" id="description-error">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    <div>
        <label for="doses_required" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Doses Required</label>
        <input type="number" name="doses_required" id="doses_required" value="{{ old('doses_required', $vaccine->doses_required ?? 1) }}" min="1" max="10" placeholder="e.g. 2"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="days_between_doses" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Days Between</label>
        <input type="number" name="days_between_doses" id="days_between_doses" value="{{ old('days_between_doses', $vaccine->days_between_doses ?? 0) }}" min="0" placeholder="e.g. 28"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="stock" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Stock</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $vaccine->stock ?? 100) }}" min="0" placeholder="e.g. 500"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="price" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Price (₹)</label>
        <input type="number" name="price" id="price" value="{{ old('price', $vaccine->price ?? 0) }}" min="0" step="0.01" placeholder="e.g. 0 for free"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4">
    <div>
        <label for="status" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Status</label>
        <select name="status" id="status"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
            <option class="dark:bg-[#151c2c]" value="available" {{ old('status', $vaccine->status ?? 'available') === 'available' ? 'selected' : '' }}>Available</option>
            <option class="dark:bg-[#151c2c]" value="unavailable" {{ old('status', $vaccine->status ?? '') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
    </div>
    <div>
        <label for="image" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Image <span class="text-slate-400 dark:text-slate-500 font-normal">(optional)</span></label>
        <input type="file" name="image" id="image" accept="image/*"
            class="w-full px-3 py-2 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 dark:file:bg-blue-950/40 file:text-blue-700 dark:file:text-blue-400 file:font-semibold hover:file:bg-blue-100"/>
    </div>
</div>
