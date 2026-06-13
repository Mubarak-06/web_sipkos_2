<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SipkosController;
use App\Http\Controllers\ChatbotController;

// ===============================
// LANDING / SPLASH
// ===============================
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin')
            : redirect()->route('home');
    }

    return view('splash');
})->name('landing');

// ===============================
// AUTH
// ===============================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/login/user', function () {
        return redirect()->route('login');
    })->name('login.user');

    Route::post('/login/user', [AuthController::class, 'login'])->name('login.user.submit');

    Route::get('/login/admin', function () {
        return redirect()->route('login');
    })->name('login.admin');

    Route::post('/login/admin', [AuthController::class, 'login'])->name('login.admin.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// ===============================
// LOGOUT
// ===============================
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ===============================
// USER AREA
// ===============================
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/home', [SipkosController::class, 'index'])->name('home');

    Route::get('/kos/{id}', [SipkosController::class, 'show'])
        ->whereNumber('id')
        ->name('kos.show');

    Route::get('/booking/pdf', [SipkosController::class, 'downloadBookingPdf'])
        ->name('booking.pdf');

    Route::get('/booking/{id}', [SipkosController::class, 'bookingForm'])
        ->whereNumber('id')
        ->name('kos.booking');

    Route::post('/booking/{id}', [SipkosController::class, 'storeBooking'])
        ->whereNumber('id')
        ->name('booking.store');

    Route::get('/my-bookings', [SipkosController::class, 'myBookings'])
        ->name('my.bookings');

    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
        ->name('chatbot.ask');
});

// ===============================
// ADMIN AREA
// ===============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [SipkosController::class, 'admin'])->name('admin');

    Route::post('/kos', [SipkosController::class, 'store'])
        ->name('kos.store');

    Route::get('/kos/{id}/edit', [SipkosController::class, 'edit'])
        ->whereNumber('id')
        ->name('kos.edit');

    Route::put('/kos/{id}', [SipkosController::class, 'update'])
        ->whereNumber('id')
        ->name('kos.update');

    Route::delete('/kos/{id}', [SipkosController::class, 'destroy'])
        ->whereNumber('id')
        ->name('kos.destroy');
});