@extends('layouts.app')

@section('title', 'Wallet Pass')

@section('content')
<div class="max-w-md mx-auto px-4 py-12 transition-colors duration-200">
    {{-- Go Back Action --}}
    <div class="mb-6 flex justify-between items-center no-print">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-slate-505 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 font-semibold cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <button onclick="downloadPass()" class="text-xs font-bold text-emerald-650 dark:text-emerald-450 hover:underline cursor-pointer">Download Pass</button>
    </div>

    {{-- Glassmorphism Mobile Card Pass (Emerald verified theme) --}}
    <div id="wallet-card" class="relative bg-gradient-to-br from-emerald-900 to-[#061e16] dark:from-[#052216] dark:to-[#020c08] text-white rounded-[32px] p-6 shadow-2xl overflow-hidden border border-white/10 select-none hover-shimmer transform transition-transform hover:scale-[1.01]">
        
        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-500/20 border border-emerald-500/30 rounded-xl flex items-center justify-center">
                    <span class="text-emerald-405 font-black text-sm">印</span>
                </div>
                <div>
                    <h1 class="text-xs font-black tracking-wider uppercase leading-none text-white/90">Wallet Pass</h1>
                    <span class="text-[8px] font-bold text-slate-400 leading-none">Government of India</span>
                </div>
            </div>
            <span class="px-2.5 py-0.5 text-[9px] font-black bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full flex items-center gap-1 uppercase tracking-wider">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Immunized
            </span>
        </div>

        {{-- Main Body Info --}}
        <div class="space-y-6">
            {{-- Big Vaccine Banner --}}
            <div class="text-center bg-white/5 border border-white/5 p-4 rounded-2xl relative">
                <div class="absolute top-2 right-2 text-[9px] font-bold text-emerald-405">Completed</div>
                <h2 class="text-2xl font-black text-white leading-none tracking-tight">{{ $appointment->vaccine->name }}</h2>
                <p class="text-[10px] text-slate-400 mt-1.5 font-bold uppercase tracking-wider">{{ $appointment->vaccine->manufacturer }}</p>
                
                {{-- Dose Badge --}}
                <div class="mt-3.5 inline-block bg-blue-500/10 border border-blue-500/20 text-blue-400 px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider">
                    Dose {{ $appointment->dose_number }} of {{ $appointment->vaccine->doses_required }}
                </div>
            </div>

            {{-- Beneficiary Details --}}
            <div class="grid grid-cols-2 gap-4 text-xs pt-1">
                <div>
                    <span class="text-slate-400 font-medium block">Beneficiary Name</span>
                    <span class="font-extrabold text-white mt-1 block text-sm">{{ $appointment->user->name }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Aadhaar KYC</span>
                    <span class="font-bold text-slate-200 mt-1 block text-sm">
                        {{ $appointment->user->aadhar_number ? 'XXXX-XXXX-' . substr($appointment->user->aadhar_number, -4) : 'Not Verified' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Vaccination Date</span>
                    <span class="font-bold text-slate-200 mt-1 block text-sm">{{ $appointment->appointment_date->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">Reference ID</span>
                    <span class="font-mono font-bold text-slate-200 mt-1 block text-sm">#PAT-{{ str_pad($appointment->user->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-span-2 border-t border-white/5 pt-3">
                    <span class="text-slate-400 font-medium block">Vaccinated At</span>
                    <span class="font-bold text-slate-200 mt-1 block leading-tight text-xs">{{ $appointment->center->name }}</span>
                </div>
            </div>

            {{-- Secure QR Code scan section --}}
            <div class="border-t border-white/10 pt-5 flex flex-col items-center gap-3">
                <div class="w-36 h-36 bg-white rounded-2xl p-2.5 flex items-center justify-center shadow-lg relative overflow-hidden">
                    {{-- QR rendered client-side to avoid CORS/tainted-canvas issues --}}
                    <div id="qr-code-render" class="w-full h-full flex items-center justify-center"></div>
                </div>
                <span class="text-[9px] font-black text-slate-400 tracking-widest uppercase">Digital Pass QR Code</span>
            </div>
        </div>
        
        {{-- Card footer security disclaimer --}}
        <div class="mt-6 border-t border-white/5 pt-4 text-center">
            <p class="text-[8px] text-slate-500 leading-normal max-w-[280px] mx-auto">
                This digital pass is generated by VacciCare. It is cryptographically linked to active registries of MOHFW.
            </p>
        </div>
    </div>
</div>

{{-- QRCode.js (renders to canvas — no cross-origin issues for html2canvas) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
{{-- html2canvas --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    // Generate QR code as an inline canvas (same-origin, no CORS issues)
    document.addEventListener('DOMContentLoaded', function () {
        const qrContainer = document.getElementById('qr-code-render');
        new QRCode(qrContainer, {
            text: "{{ addslashes($verificationUrl) }}",
            width: 110,
            height: 110,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
        });

        // Style the generated canvas/img to fill the container
        setTimeout(() => {
            const qrImg = qrContainer.querySelector('img, canvas');
            if (qrImg) {
                qrImg.style.width = '100%';
                qrImg.style.height = '100%';
                qrImg.style.objectFit = 'contain';
                qrImg.style.borderRadius = '8px';
            }
        }, 100);
    });

    function downloadPass() {
        const card = document.getElementById('wallet-card');

        // Remove hover/scale transforms so capture is pixel-accurate
        const prevTransform = card.style.transform;
        card.classList.remove('hover:scale-[1.01]');
        card.style.transform = 'none';

        // Subtle audio feedback
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(600, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.05, audioCtx.currentTime);
            osc.start();
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12);
            osc.stop(audioCtx.currentTime + 0.12);
        } catch (e) {}

        html2canvas(card, {
            backgroundColor: null,   // keep rounded-corner transparency
            scale: 3,                 // 3× for crisp retina-quality output
            logging: false,
            useCORS: false,           // all resources are now same-origin
            allowTaint: false
        }).then(canvas => {
            card.style.transform = prevTransform;

            const link = document.createElement('a');
            link.download = 'VacciCare_Wallet_Pass.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }).catch(err => {
            card.style.transform = prevTransform;
            console.error('html2canvas error:', err);
            alert('Download failed. Please try again in a moment.');
        });
    }
</script>
@endsection

