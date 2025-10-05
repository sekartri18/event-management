<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; // <<< BARU: Pastikan Controller ini diimpor!
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
    
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
        ->name('events.edit')
        ->middleware('permission:update_event'); // Update form
    
    Route::patch('/events/{event}', [EventController::class, 'update'])
        ->name('events.update')
        ->middleware('permission:update_event'); // Update logic
        
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->name('events.destroy')
        ->middleware('permission:delete_event'); // Delete logic
        
    // Catatan: show route tidak perlu di-override, akan menggunakan permission default 'view_event'
    

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