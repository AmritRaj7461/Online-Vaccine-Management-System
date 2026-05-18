{{-- Shared center form fields --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
    <div>
        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Center Name <span class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $center->name ?? '') }}" required
            class="w-full px-4 py-3 border @error('name') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone <span class="text-rose-500">*</span></label>
        <input type="tel" name="phone" id="phone" value="{{ old('phone', $center->phone ?? '') }}" required
            class="w-full px-4 py-3 border @error('phone') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mb-4">
    <label for="address" class="block text-sm font-semibold text-slate-700 mb-1.5">Address <span class="text-rose-500">*</span></label>
    <textarea name="address" id="address" rows="2" required
        class="w-full px-4 py-3 border @error('address') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('address', $center->address ?? '') }}</textarea>
    @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <label for="city" class="block text-sm font-semibold text-slate-700 mb-1.5">City <span class="text-rose-500">*</span></label>
        <input type="text" name="city" id="city" value="{{ old('city', $center->city ?? '') }}" required
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="state" class="block text-sm font-semibold text-slate-700 mb-1.5">State <span class="text-rose-500">*</span></label>
        <input type="text" name="state" id="state" value="{{ old('state', $center->state ?? '') }}" required
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="pincode" class="block text-sm font-semibold text-slate-700 mb-1.5">Pincode <span class="text-rose-500">*</span></label>
        <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $center->pincode ?? '') }}" required
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
    <div>
        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $center->email ?? '') }}"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="opening_time" class="block text-sm font-semibold text-slate-700 mb-1.5">Opening Time</label>
        <input type="time" name="opening_time" id="opening_time" value="{{ old('opening_time', isset($center) ? \Carbon\Carbon::parse($center->opening_time)->format('H:i') : '09:00') }}"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
    <div>
        <label for="closing_time" class="block text-sm font-semibold text-slate-700 mb-1.5">Closing Time</label>
        <input type="time" name="closing_time" id="closing_time" value="{{ old('closing_time', isset($center) ? \Carbon\Carbon::parse($center->closing_time)->format('H:i') : '17:00') }}"
            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>
</div>

<div class="mb-4">
    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
    <select name="status" id="status"
        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
        <option value="active" {{ old('status', $center->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $center->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
