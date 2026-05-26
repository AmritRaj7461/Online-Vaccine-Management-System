<?php

use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\AdminCenterController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVaccineController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\UserFeatureController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Named Routes)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Routes (Route Group with 'auth' middleware prefix)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Aadhaar e-KYC Verification
    Route::post('/aadhar/send-otp', [\App\Http\Controllers\AadharVerificationController::class, 'sendOtp'])->name('aadhar.send-otp');
    Route::post('/aadhar/verify-otp', [\App\Http\Controllers\AadharVerificationController::class, 'verifyOtp'])->name('aadhar.verify-otp');
    Route::get('/aadhar-exemption-pass', [UserFeatureController::class, 'aadharExemptionPass'])->name('aadhar.exemption-pass');

    // Vaccines (view only for users)
    Route::get('/vaccines', [VaccineController::class, 'index'])->name('vaccines.index');
    Route::get('/vaccines/{vaccine}', [VaccineController::class, 'show'])->name('vaccines.show');

    // Appointments (RESTful Resource Controller)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::get('/appointments/{appointment}/certificate', [AppointmentController::class, 'certificate'])->name('appointments.certificate');

    // Advanced Interactive Features
    Route::get('/notifications', [UserFeatureController::class, 'notifications'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [UserFeatureController::class, 'markNotificationRead'])->name('notifications.read');
    Route::get('/appointments/{appointment}/pass', [UserFeatureController::class, 'walletPass'])->name('appointments.pass');
    Route::get('/appointments/{appointment}/queue', [UserFeatureController::class, 'queueStatus'])->name('appointments.queue');
    Route::get('/appointments/{appointment}/wellness', [UserFeatureController::class, 'wellnessLogForm'])->name('appointments.wellness');
    Route::post('/appointments/{appointment}/wellness', [UserFeatureController::class, 'storeWellnessLog'])->name('appointments.wellness.store');
});

/*
|--------------------------------------------------------------------------
| Public Routes (Additional)
|--------------------------------------------------------------------------
*/
Route::get('/verify-certificate/{appointment}', [AppointmentController::class, 'verifyCertificate'])->name('verify.certificate');
Route::get('/verify-pass/{user}', [UserFeatureController::class, 'verifyExemptionPass'])->name('verify.exemption-pass');

/*
|--------------------------------------------------------------------------
| Admin Routes (Route Group with prefix 'admin' and IsAdmin middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');

        // Vaccine CRUD
        Route::resource('vaccines', AdminVaccineController::class);

        // Centers CRUD
        Route::resource('centers', AdminCenterController::class);

        // Appointment Management
        Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}', [AdminAppointmentController::class, 'update'])->name('appointments.update');
    });

/*
|--------------------------------------------------------------------------
| API Routes (Basic REST API)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    Route::get('/vaccines', [ApiController::class, 'vaccines'])->name('vaccines');
    Route::get('/appointments', [ApiController::class, 'appointments'])->name('appointments');
});
