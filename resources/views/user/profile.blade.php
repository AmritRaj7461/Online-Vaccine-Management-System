@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-6px); }
    20%, 40%, 60%, 80% { transform: translateX(6px); }
}
.animate-shake {
    animation: shake 0.5s ease-in-out;
}
</style>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200"
     x-data="{
        name: '{{ old('name', $user->name) }}',
        aadhar: '{{ old('aadhar_number', $user->aadhar_number) }}',
        originalAadhar: '{{ $user->aadhar_number }}',
        isVerified: {{ $user->aadhar_verified ? 'true' : 'false' }},
        originalVerified: {{ $user->aadhar_verified ? 'true' : 'false' }},
        modalOpen: false,
        otpSent: false,
        otpValues: ['', '', '', '', '', ''],
        countdown: 60,
        countdownInterval: null,
        maskedMobile: '',
        verificationError: '',
        verificationSuccess: false,
        shakeModal: false,
        toastOpen: false,
        simulatedOtp: '',
        loading: false,

        formatAadhar(val) {
            let cleaned = val.replace(/\D/g, '').substring(0, 12);
            if (cleaned.length > 8) {
                return cleaned.substring(0, 4) + ' ' + cleaned.substring(4, 8) + ' ' + cleaned.substring(8, 12);
            } else if (cleaned.length > 4) {
                return cleaned.substring(0, 4) + ' ' + cleaned.substring(4, 8);
            }
            return cleaned;
        },

        init() {
            this.$watch('aadhar', value => {
                let cleaned = value.replace(/\D/g, '').substring(0, 12);
                this.aadhar = cleaned;
                
                // Live recalculation of verified status
                if (cleaned === this.originalAadhar && this.originalVerified) {
                    this.isVerified = true;
                } else {
                    this.isVerified = false;
                }
                this.verificationError = '';
            });
        },

        triggerVerification(isResend = false) {
            if (this.aadhar.length !== 12) {
                this.verificationError = 'Aadhaar must be exactly 12 digits.';
                return;
            }
            this.loading = true;
            this.verificationError = '';

            fetch('{{ route('user.aadhar.send-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ aadhar_number: this.aadhar })
            })
            .then(res => res.json())
            .then(data => {
                this.loading = false;
                if (!data.success) {
                    this.verificationError = data.message;
                    return;
                }
                // OTP triggered successfully
                this.maskedMobile = data.mobile;
                this.simulatedOtp = data.debug_otp || '';
                this.otpSent = true;
                this.otpValues = ['', '', '', '', '', ''];
                this.modalOpen = true;
                this.verificationError = '';

                // Start countdown timer
                this.startTimer();

                // Auto focus first OTP input block
                setTimeout(() => {
                    document.getElementById('otp-field-0')?.focus();
                }, 400);
            })
            .catch(err => {
                this.loading = false;
                this.verificationError = 'Network error. Please try again.';
            });
        },

        startTimer() {
            clearInterval(this.countdownInterval);
            this.countdown = 60;
            this.countdownInterval = setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    clearInterval(this.countdownInterval);
                }
            }, 1000);
        },

        closeModal() {
            this.modalOpen = false;
            clearInterval(this.countdownInterval);
        },

        handleOtpInput(e, index) {
            let val = e.target.value.replace(/\D/g, '');
            this.otpValues[index] = val;

            if (val && index < 5) {
                document.getElementById('otp-field-' + (index + 1))?.focus();
            }

            // Auto trigger verification if full
            if (this.otpValues.join('').length === 6) {
                this.verifyAadharOtp();
            }
        },

        handleOtpKeydown(e, index) {
            if (e.key === 'Backspace' && !this.otpValues[index] && index > 0) {
                this.otpValues[index - 1] = '';
                document.getElementById('otp-field-' + (index - 1))?.focus();
            }
        },

        handleOtpPaste(e) {
            e.preventDefault();
            let clipboardData = e.clipboardData || window.clipboardData;
            let pastedText = clipboardData.getData('Text').replace(/\D/g, '').substring(0, 6);
            
            for (let i = 0; i < pastedText.length; i++) {
                this.otpValues[i] = pastedText[i];
            }
            
            if (pastedText.length === 6) {
                this.verifyAadharOtp();
            }
        },

        verifyAadharOtp() {
            let otp = this.otpValues.join('');
            if (otp.length !== 6) {
                this.verificationError = 'Please enter a 6-digit OTP code.';
                return;
            }

            fetch('{{ route('user.aadhar.verify-otp') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    otp: otp,
                    aadhar_number: this.aadhar
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    this.verificationError = data.message;
                    this.shakeModal = true;
                    setTimeout(() => { this.shakeModal = false; }, 500);
                    return;
                }
                
                // Success! e-KYC Verified
                this.verificationSuccess = true;
                this.isVerified = true;
                this.originalAadhar = this.aadhar;
                this.originalVerified = true;
                
                // Hide toast & modal
                this.toastOpen = false;
                setTimeout(() => {
                    this.modalOpen = false;
                }, 800);
            })
            .catch(err => {
                this.verificationError = 'Network validation error. Please try again.';
            });
        }
     }">

    {{-- Breadcrumb/Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-semibold bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Patient Portal</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Profile Settings</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Manage your identity details, security, and vaccination documents.</p>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Profile Fields & Forms --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="font-semibold text-slate-800 dark:text-white">Personal Information</h2>
                <p class="text-xs text-slate-400 dark:text-slate-400 mt-0.5">Please ensure your Aadhar details match your official documents.</p>
            </div>

            @if($errors->any())
                <div class="p-6 bg-rose-500/10 border-b border-rose-500/20">
                    <ul class="text-xs text-rose-500 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full shrink-0"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('user.profile.update') }}" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Full Name --}}
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Full Name *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input type="text" name="name" id="name" required x-model="name"
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
                        </div>
                    </div>

                    {{-- Email (Read-Only) --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400 opacity-60">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="email" id="email" value="{{ $user->email }}" readonly
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-500 dark:text-slate-400 cursor-not-allowed text-sm focus:outline-none"/>
                        </div>
                    </div>

                    {{-- Phone Number --}}
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h2.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </span>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +91 9876543210"
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
                        </div>
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label for="dob" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Date of Birth</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input type="date" name="dob" id="dob" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}"
                                   class="w-full pl-12 pr-4 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"/>
                        </div>
                    </div>

                    {{-- Aadhar Number inline group --}}
                    <div class="sm:col-span-2">
                        <label for="aadhar_number" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">12-Digit Aadhar Number *</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <input type="text" name="aadhar_number" id="aadhar_number" required maxlength="12" x-model="aadhar" placeholder="Enter your 12-digit number"
                                   :readonly="isVerified"
                                   class="w-full pl-12 pr-36 py-2.5 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all text-sm"
                                   :class="isVerified ? 'bg-slate-100/50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 border-emerald-500/30' : ''"/>
                            
                            {{-- Inline Verification Button Overlay --}}
                            <div class="absolute right-2 flex items-center gap-1.5">
                                {{-- Verified ticked --}}
                                <template x-if="isVerified">
                                    <span class="inline-flex items-center gap-1 bg-emerald-500/15 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider select-none">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        Verified
                                    </span>
                                </template>

                                {{-- Verify Button --}}
                                <template x-if="!isVerified && aadhar.length === 12">
                                    <button type="button" @click="triggerVerification()"
                                            class="inline-flex items-center gap-1.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all active:scale-95 shadow-md shadow-blue-500/15 hover:shadow-blue-500/25 cursor-pointer">
                                        <svg class="w-3 h-3 animate-spin" x-show="loading" fill="none" viewBox="0 0 24 24" style="display: none;">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Verify e-KYC
                                    </button>
                                </template>
                            </div>
                        </div>
                        
                        {{-- Live client validation warning/error --}}
                        <template x-if="verificationError">
                            <p class="mt-2 text-xs text-rose-500 font-semibold" x-text="verificationError"></p>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800 pt-6">
                    <p class="text-xs text-slate-400 dark:text-slate-400">Unique Patient ID: <span class="font-mono text-slate-700 dark:text-slate-300">#PAT-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all shadow-sm active:scale-98 text-sm">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Interactive Digital Aadhar Card Widget --}}
        <div class="space-y-6">
            
            {{-- Digital Aadhar Preview --}}
            <div class="relative w-full h-[224px] max-w-sm mx-auto bg-gradient-to-tr from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 border-2 border-emerald-500/20 dark:border-emerald-500/10 rounded-2xl p-5 shadow-md flex flex-col justify-between overflow-hidden group select-none transition-colors duration-200">
                
                {{-- Decorative background emblem --}}
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-white/20 via-transparent to-transparent pointer-events-none"></div>
                <div class="absolute right-4 bottom-4 w-28 h-28 bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>

                {{-- Card Header --}}
                <div class="flex items-center justify-between border-b border-emerald-500/10 pb-2 z-10">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-extrabold text-sm shadow-sm shadow-emerald-200 dark:shadow-none shrink-0">印</div>
                        <div>
                            <p class="text-[9px] font-bold text-emerald-800 dark:text-emerald-400 leading-none">GOVERNMENT OF INDIA</p>
                            <p class="text-[10px] font-black text-slate-800 dark:text-slate-100 tracking-wide mt-0.5 leading-none">UNIQUE IDENTIFICATION AUTHORITY</p>
                        </div>
                    </div>
                    
                    {{-- Verified Tick Badge inside card --}}
                    <div class="flex items-center gap-1.5 bg-emerald-500/15 border border-emerald-500/25 px-2 py-0.5 rounded-full text-[9px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider"
                         x-show="isVerified"
                         x-transition.opacity>
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        e-KYC
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="flex gap-4 my-2 z-10">
                    {{-- User Photo Placeholder --}}
                    <div class="w-16 h-20 bg-slate-200/80 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-10 h-10 text-slate-400 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>

                    {{-- Patient details --}}
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <p class="text-[10px] uppercase font-bold text-slate-400 dark:text-slate-400 leading-none">Name / नाम</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-normal truncate" x-text="name ? name : 'Name'"></p>

                        <p class="text-[9px] uppercase font-bold text-slate-400 dark:text-slate-400 leading-none mt-2">DOB / जन्म तिथि</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mt-0.5 leading-none">{{ $user->dob ? $user->dob->format('d/m/Y') : '--/--/----' }}</p>

                        <p class="text-[9px] uppercase font-bold text-slate-400 dark:text-slate-400 leading-none mt-2">Gender / लिंग</p>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mt-0.5 leading-none">Male / पुरुष</p>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="flex items-center justify-between border-t border-emerald-500/10 pt-2 z-10">
                    <p class="text-base sm:text-lg font-black text-emerald-800 dark:text-emerald-400 font-mono tracking-widest leading-normal mx-auto font-extrabold"
                       x-text="formatAadhar(aadhar) ? formatAadhar(aadhar) : '0000 0000 0000'"></p>
                </div>
            </div>

            {{-- Metadata Cards --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 space-y-4 transition-colors duration-200">
                <h3 class="font-semibold text-slate-800 dark:text-white">Security & Timeline</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Account Role</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 capitalize">{{ $user->role }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Member Since</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 dark:text-slate-400">Verified Aadhar</span>
                        <span class="font-bold inline-flex items-center gap-1.5 text-xs uppercase tracking-wider"
                              :class="isVerified ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-500'">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span x-text="isVerified ? 'Active' : 'Pending Verification'"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- e-KYC Modal Overlay --}}
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-md transition-opacity duration-300"
         x-show="modalOpen"
         x-transition.opacity
         style="display: none;">
        
        <div class="relative max-w-md w-full bg-white dark:bg-[#151c2c] border border-slate-100 dark:border-slate-800 rounded-3xl p-6 shadow-2xl transition-all transform duration-300"
             x-show="modalOpen"
             x-transition.scale
             :class="{ 'animate-shake': shakeModal }"
             @click.away="closeModal()">
            
            {{-- Header --}}
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-gradient-to-tr from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-md shadow-emerald-500/20 mx-auto mb-4">印</div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Aadhaar e-KYC Verification</h3>
                <p class="text-xs text-slate-400 dark:text-slate-400 mt-1">Unique Identification Authority of India (UIDAI) Gateway</p>
            </div>

            {{-- Progress Steps --}}
            <div class="flex items-center justify-center gap-1.5 mb-6 text-[10px] font-black uppercase tracking-wider">
                <span class="text-emerald-500 flex items-center gap-1">Aadhaar <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></span>
                <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span>
                <span :class="otpSent ? 'text-emerald-500' : 'text-slate-400'">OTP Gate</span>
                <span class="w-8 h-[2px] bg-slate-200 dark:bg-slate-800"></span>
                <span :class="verificationSuccess ? 'text-emerald-500' : 'text-slate-400'">Verified</span>
            </div>

            {{-- Body contents --}}
            <div class="space-y-6">
                <div class="text-center">
                    <p class="text-sm font-semibold text-slate-600 dark:text-slate-350">Enter 6-Digit OTP</p>
                    <p class="text-xs text-slate-450 dark:text-slate-400 mt-1.5 leading-relaxed">A secure e-KYC verification code has been sent to your **registered email address**. Please check your inbox to retrieve the code.</p>
                    
                    {{-- Demo Mode helper when SMTP is blocked (e.g. Render Free Tier) --}}
                    <template x-if="simulatedOtp">
                        <div class="mt-4 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl text-center">
                            <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold leading-relaxed">
                                ⚠️ <strong>Demo Mode:</strong> Outbound SMTP is restricted on Render's free tier. Use this code to verify: 
                                <span class="font-mono text-sm font-bold bg-amber-500/20 px-2 py-0.5 rounded select-all" x-text="simulatedOtp"></span>
                            </p>
                        </div>
                    </template>
                </div>

                {{-- Segmented OTP Inputs --}}
                <div class="flex justify-center gap-2" id="otp-fields-parent">
                    <template x-for="(val, index) in otpValues" :key="index">
                        <input type="text"
                               maxlength="1"
                               x-model="otpValues[index]"
                               @input="handleOtpInput($event, index)"
                               @keydown="handleOtpKeydown($event, index)"
                               @paste="handleOtpPaste($event)"
                               class="w-12 h-14 text-center font-mono font-bold text-xl bg-slate-55 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-slate-800 dark:text-white focus:outline-none focus:ring-4 focus:ring-blue-500/15 focus:border-blue-500 transition-all uppercase"
                               :id="'otp-field-' + index"/>
                    </template>
                </div>

                {{-- Client errors --}}
                <template x-if="verificationError">
                    <p class="text-xs text-rose-500 text-center font-semibold" x-text="verificationError"></p>
                </template>

                {{-- Resend area --}}
                <div class="text-center text-xs">
                    <template x-if="countdown > 0">
                        <span class="text-slate-400">Resend OTP in <span class="font-mono font-semibold" x-text="'00:' + (countdown < 10 ? '0' + countdown : countdown)"></span></span>
                    </template>
                    <template x-if="countdown === 0">
                        <button type="button" @click="triggerVerification(true)"
                                class="text-blue-500 hover:text-blue-600 font-bold uppercase tracking-wider transition-colors cursor-pointer select-none">
                            Resend Aadhaar OTP
                        </button>
                    </template>
                </div>
            </div>

            {{-- Footer actions --}}
            <div class="flex gap-3 mt-8">
                <button type="button" @click="closeModal()"
                        class="flex-1 py-2.5 border border-slate-200 dark:border-slate-800 hover:bg-slate-55 dark:hover:bg-slate-800/40 text-slate-500 dark:text-slate-400 rounded-xl text-sm font-semibold transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="button" @click="verifyAadharOtp()"
                        class="flex-1 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-650 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-500/15 transition-all cursor-pointer">
                    Verify e-KYC
                </button>
            </div>
        </div>
    </div>



</div>
@endsection
