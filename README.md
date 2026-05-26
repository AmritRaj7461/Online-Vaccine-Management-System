<div align="center">

<!-- Animated Header Banner -->
<img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&customColorList=12,20,24&height=220&section=header&text=VacciCare&fontSize=72&fontColor=ffffff&fontAlignY=38&desc=National%20Digital%20Immunization%20Platform&descAlignY=58&descSize=20&animation=fadeIn" width="100%" />

<br/>

<!-- Badge row 1 — Tech stack -->
<img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"/>
<img src="https://img.shields.io/badge/TailwindCSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS"/>
<img src="https://img.shields.io/badge/AlpineJS-3.x-77C1D2?style=for-the-badge&logo=alpine.js&logoColor=white" alt="AlpineJS"/>
<img src="https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"/>
<img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>

<br/><br/>

<!-- Badge row 2 — Project meta -->
<img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+"/>
<img src="https://img.shields.io/badge/License-MIT-22c55e?style=flat-square" alt="MIT License"/>
<img src="https://img.shields.io/badge/Platform-Web%20%2B%20Mobile-6366f1?style=flat-square" alt="Platform"/>
<img src="https://img.shields.io/badge/Status-Active-10b981?style=flat-square" alt="Status Active"/>
<img src="https://img.shields.io/badge/QR%20Verification-Enabled-f59e0b?style=flat-square" alt="QR Verification"/>

<br/><br/>

<h3>
  <a href="#-introduction">Introduction</a> ·
  <a href="#-feature-showcase">Features</a> ·
  <a href="#-tech-stack">Tech Stack</a> ·
  <a href="#-quick-start">Quick Start</a> ·
  <a href="#-project-structure">Structure</a> ·
  <a href="#-api-reference">API</a> ·
  <a href="#-demo-accounts">Demo</a>
</h3>

</div>

---

## 🌟 Introduction

**VacciCare** is a full-stack, production-grade **National Digital Immunization Management System** built on Laravel 13. It bridges the gap between government health registries and vaccination centers — providing citizens a frictionless way to book, verify, and carry their immunization credentials, while giving healthcare staff real-time scan-and-update capabilities.

> _"Making India's immunization journey paperless, secure, and accessible from every screen."_

The platform features a **premium dark glassmorphism UI** with smooth AlpineJS micro-animations, responsive mobile-first layouts, cryptographically-signed digital certificates, and a built-in QR scanner for healthcare portals.

---

## ✨ Feature Showcase

<table>
<tr>
<td width="50%" valign="top">

### 👤 Citizen Portal

| Feature | Description |
|---|---|
| 🛡️ **Aadhaar e-KYC** | OTP-based identity verification linked to UIDAI registries. Blocks unverified users from booking. |
| 📅 **Smart Slot Booking** | Browse vaccines & centers by city, check real-time stock, book multi-dose slots. |
| 📋 **Appointments Dashboard** | Track pending, approved, completed & cancelled bookings at a glance. |
| 📄 **Digital Certificate** | Cryptographically-signed vaccination certificate with printable QR code. |
| 🎫 **Wallet Pass** | Downloadable glassmorphism card (PNG, 3× retina) — no CORS issues, client-rendered QR. |
| 🆔 **Exemption Pass** | Citizens unable to verify Aadhaar get a scannable pass for in-center verification. |
| 🔔 **Notifications** | Real-time in-app notifications for bookings, approvals, and Aadhaar updates. |
| 🏥 **Wellness Logs** | Post-vaccination side-effect tracking with timeline view. |
| 📊 **Queue Status** | Live queue position monitor for vaccination day. |
| 🌗 **Dark / Light Mode** | Instant theme toggle, persisted in localStorage — zero flash on reload. |

</td>
<td width="50%" valign="top">

### 👑 Admin / Healthcare Portal

| Feature | Description |
|---|---|
| 📊 **Live Dashboard** | Real-time stats — registered users, doses administered, active centers, pending bookings. |
| 🔍 **QR Scanner** | Built-in camera scanner (mobile-first, auto-selects rear camera) to scan citizen passes and verify identity on-site. |
| 🪪 **Aadhaar Update** | After scanning a pass, healthcare staff enter the citizen's Aadhaar — auto-updates their profile. |
| 💉 **Vaccine CRUD** | Create, edit, and archive vaccines with dose schedules and stock management. |
| 🏢 **Center Management** | Full CRUD for vaccination centers including city, capacity, and status controls. |
| 📑 **Appointment Control** | Review and approve/reject citizen bookings with status-push notifications. |
| 📈 **Most Booked Vaccines** | Demand-ranked vaccine list for supply chain forward planning. |
| 📦 **Auto Stock Sync** | Bookings auto-decrement stock; cancellations restore it — zero manual intervention. |

</td>
</tr>
</table>

### 🔒 Security & Infrastructure

| Feature | Description |
|---|---|
| 🔏 **Signed URLs** | All certificate QR codes use Laravel's `URL::signedRoute()` — tamper-evident, cryptographically verified. |
| 🌐 **Host-Aware Signing** | QR URLs are generated using the actual request host, not `APP_URL`, so they work when scanning from any device/network. |
| 🎉 **Selective Confetti** | Celebration animation fires only on login/registration — not on every success toast. |
| 📧 **HTML Email** | Beautiful booking confirmation emails via Laravel Mailer + SMTP. |
| 🔑 **Role-Based Auth** | Separate citizen and admin middleware layers with dedicated route groups. |

---

## 🖼️ Screenshots

> The platform uses a **premium dark glassmorphism** design system with animated gradients, responsive mobile-first layouts, and interactive micro-animations.

<table>
<tr>
  <td align="center"><b>🌐 Landing Page</b><br/><sub>Dark hero with animated blobs, feature grid, and CTA</sub></td>
  <td align="center"><b>🔐 Auth Page</b><br/><sub>Slide-panel login/register — mobile tab-based</sub></td>
</tr>
<tr>
  <td align="center"><b>📊 User Dashboard</b><br/><sub>Appointment overview, dose tracker, quick actions</sub></td>
  <td align="center"><b>📄 Digital Certificate</b><br/><sub>Print/PDF-ready with QR verification code</sub></td>
</tr>
<tr>
  <td align="center"><b>🎫 Wallet Pass</b><br/><sub>Downloadable 3× retina PNG wallet card</sub></td>
  <td align="center"><b>📷 QR Scanner</b><br/><sub>Mobile-first, auto-selects rear camera</sub></td>
</tr>
</table>

---

## 🏗️ Tech Stack

<table>
<tr><th>Layer</th><th>Technology</th><th>Version</th><th>Purpose</th></tr>
<tr><td>⚙️ Backend</td><td>Laravel</td><td>13.x</td><td>MVC framework, routing, auth, mail, queues</td></tr>
<tr><td>🐘 Runtime</td><td>PHP</td><td>8.3+</td><td>Server-side language</td></tr>
<tr><td>🗄️ Database</td><td>MySQL</td><td>8.x</td><td>Relational data store</td></tr>
<tr><td>🎨 CSS Framework</td><td>TailwindCSS</td><td>v4.0</td><td>Utility-first styling with dark mode</td></tr>
<tr><td>⚡ JS Reactivity</td><td>AlpineJS</td><td>3.x</td><td>Declarative UI interactivity (toasts, modals, theme)</td></tr>
<tr><td>📦 Asset Bundler</td><td>Vite</td><td>8.x</td><td>HMR, CSS/JS build pipeline</td></tr>
<tr><td>📷 QR Scanner</td><td>html5-qrcode</td><td>2.3.x</td><td>WebRTC camera QR scanning in admin portal</td></tr>
<tr><td>🖼️ DOM Export</td><td>html-to-image</td><td>1.11.x</td><td>Wallet pass PNG capture (supports oklch/oklab)</td></tr>
<tr><td>📲 QR Generator</td><td>QRCode.js</td><td>1.0</td><td>Client-side canvas QR (no CORS issues)</td></tr>
<tr><td>📧 Email</td><td>Laravel Mailer + SMTP</td><td>—</td><td>Booking confirmation HTML emails</td></tr>
<tr><td>🔏 URL Signing</td><td>Laravel URL Facade</td><td>—</td><td>Tamper-evident certificate verification URLs</td></tr>
</table>

---

## 🗺️ Project Structure

```
Online-Vaccine-Management-System/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php              # Login, register, logout
│   │   │   ├── AppointmentController.php       # Booking + digital certificate + QR verify
│   │   │   ├── UserController.php              # Patient dashboard & profile
│   │   │   ├── UserFeatureController.php       # Wallet pass, notifications, wellness, queue
│   │   │   ├── AadharVerificationController.php# Aadhaar e-KYC OTP flow
│   │   │   ├── VaccineController.php           # Citizen vaccine browsing
│   │   │   ├── ApiController.php               # REST API endpoints
│   │   │   └── Admin/
│   │   │       ├── AdminDashboardController.php # Stats, QR scanner, Aadhaar update
│   │   │       ├── AdminVaccineController.php   # Vaccine CRUD
│   │   │       ├── AdminCenterController.php    # Center CRUD
│   │   │       └── AdminAppointmentController.php# Appointment approval
│   │   └── Middleware/
│   │       └── IsAdmin.php                     # Admin route guard
│   └── Models/
│       ├── User.php                            # Patient / admin user
│       ├── Vaccine.php                         # Vaccine catalogue
│       ├── Center.php                          # Vaccination center
│       ├── Appointment.php                     # Booking record
│       ├── Notification.php                    # In-app notifications
│       ├── WellnessLog.php                     # Post-vaccination logs
│       └── AadhaarRegistry.php                 # Simulated UIDAI registry
│
├── resources/views/
│   ├── landing.blade.php                       # Public landing page (/)
│   ├── layouts/app.blade.php                   # Master layout (nav, toasts, confetti)
│   ├── auth/login.blade.php                    # Login + register (slide-panel, mobile-tabs)
│   ├── user/
│   │   ├── dashboard.blade.php                 # Patient home
│   │   └── profile.blade.php                  # Profile + Aadhaar card widget
│   ├── appointments/
│   │   ├── index.blade.php                     # My bookings
│   │   ├── create.blade.php                    # Book appointment
│   │   ├── show.blade.php                      # Appointment detail
│   │   ├── certificate.blade.php               # Digital certificate + QR
│   │   ├── pass.blade.php                      # Wallet pass download
│   │   └── verify.blade.php                    # Public certificate verify page
│   └── admin/
│       ├── dashboard.blade.php                 # Admin control center
│       └── scanner.blade.php                   # QR scanner portal
│
├── routes/web.php                              # All web routes (public, user, admin)
├── database/
│   ├── migrations/                             # 10 migration files
│   └── seeders/DatabaseSeeder.php              # Demo data seeder
└── public/images/                              # Static assets
```

---

## 🗄️ Database Schema

```
┌─────────────────────────────────────────────────────────┐
│  users                  │  vaccines                     │
│  ─────────────────       │  ─────────────────────────    │
│  id, name, email         │  id, name, manufacturer      │
│  phone, dob, role        │  doses_required, stock       │
│  aadhar_number           │  status, description         │
│  aadhar_verified         │  image_path                  │
│  password                │                               │
├─────────────────────────────────────────────────────────┤
│  centers                 │  appointments                 │
│  ─────────────────       │  ─────────────────────────   │
│  id, name, address       │  id, user_id, vaccine_id     │
│  city, state, pincode    │  center_id, dose_number      │
│  capacity, status        │  appointment_date/time       │
│                          │  status, notes               │
├─────────────────────────────────────────────────────────┤
│  aadhaar_registries      │  notifications               │
│  ─────────────────       │  ─────────────────────────   │
│  id, aadhar_number       │  id, user_id, title          │
│  name, mobile (masked)   │  message, type, read_at      │
├─────────────────────────────────────────────────────────┤
│  wellness_logs                                           │
│  ──────────────────────────────────────                 │
│  id, appointment_id, user_id                            │
│  symptoms (JSON), overall_feeling, notes                │
└─────────────────────────────────────────────────────────┘
```

---

## 🚀 Quick Start

### Prerequisites

- **PHP** 8.3+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`
- **Composer** (PHP dependency manager)
- **Node.js** v18+ & **NPM**
- **MySQL** 8.x (or compatible)

### 1 · Clone & Install

```bash
git clone https://github.com/AmritRaj7461/Online-Vaccine-Management-System.git
cd Online-Vaccine-Management-System
```

### 2 · Configure Environment

```bash
cp .env.example .env
```

Edit `.env` — set your database credentials and `APP_URL`:

```dotenv
APP_URL=http://127.0.0.1:8000   # Change to your public URL in production!

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vaccicare
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="VacciCare"
```

> ⚠️ **Important:** `APP_URL` must match the host your app is served from. QR code signatures are tied to this URL.

### 3 · One-Command Setup

```bash
composer run setup
```

This single command:
- ✅ Installs all PHP & Node.js dependencies
- ✅ Generates application key
- ✅ Runs database migrations (creates all 10 tables)
- ✅ Seeds demo data (vaccines, centers, admin & patient accounts)
- ✅ Builds frontend assets with Vite

### 4 · Start Development Server

```bash
composer run dev
```

This concurrently starts:
- `php artisan serve` → http://127.0.0.1:8000
- `npm run dev` (Vite HMR)
- `php artisan queue:listen` (background jobs)
- `php artisan pail` (real-time log streaming)

---

## 🔑 Demo Accounts

| Role | Email | Password | Notes |
|---|---|---|---|
| 🛡️ **Admin** | `admin@vaccicare.com` | `password` | Full access to admin control center |
| 👤 **Patient** | `user@vaccicare.com` | `password` | Aadhaar: `123456789012` · OTP mobile: `XXXXXX0001` |

---

## 🌐 API Reference

The platform exposes two public REST endpoints for third-party integrations:

### `GET /api/vaccines`
Returns all available vaccines.

```json
[
  {
    "id": 1,
    "name": "COVAXIN",
    "manufacturer": "Bharat Biotech",
    "doses_required": 2,
    "stock": 450,
    "status": "available"
  }
]
```

### `GET /api/appointments`
Returns all appointment records.

```json
[
  {
    "id": 1,
    "user_id": 2,
    "vaccine_id": 1,
    "center_id": 1,
    "dose_number": 1,
    "appointment_date": "2026-05-28",
    "status": "completed"
  }
]
```

---

## 🔏 QR Certificate Verification Flow

```
Patient gets appointment approved
        │
        ▼
Patient visits /user/appointments/{id}/certificate
        │
        ▼
Server generates signed URL:
URL::signedRoute('verify.certificate', ['appointment' => $id])
  └─ Uses request()->getSchemeAndHttpHost() as base (not APP_URL)
        │
        ▼
QR code is rendered client-side by QRCode.js (canvas, no CORS)
        │
        ▼
Healthcare staff / verifier scans QR with phone camera
        │
        ▼
Browser opens /verify-certificate/{id}?signature=...
        │
        ▼
Server validates signature (dual-check with host fallback)
  ├─ ✅ Valid → Shows full certificate details
  └─ ❌ Invalid / tampered → Shows 403 error page
```

---

## 📱 Mobile-First Design

The entire platform is optimized for smartphones:

| Component | Mobile Behaviour |
|---|---|
| **Landing Page** | Responsive hero, collapsible nav drawer, stacked feature grid |
| **Login / Register** | Tab-based panel toggle (no double-stacking) via AlpineJS `isMobile` |
| **Dashboard** | Fluid card grid, touch-friendly buttons |
| **QR Scanner** | Viewfinder sized for mobile screens, auto-selects **rear camera** |
| **Certificate** | Scrollable, print-ready A4 layout |
| **Wallet Pass** | Full-width card, download button spans screen on mobile |
| **Profile** | Aadhaar input stacks vertically on small screens (no text overflow) |

---

## 🎨 Design System

| Token | Value | Usage |
|---|---|---|
| Primary Accent | `#6366f1` (Indigo) | Buttons, active states |
| Success | `#10b981` (Emerald) | Verified badges, completion |
| Warning | `#f59e0b` (Amber) | Pending states, Aadhaar card |
| Destructive | `#f43f5e` (Rose) | Errors, cancelled |
| Surface Dark | `#0b0f19` | Card backgrounds |
| Border Dark | `rgba(255,255,255,0.08)` | Glassmorphism borders |
| Typography | Plus Jakarta Sans / Inter | All headings and body text |

UI Patterns: **Glassmorphism cards** · **Gradient backgrounds** · **Animated blobs** · **Micro-animations** · **AlpineJS reactive state**

---

## 📜 License

This project is open-sourced under the [MIT License](LICENSE).

---

<div align="center">

**Built with ❤️ by [Amrit Raj](https://github.com/AmritRaj7461)**

<br/>

<img src="https://capsule-render.vercel.app/api?type=waving&color=gradient&customColorList=12,20,24&height=120&section=footer" width="100%"/>

</div>
