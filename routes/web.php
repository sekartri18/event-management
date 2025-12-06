<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketTypeController; 
use App\Http\Controllers\CheckInController; // Controller Check-in Anda
use App\Http\Controllers\ReviewController; // <-- DITAMBAHKAN: Import Controller Review
use Illuminate\Support\Facades\Route;

// Rute Halaman Utama (Welcome Page)
Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard (Akses setelah Login dan Verifikasi Email)
Route::get('/dashboard', function () {
    // Tentukan ke mana user akan diarahkan setelah login (Dashboard Umum)
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// =================================================================
// RUTE UTAMA (Membutuhkan Autentikasi)
// =================================================================
Route::middleware('auth')->group(function () {
    // -------------------------------------------------------------
    // RUTE PROFILE
    // -------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
    
    // -------------------------------------------------------------
    // RUTE EVENT (CRUD Lengkap - Telah Dirapikan)
    // -------------------------------------------------------------
    
    // Read (List)
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index')
        ->middleware('permission:view_event'); 

    // Create (Form)
    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create')
        ->middleware('permission:create_event'); 

    // Create (Logic)
    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store')
        ->middleware('permission:create_event');
    
    // Read (Single)
    Route::get('/events/{event}', [EventController::class, 'show'])
        ->name('events.show')
        ->middleware('permission:view_event');

    // Update (Form) - Otorisasi via Policy
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
        ->name('events.edit'); 
    
    // Update (Logic) - Otorisasi via Policy
    Route::patch('/events/{event}', [EventController::class, 'update'])
        ->name('events.update');
        
    // Delete (Logic)
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->name('events.destroy')
        ->middleware('permission:delete_event');
    
    // =============================================================
    // RUTE PENGATURAN TIPE TIKET (NESTED RESOURCE)
    // =============================================================
    Route::resource('events.tickets', TicketTypeController::class)
            ->except(['show', 'create', 'edit'])
            ->middleware('permission:edit_event'); 

    // -------------------------------------------------------------
    // RUTE BOOKING (Untuk Attendee)
    // -------------------------------------------------------------
    
    // Daftar riwayat booking attendee
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    // 1. Proses Initial Booking (dari form Beli Sekarang)
    Route::post('/events/{event}/buy', [BookingController::class, 'store'])->name('bookings.store');

    // 2. Halaman Checkout/Pilih Pembayaran (GET)
    Route::get('/bookings/{booking}/checkout', [BookingController::class, 'showCheckout'])->name('bookings.checkout'); 

    // 3. Konfirmasi/Tiket Jadi (Setelah Pembayaran Sukses)
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'showConfirmation'])->name('bookings.confirmation');

    // =============================================================
    // RUTE CHECK-IN EVENT (FOR ORGANIZER)
    // =============================================================
    Route::prefix('events/{event}')->name('events.checkin.')->group(function () {
        
        // 1. Daftar Peserta (List Tickets)
        Route::get('attendees', [CheckInController::class, 'index'])
            ->name('index')
            ->middleware('permission:edit_event'); // Menggunakan permission edit_event

        // 2. Halaman Scanner (Form Input QR Code)
        Route::get('scanner', [CheckInController::class, 'showScanner'])
            ->name('scanner')
            ->middleware('permission:edit_event');

        // 3. Proses Check-In (API Endpoint)
        Route::post('check-in', [CheckInController::class, 'processCheckIn'])
            ->name('process')
            ->middleware('permission:edit_event');
    });

    // =============================================================
    // RUTE REVIEWS EVENT (FOR ATTENDEE - DIPERBAIKI)
    // =============================================================
    Route::post('events/{event}/reviews', [ReviewController::class, 'store'])
        ->name('events.reviews.store') // <-- Nama diubah agar konsisten
        ->middleware('permission:review_event'); // <-- Middleware keamanan ditambahkan
    // =============================================================

    // -------------------------------------------------------------
    // RUTE ADMIN AREA (DILINDUNGI OLEH PERMISSION ADMIN)
    // -------------------------------------------------------------
    // Hanya pengguna dengan permission:manage_users (yaitu Admin) yang bisa mengakses.
    Route::prefix('admin')->name('admin.')->middleware('permission:manage_users')->group(function () {
        
        // Admin Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // RUTE BARU: Manajemen Pengguna (Menggunakan UserController)
        // Kita hanya mengizinkan aksi index, edit, update, dan destroy.
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

    Route::post('/midtrans/notification', [BookingController::class, 'notificationHandler'])->name('midtrans.notification');
        
    });

});

require __DIR__.'/auth.php';