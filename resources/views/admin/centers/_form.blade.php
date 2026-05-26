{{-- Shared center form fields --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-5">
    <div>
        <label for="name" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Center Name <span class="text-rose-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $center->name ?? '') }}" placeholder="e.g. City Health Centre" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('name') border-rose-450 dark:border-rose-500/40 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
        @error('name')<p class="mt-1.5 text-xs text-rose-500 font-semibold" id="name-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="phone" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Phone <span class="text-rose-500">*</span></label>
        <input type="tel" name="phone" id="phone" value="{{ old('phone', $center->phone ?? '') }}" placeholder="e.g. +91 98765 43210" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('phone') border-rose-450 dark:border-rose-500/40 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
        @error('phone')<p class="mt-1.5 text-xs text-rose-500 font-semibold" id="phone-error">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mb-5">
    <label for="address" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Address <span class="text-rose-500">*</span></label>
    <textarea name="address" id="address" rows="2" placeholder="e.g. 12, MG Road, Near Civil Hospital" required
        class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border @error('address') border-rose-450 dark:border-rose-500/40 @else border-slate-200 dark:border-slate-800 @enderror rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all resize-none">{{ old('address', $center->address ?? '') }}</textarea>
    @error('address')<p class="mt-1.5 text-xs text-rose-500 font-semibold" id="address-error">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div>
        <label for="city" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">City <span class="text-rose-500">*</span></label>
        <input type="text" name="city" id="city" value="{{ old('city', $center->city ?? '') }}" placeholder="e.g. Raipur" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="state" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">State <span class="text-rose-500">*</span></label>
        <input type="text" name="state" id="state" value="{{ old('state', $center->state ?? '') }}" placeholder="e.g. Chhattisgarh" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="pincode" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Pincode <span class="text-rose-500">*</span></label>
        <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $center->pincode ?? '') }}" placeholder="e.g. 492001" required
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
    <div>
        <label for="email" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $center->email ?? '') }}" placeholder="e.g. info@center.com"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-600 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="opening_time" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Opening Time</label>
        <input type="time" name="opening_time" id="opening_time" value="{{ old('opening_time', isset($center) ? \Carbon\Carbon::parse($center->opening_time)->format('H:i') : '09:00') }}"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
    <div>
        <label for="closing_time" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Closing Time</label>
        <input type="time" name="closing_time" id="closing_time" value="{{ old('closing_time', isset($center) ? \Carbon\Carbon::parse($center->closing_time)->format('H:i') : '17:00') }}"
            class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all"/>
    </div>
</div>

<div class="mb-4">
    <label for="status" class="block text-sm font-semibold text-slate-800 dark:text-slate-200 mb-2">Status</label>
    <select name="status" id="status"
        class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all">
        <option class="dark:bg-[#151c2c]" value="active" {{ old('status', $center->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
        <option class="dark:bg-[#151c2c]" value="inactive" {{ old('status', $center->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
