<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VacciCare — India's National Digital Immunization Platform. Book vaccine slots, verify e-certificates, and manage your vaccination journey securely.">
    <title>VacciCare — National Immunization Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:    #070913;
            --navy-2:  #0b0f1a;
            --card:    rgba(9, 13, 26, 0.7);
            --border:  rgba(255,255,255,0.08);
            --emerald: #10b981;
            --indigo:  #6366f1;
            --purple:  #8b5cf6;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--navy);
            color: #f8fafc;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ── Background decorations ── */
        .bg-grid {
            background-image:
                linear-gradient(to right,  rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* ── Animated blobs ── */
        @keyframes blob {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(40px,-55px) scale(1.15); }
            66%      { transform: translate(-30px,35px) scale(0.88); }
        }
        .blob { animation: blob 22s infinite alternate ease-in-out; }
        .blob-2 { animation-delay: 6s; }
        .blob-3 { animation-delay: 12s; }

        /* ── Fade + slide up entry ── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(24px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up      { animation: fadeUp 0.7s cubic-bezier(.16,1,.3,1) both; }
        .delay-100    { animation-delay:.10s; }
        .delay-200    { animation-delay:.20s; }
        .delay-300    { animation-delay:.30s; }
        .delay-400    { animation-delay:.40s; }
        .delay-500    { animation-delay:.50s; }

        /* ── Glow pulse on CTA buttons ── */
        @keyframes glow-pulse {
            0%,100% { box-shadow: 0 0 20px rgba(99,102,241,.4); }
            50%      { box-shadow: 0 0 45px rgba(99,102,241,.7); }
        }
        .btn-glow { animation: glow-pulse 2.5s ease-in-out infinite; }

        /* ── Hero floating card ── */
        @keyframes float {
            0%,100% { transform: translateY(0px) rotate(0deg); }
            50%      { transform: translateY(-14px) rotate(1deg); }
        }
        .float-card { animation: float 5s ease-in-out infinite; }

        /* ── Ping slow ── */
        @keyframes ping-slow {
            75%,100% { transform: scale(2); opacity: 0; }
        }
        .ping-slow { animation: ping-slow 2.5s cubic-bezier(0,0,.2,1) infinite; }

        /* ── Glassmorphism card ── */
        .glass {
            background: var(--card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
        }

        /* ── Stat counter ── */
        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #a5f3fc, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        /* ── Feature icon ── */
        .feat-icon {
            width: 52px; height: 52px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* ── Tricolor bar (India flag motif) ── */
        .tricolor {
            height: 4px;
            background: linear-gradient(90deg, #FF9933 33%, #fff 33% 66%, #138808 66%);
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 99px; }

        /* ── Nav ── */
        nav.lp-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem;
            height: 68px;
            background: rgba(7,9,19,0.75);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        /* ── CTA buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            padding: 14px 32px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; font-weight: 700; font-size: 15px;
            border: none; border-radius: 14px; cursor: pointer; text-decoration: none;
            transition: all .2s ease;
        }
        .btn-primary:hover { filter: brightness(1.1); transform: translateY(-2px); }
        .btn-primary:active { transform: translateY(0); }

        .btn-outline {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            padding: 14px 32px;
            background: transparent;
            color: #e2e8f0; font-weight: 700; font-size: 15px;
            border: 1.5px solid rgba(255,255,255,0.2); border-radius: 14px; cursor: pointer; text-decoration: none;
            transition: all .2s ease;
        }
        .btn-outline:hover { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.4); transform: translateY(-2px); }

        /* ── Section titles ── */
        .section-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
            background: rgba(99,102,241,.12); color: #a5b4fc;
            border: 1px solid rgba(99,102,241,.25); border-radius: 999px;
            padding: 5px 14px;
        }

        @media (max-width: 768px) {
            .hero-grid { grid-template-columns: 1fr !important; }
            .hero-img-wrap { display: none; }
            nav.lp-nav { padding: 0 1.25rem; }
            .stats-grid { grid-template-columns: 1fr 1fr !important; }
            .features-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</head>
<body>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║             BACKGROUND DECORATIONS    ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <div style="position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden;" class="bg-grid">
        <div class="blob"  style="position:absolute;top:-15%;left:-10%;width:55vw;height:55vw;max-width:600px;max-height:600px;background:radial-gradient(circle,rgba(99,102,241,.18),transparent 70%);border-radius:50%;filter:blur(80px);"></div>
        <div class="blob blob-2" style="position:absolute;bottom:-10%;right:-10%;width:50vw;height:50vw;max-width:550px;max-height:550px;background:radial-gradient(circle,rgba(16,185,129,.14),transparent 70%);border-radius:50%;filter:blur(90px);"></div>
        <div class="blob blob-3" style="position:absolute;top:50%;left:40%;width:35vw;height:35vw;max-width:400px;max-height:400px;background:radial-gradient(circle,rgba(139,92,246,.1),transparent 70%);border-radius:50%;filter:blur(70px);"></div>
    </div>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║                   NAV                 ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <nav class="lp-nav" x-data="{ open: false }">
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span style="font-size:20px;font-weight:800;background:linear-gradient(135deg,#a5b4fc,#c4b5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">VacciCare</span>
        </a>

        <!-- Desktop links -->
        <div style="display:flex;align-items:center;gap:8px;" class="nav-links" id="nav-desktop">
            <a href="#features" style="color:#94a3b8;text-decoration:none;font-size:14px;font-weight:600;padding:8px 14px;border-radius:10px;transition:.2s;" onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.color='#94a3b8';this.style.background='transparent'">Features</a>
            <a href="#how-it-works" style="color:#94a3b8;text-decoration:none;font-size:14px;font-weight:600;padding:8px 14px;border-radius:10px;transition:.2s;" onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.color='#94a3b8';this.style.background='transparent'">How it Works</a>
            <a href="{{ route('login') }}" style="color:#94a3b8;text-decoration:none;font-size:14px;font-weight:600;padding:8px 14px;border-radius:10px;transition:.2s;" onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.color='#94a3b8';this.style.background='transparent'">Sign In</a>
            <a href="{{ route('register') }}" class="btn-primary" style="padding:10px 22px;font-size:14px;">Get Started →</a>
        </div>

        <!-- Mobile hamburger -->
        <button @click="open=!open" style="display:none;padding:8px;background:rgba(255,255,255,.06);border:1px solid var(--border);border-radius:10px;cursor:pointer;" id="hamburger-btn" aria-label="Menu">
            <svg width="20" height="20" fill="none" stroke="#94a3b8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        <!-- Mobile menu drawer -->
        <div x-show="open" x-transition style="position:fixed;top:68px;left:0;right:0;background:rgba(7,9,19,.96);backdrop-filter:blur(20px);border-bottom:1px solid var(--border);padding:1rem 1.5rem;display:flex;flex-direction:column;gap:.5rem;z-index:49;">
            <a href="#features" @click="open=false" style="display:block;padding:12px 16px;color:#cbd5e1;text-decoration:none;font-size:15px;font-weight:600;border-radius:12px;" onmouseover="this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.background='transparent'">Features</a>
            <a href="#how-it-works" @click="open=false" style="display:block;padding:12px 16px;color:#cbd5e1;text-decoration:none;font-size:15px;font-weight:600;border-radius:12px;" onmouseover="this.style.background='rgba(255,255,255,.05)'" onmouseout="this.style.background='transparent'">How it Works</a>
            <a href="{{ route('login') }}" style="display:block;padding:12px 16px;color:#cbd5e1;text-decoration:none;font-size:15px;font-weight:600;border-radius:12px;">Sign In</a>
            <a href="{{ route('register') }}" class="btn-primary" style="text-align:center;margin-top:4px;">Get Started →</a>
        </div>
    </nav>

    <div style="position:relative;z-index:1;">

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║                  HERO                 ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <section style="min-height:100vh;padding-top:68px;display:flex;align-items:center;padding-bottom:80px;">
        <div style="max-width:1200px;margin:0 auto;padding:60px 24px 40px;width:100%;">
            <div class="hero-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">

                <!-- Text -->
                <div>
                    <!-- Trust badge -->
                    <div class="fade-up" style="margin-bottom:28px;">
                        <span style="display:inline-flex;align-items:center;gap:8px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.2);border-radius:999px;padding:7px 16px;font-size:12px;font-weight:700;color:#34d399;letter-spacing:.06em;text-transform:uppercase;">
                            <span style="width:7px;height:7px;border-radius:50%;background:#34d399;display:inline-block;box-shadow:0 0 10px #34d399;" class="ping-slow"></span>
                            Government of India · Official Platform
                        </span>
                    </div>

                    <h1 class="fade-up delay-100" style="font-size:clamp(2.2rem,5vw,3.6rem);font-weight:900;line-height:1.1;letter-spacing:-.02em;margin-bottom:24px;">
                        <span style="color:#f8fafc;">Protecting India,</span><br>
                        <span style="background:linear-gradient(135deg,#6366f1,#a78bfa,#34d399);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">One Dose at a Time</span>
                    </h1>

                    <p class="fade-up delay-200" style="font-size:17px;color:#94a3b8;line-height:1.75;max-width:480px;margin-bottom:40px;">
                        Book vaccine slots, verify your Aadhaar e-KYC identity, download digitally-signed certificates, and track your full immunization journey — all in one secure platform.
                    </p>

                    <!-- CTAs -->
                    <div class="fade-up delay-300" style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:48px;">
                        <a href="{{ route('register') }}" class="btn-primary btn-glow" id="cta-register">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Register Free
                        </a>
                        <a href="{{ route('login') }}" class="btn-outline" id="cta-login">
                            Sign In
                        </a>
                    </div>

                    <!-- Trust indicators -->
                    <div class="fade-up delay-400" style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#64748b;font-weight:600;">
                            <svg width="16" height="16" fill="none" stroke="#34d399" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Aadhaar e-KYC Secured
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#64748b;font-weight:600;">
                            <svg width="16" height="16" fill="none" stroke="#34d399" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Cryptographic Certificates
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#64748b;font-weight:600;">
                            <svg width="16" height="16" fill="none" stroke="#34d399" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            QR Verification
                        </div>
                    </div>
                </div>

                <!-- Hero image / floating card -->
                <div class="hero-img-wrap" style="display:flex;align-items:center;justify-content:center;position:relative;">
                    <!-- Glow halo -->
                    <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50% 50%,rgba(99,102,241,.25),transparent 70%);border-radius:50%;filter:blur(40px);pointer-events:none;"></div>

                    <!-- Floating glass phone mockup -->
                    <div class="float-card glass" style="width:300px;padding:28px;position:relative;z-index:1;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                            <div style="width:36px;height:36px;background:linear-gradient(135deg,#059669,#10b981);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <div style="font-size:11px;font-weight:800;color:#f8fafc;letter-spacing:.05em;text-transform:uppercase;">Vaccination Certificate</div>
                                <div style="font-size:10px;color:#475569;margin-top:2px;">Ministry of Health · GoI</div>
                            </div>
                            <span style="margin-left:auto;background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.25);color:#34d399;font-size:9px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:3px 8px;border-radius:999px;">Verified ✓</span>
                        </div>

                        <!-- Certificate info rows -->
                        <div style="space-y:12px;">
                            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:10px;">
                                <span style="color:#64748b;font-weight:500;">Beneficiary</span>
                                <span style="color:#f1f5f9;font-weight:700;">Amrit Raj</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:10px;">
                                <span style="color:#64748b;font-weight:500;">Vaccine</span>
                                <span style="color:#34d399;font-weight:700;">COVAXIN · Dose 2</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:10px;">
                                <span style="color:#64748b;font-weight:500;">Date</span>
                                <span style="color:#f1f5f9;font-weight:700;">26 May 2026</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:16px;">
                                <span style="color:#64748b;font-weight:500;">Status</span>
                                <span style="color:#f1f5f9;font-weight:700;">Fully Immunized 🎉</span>
                            </div>
                        </div>

                        <!-- QR placeholder -->
                        <div style="display:flex;flex-direction:column;align-items:center;padding-top:14px;border-top:1px solid var(--border);">
                            <div style="width:70px;height:70px;background:#fff;border-radius:12px;padding:6px;display:flex;align-items:center;justify-content:center;margin-bottom:6px;">
                                <svg viewBox="0 0 21 21" width="58" height="58" fill="#1e293b"><path d="M0 0h9v9H0V0zm2 2v5h5V2H2zm1 1h3v3H3V3zm7-3h9v9h-9V0zm2 2v5h5V2h-5zm1 1h3v3h-3V3zM0 10h9v9H0v-9zm2 2v5h5v-5H2zm1 1h3v3H3v-3zm9 4h2v2h-2v-2zm2-6h2v2h-2v-2zm-4 2h2v2h-2v-2zm2 2h2v2h-2v-2zm2 2h2v2h-2v-2zm2-4h2v2h-2v-2zm-2-2h2v2h-2v-2z"/></svg>
                            </div>
                            <span style="font-size:8px;font-weight:800;color:#475569;letter-spacing:.1em;text-transform:uppercase;">Scan to Verify</span>
                        </div>
                    </div>

                    <!-- Floating badge: Aadhaar linked -->
                    <div class="glass" style="position:absolute;top:-10px;right:-20px;padding:10px 16px;font-size:11px;font-weight:700;color:#a5f3fc;display:flex;align-items:center;gap:6px;border-radius:14px;animation:float 4s 1s ease-in-out infinite;">
                        <span style="width:8px;height:8px;background:#34d399;border-radius:50%;display:inline-block;box-shadow:0 0 8px #34d399;"></span>
                        Aadhaar e-KYC Active
                    </div>

                    <!-- Floating badge: Secure -->
                    <div class="glass" style="position:absolute;bottom:10px;left:-30px;padding:10px 16px;font-size:11px;font-weight:700;color:#c4b5fd;display:flex;align-items:center;gap:6px;border-radius:14px;animation:float 4s 2s ease-in-out infinite;">
                        <svg width="14" height="14" fill="none" stroke="#a78bfa" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        SSL Encrypted
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║               STATS BAR               ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <section style="padding:0 24px 80px;">
        <div style="max-width:1100px;margin:0 auto;">
            <div class="glass" style="padding:48px 40px;">
                <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:32px;text-align:center;">
                    <div>
                        <div class="stat-number">2.4M+</div>
                        <div style="color:#64748b;font-size:13px;font-weight:600;margin-top:6px;text-transform:uppercase;letter-spacing:.06em;">Doses Administered</div>
                    </div>
                    <div>
                        <div class="stat-number">18K+</div>
                        <div style="color:#64748b;font-size:13px;font-weight:600;margin-top:6px;text-transform:uppercase;letter-spacing:.06em;">Vaccination Centers</div>
                    </div>
                    <div>
                        <div class="stat-number">99.9%</div>
                        <div style="color:#64748b;font-size:13px;font-weight:600;margin-top:6px;text-transform:uppercase;letter-spacing:.06em;">Platform Uptime</div>
                    </div>
                    <div>
                        <div class="stat-number">1.2M+</div>
                        <div style="color:#64748b;font-size:13px;font-weight:600;margin-top:6px;text-transform:uppercase;letter-spacing:.06em;">Citizens Registered</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║               FEATURES                ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <section id="features" style="padding:80px 24px;">
        <div style="max-width:1100px;margin:0 auto;">
            <!-- Title -->
            <div style="text-align:center;margin-bottom:60px;">
                <div class="section-badge" style="margin-bottom:16px;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#818cf8;display:inline-block;"></span>
                    Platform Features
                </div>
                <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;letter-spacing:-.02em;color:#f8fafc;margin-bottom:12px;">Everything you need to stay protected</h2>
                <p style="color:#64748b;font-size:16px;max-width:500px;margin:0 auto;line-height:1.7;">A complete end-to-end immunization management system built for citizens and healthcare staff.</p>
            </div>

            <!-- Feature grid -->
            <div class="features-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">

                @php
                $feats = [
                    ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','color'=>'#6366f1','bg'=>'rgba(99,102,241,.12)','title'=>'Aadhaar e-KYC Verification','desc'=>'Verify your identity via UIDAI-linked OTP. Blocks duplicate bookings and ensures every dose is accurately attributed.'],
                    ['icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)','title'=>'Smart Slot Booking','desc'=>'Browse vaccines and centers near you, check real-time stock, and book appointment slots in seconds.'],
                    ['icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)','title'=>'Digital Certificates','desc'=>'Download cryptographically-signed vaccination certificates printable as PDFs with a QR verification code.'],
                    ['icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-16v3m0 0h.01M12 12h.01M16 20h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'#ec4899','bg'=>'rgba(236,72,153,.12)','title'=>'QR Pass Scanner','desc'=>'Healthcare staff scan citizen passes on-site with the built-in camera scanner to verify and update records instantly.'],
                    ['icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z','color'=>'#06b6d4','bg'=>'rgba(6,182,212,.12)','title'=>'Mobile Wallet Pass','desc'=>'Save a sleek, downloadable wallet card (PNG) with your immunization status directly to your phone gallery.'],
                    ['icon'=>'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z','color'=>'#8b5cf6','bg'=>'rgba(139,92,246,.12)','title'=>'Wellness Tracking','desc'=>'Log post-vaccination wellness check-ins and side effects. Monitor your recovery timeline in a beautiful UI.'],
                ];
                @endphp

                @foreach($feats as $f)
                <div class="glass" style="padding:28px;transition:transform .25s,box-shadow .25s;" onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 20px 60px rgba(0,0,0,.4)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div class="feat-icon" style="background:{{ $f['bg'] }};margin-bottom:18px;">
                        <svg width="24" height="24" fill="none" stroke="{{ $f['color'] }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:800;color:#f1f5f9;margin-bottom:8px;">{{ $f['title'] }}</h3>
                    <p style="font-size:13px;color:#64748b;line-height:1.7;">{{ $f['desc'] }}</p>
                </div>
                @endforeach

            </div>
        </div>
    </section>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║              HOW IT WORKS             ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <section id="how-it-works" style="padding:80px 24px;">
        <div style="max-width:900px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:56px;">
                <div class="section-badge" style="margin-bottom:16px;">
                    <span style="width:6px;height:6px;border-radius:50%;background:#34d399;display:inline-block;"></span>
                    Simple Process
                </div>
                <h2 style="font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;letter-spacing:-.02em;color:#f8fafc;margin-bottom:12px;">Get vaccinated in 4 easy steps</h2>
            </div>

            <div style="display:flex;flex-direction:column;gap:0;position:relative;">
                <!-- Vertical line -->
                <div style="position:absolute;left:28px;top:56px;bottom:56px;width:2px;background:linear-gradient(to bottom,#6366f1,#10b981);opacity:.3;border-radius:2px;"></div>

                @php
                $steps = [
                    ['n'=>'1','color'=>'#6366f1','title'=>'Create Your Account','desc'=>'Register with your name, email, and date of birth in under 60 seconds.'],
                    ['n'=>'2','color'=>'#8b5cf6','title'=>'Verify Aadhaar e-KYC','desc'=>'Enter your 12-digit Aadhaar number and verify with OTP sent to your registered mobile.'],
                    ['n'=>'3','color'=>'#06b6d4','title'=>'Book a Vaccine Slot','desc'=>'Browse available vaccines and nearby centers. Choose your preferred date and time slot.'],
                    ['n'=>'4','color'=>'#10b981','title'=>'Download Your Certificate','desc'=>'After vaccination, download your signed digital certificate with QR code for instant verification anywhere.'],
                ];
                @endphp

                @foreach($steps as $s)
                <div style="display:flex;gap:24px;align-items:flex-start;padding:24px 0;position:relative;">
                    <div style="width:56px;height:56px;border-radius:50%;background:{{ $s['color'] }}22;border:2px solid {{ $s['color'] }}55;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;color:{{ $s['color'] }};flex-shrink:0;z-index:1;background-color:var(--navy-2);">
                        {{ $s['n'] }}
                    </div>
                    <div style="padding-top:10px;">
                        <h3 style="font-size:17px;font-weight:800;color:#f1f5f9;margin-bottom:6px;">{{ $s['title'] }}</h3>
                        <p style="font-size:14px;color:#64748b;line-height:1.65;">{{ $s['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║                  CTA                  ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <section style="padding:80px 24px 100px;">
        <div style="max-width:700px;margin:0 auto;text-align:center;">
            <div class="glass" style="padding:60px 40px;position:relative;overflow:hidden;">
                <!-- Bg glow -->
                <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50% 0%,rgba(99,102,241,.2),transparent 70%);pointer-events:none;"></div>
                <div style="position:relative;z-index:1;">
                    <!-- India tricolor bar -->
                    <div class="tricolor" style="width:80px;margin:0 auto 28px;border-radius:4px;"></div>
                    <h2 style="font-size:clamp(1.6rem,3.5vw,2.4rem);font-weight:900;color:#f8fafc;margin-bottom:16px;letter-spacing:-.02em;">Start your immunization journey today</h2>
                    <p style="color:#64748b;font-size:15px;line-height:1.7;max-width:440px;margin:0 auto 36px;">Join over 1.2 million citizens who have already secured their health records on VacciCare.</p>
                    <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:14px;">
                        <a href="{{ route('register') }}" class="btn-primary btn-glow" id="cta-register-2">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Create Free Account
                        </a>
                        <a href="{{ route('login') }}" class="btn-outline" id="cta-login-2">
                            Already registered? Sign In
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ╔═══════════════════════════════════════╗ -->
    <!-- ║                FOOTER                 ║ -->
    <!-- ╚═══════════════════════════════════════╝ -->
    <footer style="border-top:1px solid var(--border);padding:32px 24px;text-align:center;">
        <div style="max-width:900px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
            <span style="font-size:13px;color:#334155;">© {{ date('Y') }} VacciCare · National Digital Health Platform · All rights reserved.</span>
            <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#475569;background:rgba(255,255,255,.04);border:1px solid var(--border);padding:5px 12px;border-radius:999px;">
                <svg width="13" height="13" fill="none" stroke="#6366f1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Aadhaar e-KYC Secure · Helpline: 1075
            </div>
        </div>
    </footer>

    </div><!-- /z-index wrapper -->

    <script>
        // Show hamburger on mobile
        function checkMobile() {
            const btn = document.getElementById('hamburger-btn');
            const links = document.getElementById('nav-desktop');
            if (window.innerWidth < 768) {
                btn.style.display = 'flex';
                links.style.display = 'none';
            } else {
                btn.style.display = 'none';
                links.style.display = 'flex';
            }
        }
        checkMobile();
        window.addEventListener('resize', checkMobile);

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const id = a.getAttribute('href').slice(1);
                const el = document.getElementById(id);
                if (el) { e.preventDefault(); el.scrollIntoView({ behavior: 'smooth' }); }
            });
        });
    </script>
</body>
</html>
