@extends('layouts.app')

@section('title', 'Admin QR Scanner')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-colors duration-200">
    
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Admin Panel</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">QR Code Exemption Scanner</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Scan patient vaccine entry passes using your camera to verify and update their Aadhaar records.</p>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="sm:self-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-750 dark:text-slate-200 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shrink-0">
            View Bookings
        </a>
    </div>

    {{-- Main Scanner Section --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6" x-data="scannerApp()">
        
        {{-- Left: Scanner Camera Card --}}
        <div class="md:col-span-3 bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-100 dark:border-slate-800 shadow-lg p-6 space-y-4">
            <div class="flex justify-between items-center pb-3 border-b border-slate-150 dark:border-slate-800">
                <h3 class="font-bold text-slate-850 dark:text-white text-base">Camera Viewfinder</h3>
                <span class="w-2.5 h-2.5 rounded-full" :class="scanning ? 'bg-emerald-500 animate-ping' : 'bg-slate-305'"></span>
            </div>

            {{-- Selector for cameras --}}
            <div class="space-y-1.5" x-show="cameras.length > 1">
                <label for="camera-select" class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Choose Camera</label>
                <select id="camera-select" x-model="selectedCameraId" @change="restartScanner()"
                        class="w-full text-xs px-2.5 py-2 bg-slate-50 dark:bg-[#0b0f19] text-slate-850 dark:text-white border border-slate-200 dark:border-slate-800 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    <template x-for="cam in cameras" :key="cam.id">
                        <option :value="cam.id" x-text="cam.label"></option>
                    </template>
                </select>
            </div>

            {{-- Viewfinder Container --}}
            <div class="relative w-full aspect-square bg-[#0b0f19] rounded-2xl overflow-hidden flex flex-col items-center justify-center border border-slate-150 dark:border-slate-800">
                
                {{-- Laser line animation --}}
                <div class="absolute inset-x-0 h-0.5 bg-emerald-450 opacity-70 shadow-[0_0_8px_#34d399] z-20 pointer-events-none"
                     x-show="scanning"
                     :style="{ animation: 'laser 2s infinite linear' }"></div>

                {{-- QR scanner target area --}}
                <div id="reader" class="w-full h-full object-cover"></div>

                {{-- Overlay when not scanning --}}
                <div class="absolute inset-0 bg-[#0b0f19]/90 z-10 flex flex-col items-center justify-center p-6 text-center" x-show="!scanning">
                    <svg class="w-12 h-12 text-slate-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-16v3m0 0h.01M12 12h.01M16 20h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-slate-400 leading-relaxed">Camera is paused or unavailable.</p>
                    <button type="button" @click="startScanner()" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md cursor-pointer transition-all active:scale-95">
                        Start Scanner
                    </button>
                </div>
            </div>

            {{-- Fallback Search Panel --}}
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-2">Manual Verification Fallback</span>
                <div class="flex gap-2">
                    <input type="text" x-model="searchQuery" placeholder="Enter patient email or reference ID"
                           class="flex-1 px-3 py-2 bg-slate-50 dark:bg-[#0b0f19] border border-slate-205 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all" />
                    <button type="button" @click="fetchPatientByName()"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl cursor-pointer">
                        Search
                    </button>
                </div>
            </div>
        </div>

        {{-- Right: Patient Status & Aadhaar Update Form --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Patient Card --}}
            <div class="bg-white dark:bg-[#151c2c] rounded-3xl border border-slate-105 dark:border-slate-800/80 shadow-lg p-6 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-blue-500/5 rounded-full pointer-events-none"></div>

                <h3 class="font-bold text-slate-850 dark:text-white text-base pb-3 border-b border-slate-150 dark:border-slate-800 mb-4">Patient Information</h3>

                {{-- Initial Waiting State --}}
                <div class="flex flex-col items-center justify-center py-12 text-center" x-show="!patient">
                    <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900/50 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                    </div>
                    <p class="text-xs text-slate-400 max-w-[200px] leading-relaxed">No patient pass scanned yet. Align a QR code within the viewfinder frame.</p>
                </div>

                {{-- Patient Information Panel --}}
                <div class="space-y-5" x-show="patient" x-transition>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-extrabold text-sm shadow-sm" x-text="patient ? patient.name.charAt(0).toUpperCase() : ''"></div>
                        <div>
                            <h4 class="font-extrabold text-slate-800 dark:text-white leading-tight" x-text="patient ? patient.name : ''"></h4>
                            <p class="text-xs text-slate-455 mt-0.5" x-text="patient ? patient.email : ''"></p>
                        </div>
                    </div>

                    <div class="bg-slate-50/50 dark:bg-slate-900/30 border border-transparent dark:border-slate-800/40 p-4 rounded-2xl text-xs space-y-2.5">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Reference ID</span>
                            <span class="font-mono font-bold text-slate-800 dark:text-slate-200" x-text="patient ? patient.reference_id : ''"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Date of Birth</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="patient ? patient.dob : ''"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Status</span>
                            <template x-if="patient && patient.aadhar_verified">
                                <span class="bg-emerald-100/70 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-500/10 px-2 py-0.5 rounded-full text-[9px] font-black uppercase">Verified</span>
                            </template>
                            <template x-if="patient && !patient.aadhar_verified">
                                <span class="bg-rose-100/70 dark:bg-rose-950/40 text-rose-700 dark:text-rose-450 border border-rose-500/10 px-2 py-0.5 rounded-full text-[9px] font-black uppercase">Pending KYC</span>
                            </template>
                        </div>
                    </div>

                    {{-- Form to update Aadhaar --}}
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
                        <template x-if="patient && patient.aadhar_verified">
                            <div class="bg-emerald-50/50 dark:bg-emerald-950/15 border border-emerald-100/60 dark:border-emerald-900/20 p-4 rounded-2xl text-center space-y-1.5">
                                <svg class="w-8 h-8 text-emerald-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <p class="text-xs font-extrabold text-emerald-805 dark:text-emerald-400">Aadhaar Already Verified</p>
                                <p class="text-[10px] text-slate-450 leading-relaxed" x-text="'Aadhaar Number: ' + formatAadharString(patient.aadhar_number)"></p>
                            </div>
                        </template>

                        <template x-if="patient && !patient.aadhar_verified">
                            <form @submit.prevent="submitAadharUpdate()" class="space-y-3">
                                <div>
                                    <label for="aadhar_number" class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Enter 12-Digit Aadhaar *</label>
                                    <input type="text" id="aadhar_number" x-model="aadharInput" required maxlength="12" placeholder="XXXX XXXX XXXX"
                                           class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0b0f19] border border-slate-200 dark:border-slate-800 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500/50 transition-all font-semibold" />
                                </div>
                                <button type="submit" :disabled="submitting"
                                        class="w-full py-3 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-xl hover:from-sky-600 hover:to-blue-700 transition-all shadow-md text-xs uppercase tracking-wider disabled:opacity-50 cursor-pointer flex items-center justify-center gap-1.5">
                                    <span x-show="submitting" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                    <span>Verify & Update Aadhaar</span>
                                </button>
                            </form>
                        </template>
                    </div>

                    {{-- Scan Next Button --}}
                    <div class="pt-2">
                        <button type="button" @click="resetScan()" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all cursor-pointer text-center">
                            Scan Next Pass
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes laser {
        0%, 100% { top: 0%; }
        50% { top: 100%; }
    }
</style>

{{-- html5-qrcode library --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    function scannerApp() {
        return {
            scanning: false,
            cameras: [],
            selectedCameraId: '',
            html5QrScanner: null,
            patient: null,
            searchQuery: '',
            aadharInput: '',
            submitting: false,

            init() {
                // Fetch list of cameras
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length > 0) {
                        this.cameras = devices;
                        this.selectedCameraId = devices[0].id;
                        this.startScanner();
                    } else {
                        console.warn("No cameras found.");
                    }
                }).catch(err => {
                    console.error("Error listing cameras: ", err);
                });
            },

            startScanner() {
                if (this.html5QrScanner) {
                    this.html5QrScanner.clear();
                }

                this.html5QrScanner = new Html5Qrcode("reader");
                this.scanning = true;

                this.html5QrScanner.start(
                    this.selectedCameraId,
                    {
                        fps: 10,
                        qrbox: { width: 220, height: 220 }
                    },
                    (decodedText, decodedResult) => {
                        this.playSuccessBeep();
                        this.stopScanner();
                        this.handleDecodedText(decodedText);
                    },
                    (errorMessage) => {
                        // Verbose scan failures, ignore
                    }
                ).catch(err => {
                    console.error("Error starting scanner: ", err);
                    this.scanning = false;
                });
            },

            stopScanner() {
                if (this.html5QrScanner && this.scanning) {
                    this.html5QrScanner.stop().then(() => {
                        this.scanning = false;
                    }).catch(err => {
                        console.error("Error stopping scanner: ", err);
                    });
                }
            },

            restartScanner() {
                this.stopScanner();
                setTimeout(() => {
                    this.startScanner();
                }, 300);
            },

            handleDecodedText(text) {
                // The pass QR code contains route('verify.exemption-pass', $user)
                // Format: http://domain/verify-pass/{user_id}
                try {
                    const parts = text.split('/');
                    const userId = parts[parts.length - 1];
                    if (userId && !isNaN(userId)) {
                        this.fetchPatientDetails(userId);
                    } else {
                        alert("Invalid Exemption Pass QR Code format.");
                        this.startScanner();
                    }
                } catch (e) {
                    alert("Error parsing scanned pass code.");
                    this.startScanner();
                }
            },

            fetchPatientDetails(userId) {
                const url = `/admin/patient-info/${userId}`;
                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.patient = data;
                            this.aadharInput = data.aadhar_number || '';
                        } else {
                            alert("Patient profile not found.");
                            this.startScanner();
                        }
                    })
                    .catch(err => {
                        console.error("Error fetching patient details: ", err);
                        alert("Failed to load patient information.");
                        this.startScanner();
                    });
            },

            fetchPatientByName() {
                if (!this.searchQuery) return;
                
                // Since user email or reference ID is entered, we can search by sending a fetch
                // We fetch from admin appointments endpoint or a helper search
                const query = encodeURIComponent(this.searchQuery);
                fetch(`/admin/appointments?search=${query}&status=`)
                    .then(r => r.text())
                    .then(html => {
                        // Extract patient ID by parsing first matching record or similar
                        // For a robust implementation, we can run a quick direct look up
                        // Let's call our profile getPatientInfo route using the search value:
                        fetch(`/admin/patient-info/${this.searchQuery}`)
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    this.patient = data;
                                    this.aadharInput = data.aadhar_number || '';
                                    this.stopScanner();
                                } else {
                                    alert("No matching patient found.");
                                }
                            })
                            .catch(() => {
                                // Fallback lookup if ID is passed
                                const parsedId = parseInt(this.searchQuery.replace('#PAT-', ''));
                                if (!isNaN(parsedId)) {
                                    this.fetchPatientDetails(parsedId);
                                    this.stopScanner();
                                } else {
                                    alert("Search lookup failed. Please scan or enter patient ID number.");
                                }
                            });
                    });
            },

            submitAadharUpdate() {
                if (this.aadharInput.length !== 12 || isNaN(this.aadharInput)) {
                    alert("Please enter a valid 12-digit numeric Aadhaar number.");
                    return;
                }

                this.submitting = true;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/admin/verify-patient/${this.patient.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ aadhar_number: this.aadharInput })
                })
                .then(r => r.json())
                .then(data => {
                    this.submitting = false;
                    if (data.success) {
                        alert(data.message);
                        this.patient.aadhar_verified = true;
                        this.patient.aadhar_number = this.aadharInput;
                    } else {
                        alert("Update failed: " + data.message);
                    }
                })
                .catch(err => {
                    this.submitting = false;
                    console.error("Error updating Aadhaar: ", err);
                    alert("A database connection error occurred.");
                });
            },

            resetScan() {
                this.patient = null;
                this.aadharInput = '';
                this.searchQuery = '';
                this.startScanner();
            },

            playSuccessBeep() {
                // Synthesize an official scanning beep sound using Web Audio API
                try {
                    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioCtx.createOscillator();
                    const gainNode = audioCtx.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioCtx.destination);

                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 high beep
                    gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);

                    oscillator.start();
                    gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
                    oscillator.stop(audioCtx.currentTime + 0.15);
                } catch (e) {
                    console.warn("Audio Context beep error:", e);
                }
            },

            formatAadharString(val) {
                if (!val) return '';
                return val.replace(/(\d{4})(\d{4})(\d{4})/, '$1 $2 $3');
            }
        };
    }
</script>
@endsection
