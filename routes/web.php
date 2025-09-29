<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController; // Tambahkan Controller Admin
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard hanya untuk user login + verified
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group middleware untuk user login
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.delete'); // Ubah name agar konsisten, biasanya 'profile.delete' atau 'profile.destroy'

    // Events routes (Dilindungi oleh permission)
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index')
        ->middleware('permission:view_event');

    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create')
        ->middleware('permission:create_event');

    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store')
        ->middleware('permission:create_event');

    Route::resource('events', EventController::class)
        ->except(['index', 'create', 'store']);

    // =================================================================
    // RUTE ADMIN (DILINDUNGI OLEH PERMISSION ADMIN)
    // Hanya pengguna dengan permission:manage_users (yaitu Admin) yang bisa mengakses.
    // =================================================================
    Route::prefix('admin')->middleware('permission:manage_users')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        // Tambahkan rute Admin lainnya di sini (misalnya untuk User Management, Role Management, dll.)
    });
});

require __DIR__.'/auth.php';
