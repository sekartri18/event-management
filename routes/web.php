<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Events routes
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
});

require __DIR__.'/auth.php';
