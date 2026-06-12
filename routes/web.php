<?php

use App\Http\Controllers\SipkosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;
// ==========================================
// RUTE UTAMA NAVIGASI SIPKOS (CUSTOMER SIDE)
// ==========================================
Route::get('/booking/pdf', [SipkosController::class, 'downloadBookingPdf'])->name('booking.pdf');
// 1. Halaman Utama - diarahkan ke method 'index'
Route::get('/', [SipkosController::class, 'index'])->name('home');

// 2. Halaman Detail Kos - diarahkan ke method 'show'
Route::get('/kos/{id}', [SipkosController::class, 'show'])->name('kos.show');

// 3. Tampilan Halaman Formulir Booking - diarahkan ke method 'bookingForm'
// Ini untuk mengatasi error "Route [kos.booking] not defined" di halaman detail
Route::get('/booking/{id}', [SipkosController::class, 'bookingForm'])->name('kos.booking');

// 4. Proses Simpan Booking ke Session - diarahkan ke method 'prosesBooking'
Route::post('/booking/{id}', [SipkosController::class, 'storeBooking'])->name('booking.store');


// 5. Halaman Riwayat Sesi Booking Anda - diarahkan ke method 'myBookings'
Route::get('/my-bookings', [SipkosController::class, 'myBookings'])->name('my.bookings');

Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])->name('chatbot.ask');


// ==========================================
// RUTE DASHBOARD ADMIN (CRUD DATA KOS)
// ==========================================

Route::prefix('admin')->group(function () {
    // Tampilan Dashboard Admin
    Route::get('/', [SipkosController::class, 'admin'])->name('admin');
    
    // Proses Tambah Kos Baru
    Route::post('/kos', [SipkosController::class, 'store'])->name('kos.store');
    
    // Tampilan Form Edit Kos
    Route::get('/kos/{id}/edit', [SipkosController::class, 'edit'])->name('kos.edit');
    
    // Proses Update Data Kos
    Route::put('/kos/{id}', [SipkosController::class, 'update'])->name('kos.update');
    
    // Proses Hapus Data Kos
    Route::delete('/kos/{id}', [SipkosController::class, 'destroy'])->name('kos.destroy');

});