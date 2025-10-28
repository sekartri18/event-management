<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketTypeController; 
use App\Http\Controllers\CheckInController; // <--- BARU: IMPORT CONTROLLER CHECK-IN
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
    // RUTE EVENT (CRUD Lengkap)
    // -------------------------------------------------------------
    
    /* * Menggunakan Route::resource untuk mendaftarkan semua rute CRUD:
     * - index, show, create, store, edit, update, destroy.
     * Kita menerapkan middleware permission ke masing-masing aksi di bawah ini.
     */
    Route::resource('events', EventController::class)->middleware([
        // Memastikan hanya user dengan role/permission yang tepat yang bisa mengakses action
        'permission:view_event' // Default permission untuk semua aksi yang tidak di-override
    ]);

    // Override untuk aksi-aksi spesifik yang membutuhkan permission berbeda
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index')
        ->middleware('permission:view_event'); // Read/List

    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create')
        ->middleware('permission:create_event'); // Create form

    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store')
        ->middleware('permission:create_event'); // Create logic
    
    // Otorisasi Update event akan di handle oleh EventPolicy (owner + permission)
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
        ->name('events.edit'); 
    
    Route::patch('/events/{event}', [EventController::class, 'update'])
        ->name('events.update');
        
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->name('events.destroy')
        ->middleware('permission:delete_event'); // Delete logic
        
    // Catatan: show route tidak perlu di-override, akan menggunakan permission default 'view_event'
    
    // =============================================================
    // RUTE YANG DIPERBAIKI: PENGATURAN TIPE TIKET (NESTED RESOURCE)
    // =============================================================
    Route::resource('events.tickets', TicketTypeController::class)
           ->except(['show', 'create', 'edit'])
           ->middleware('permission:edit_event'); 

    // Rute Booking 
    // Daftar riwayat booking attendee
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    
    // 1. Proses Initial Booking (dari form Beli Sekarang)
    Route::post('/events/{event}/buy', [BookingController::class, 'store'])->name('bookings.store');

    // 2. Halaman Checkout/Pilih Pembayaran (GET)
    Route::get('/bookings/{booking}/checkout', [BookingController::class, 'showCheckout'])->name('bookings.checkout'); 

    // 3. Proses Pembayaran (Simulasi mengubah status booking) (POST)
    Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay'); 

    // 4. Konfirmasi/Tiket Jadi (Setelah Pembayaran Sukses)
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'showConfirmation'])->name('bookings.confirmation');

    // =============================================================
    // RUTE BARU: CHECK-IN EVENT & DAFTAR PESERTA (FOR ORGANIZER) <--- RUTE BARU DITAMBAHKAN DI SINI
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
        
    });

});

require __DIR__.'/auth.php';