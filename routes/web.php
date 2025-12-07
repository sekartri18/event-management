<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketTypeController; 
use App\Http\Controllers\CheckInController; 
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminParticipantController; // <--- DITAMBAHKAN: Controller Peserta Global
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 

// =================================================================
// ✅ RUTE WEBHOOK MIDTRANS (HARUS PUBLIK DAN DI LUAR MIDDLEWARE)
// =================================================================
// Penting: Ini ditaruh paling atas agar tidak terhalang login
Route::post('/midtrans/notification', [BookingController::class, 'notificationHandler'])
    ->name('midtrans.notification');


// -----------------------------------------------------------------
// RUTE UMUM (Welcome Page)
// -----------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (Butuh Login & Verifikasi Email)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =================================================================
// RUTE UTAMA (MEMBUTUHKAN LOGIN / AUTH)
// =================================================================
Route::middleware('auth')->group(function () {
    
    // -------------------------------------------------------------
    // RUTE PROFILE
    // -------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
    
    // -------------------------------------------------------------
    // RUTE EVENT (CRUD)
    // -------------------------------------------------------------
    
    // List Event (Akses diatur di Controller)
    Route::get('/events', [EventController::class, 'index'])->name('events.index'); 

    // Create (Form & Logic)
    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create')->middleware('permission:create_event'); 
    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store')->middleware('permission:create_event');
    
    // Detail Event
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Edit & Update
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit'); 
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
        
    // Delete
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->name('events.destroy')->middleware('permission:delete_event');
    
    // -------------------------------------------------------------
    // RUTE TIKET (MANAJEMEN TIPE TIKET OLEH ORGANIZER)
    // -------------------------------------------------------------
    Route::resource('events.tickets', TicketTypeController::class)
        ->except(['show', 'create', 'edit'])
        ->middleware('permission:edit_event'); 

    // -------------------------------------------------------------
    // RUTE BOOKING (PEMBELIAN TIKET)
    // -------------------------------------------------------------
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/events/{event}/buy', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/checkout', [BookingController::class, 'showCheckout'])->name('bookings.checkout'); 
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'showConfirmation'])->name('bookings.confirmation');
    Route::get('/bookings/{booking}', [BookingController::class, 'showTicketDetail'])->name('bookings.show');

    // -------------------------------------------------------------
    // RUTE CHECK-IN (SCANNER QR)
    // -------------------------------------------------------------
    Route::prefix('events/{event}')->name('events.checkin.')->group(function () {
        // 1. Daftar Peserta (Per Event)
        Route::get('attendees', [CheckInController::class, 'index']) 
            ->name('index')->middleware('permission:edit_event'); 

        // 2. Halaman Scanner
        Route::get('scanner', [CheckInController::class, 'showScanner']) 
            ->name('scanner')->middleware('permission:edit_event');

        // 3. Proses API Check-In
        Route::post('check-in', [CheckInController::class, 'processCheckIn']) 
            ->name('process')->middleware('permission:edit_event');
    });

    // -------------------------------------------------------------
    // RUTE REVIEW
    // -------------------------------------------------------------
    Route::post('events/{event}/reviews', [ReviewController::class, 'store'])
        ->name('events.reviews.store')->middleware('permission:review_event');

    // -------------------------------------------------------------
    // RUTE ADMIN AREA
    // -------------------------------------------------------------
    Route::prefix('admin')->name('admin.')->middleware('permission:manage_users')->group(function () {
        
        // Dashboard Admin
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Manajemen User
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

        // RUTE BARU: DATA PESERTA GLOBAL (SEMUA EVENT)
        // Menampilkan seluruh tiket/peserta dari semua event dalam satu tabel
        Route::get('/participants', [AdminParticipantController::class, 'index'])->name('participants.index');

        // Manajemen Review (Hapus review spam dll)
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    });

}); // <--- PENUTUP GRUP AUTH (PENTING!)

require __DIR__.'/auth.php';