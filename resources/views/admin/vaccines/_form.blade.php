{{-- Shared form fields for vaccine create/edit --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Vaccine Name <span class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $vaccine->name ?? '') }}" required
            class="w-full px-4 py-3 border @error('name') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="manufacturer" class="block text-sm font-semibold text-slate-700 mb-1.5">Manufacturer <span class="text-rose-500">*</span></label>
        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $vaccine->manufacturer ?? '') }}" required
            class="w-full px-4 py-3 border @error('manufacturer') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        @error('manufacturer')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Description <span class="text-rose-500">*</span></label>
    <textarea name="description" id="description" rows="3" required
        class="w-full px-4 py-3 border @error('description') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $vaccine->description ?? '') }}</textarea>
    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
    <div>
        <label for="doses_required" class="block text-sm font-semibold text-slate-700 mb-1.5">Doses Required</label>
        <input type="number" name="doses_required" id="doses_required" value="{{ old('doses_required', $vaccine->doses_required ?? 1) }}" min="1" max="10"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="days_between_doses" class="block text-sm font-semibold text-slate-700 mb-1.5">Days Between</label>
        <input type="number" name="days_between_doses" id="days_between_doses" value="{{ old('days_between_doses', $vaccine->days_between_doses ?? 0) }}" min="0"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1.5">Stock</label>
        <input type="number" name="stock" id="stock" value="{{ old('stock', $vaccine->stock ?? 100) }}" min="0"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="price" class="block text-sm font-semibold text-slate-700 mb-1.5">Price (₹)</label>
        <input type="number" name="price" id="price" value="{{ old('price', $vaccine->price ?? 0) }}" min="0" step="0.01"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
        <select name="status" id="status"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            <option value="available" {{ old('status', $vaccine->status ?? 'available') === 'available' ? 'selected' : '' }}>Available</option>
            <option value="unavailable" {{ old('status', $vaccine->status ?? '') === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
    </div>
    <div>
        <label for="image" class="block text-sm font-semibold text-slate-700 mb-1.5">Image <span class="text-slate-400 font-normal">(optional)</span></label>
        <input type="file" name="image" id="image" accept="image/*"
            class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-medium hover:file:bg-blue-100"/>
    </div>
</div>
