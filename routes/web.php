<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketTypeController; 
use App\Http\Controllers\CheckInController; 
use App\Http\Controllers\ReviewController; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; 

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // RUTE PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
    
    // -------------------------------------------------------------
    // RUTE EVENT (CRUD Lengkap)
    // -------------------------------------------------------------
    
    // Read (List) - PERBAIKAN: Middleware permission dihapus
    // Keamanan sekarang ditangani oleh logika di EventController::index
    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index'); 

    // Create (Form)
    Route::get('/events/create', [EventController::class, 'create'])
        ->name('events.create')
        ->middleware('permission:create_event'); 

    // Create (Logic)
    Route::post('/events', [EventController::class, 'store'])
        ->name('events.store')
        ->middleware('permission:create_event');
    
    // Read (Single) - PERBAIKAN: Middleware permission dihapus
    // Keamanan sekarang ditangani oleh EventPolicy & Gate::authorize('view', $event)
    Route::get('/events/{event}', [EventController::class, 'show'])
        ->name('events.show');

    // Update (Form)
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])
        ->name('events.edit'); 
    
    // Update (Logic)
    Route::patch('/events/{event}', [EventController::class, 'update'])
        ->name('events.update');
        
    // Delete (Logic)
    Route::delete('/events/{event}', [EventController::class, 'destroy'])
        ->name('events.destroy')
        ->middleware('permission:delete_event');
    
    // =============================================================
    // SISA ROUTE KE BAWAH TETAP SAMA
    // =============================================================
    
    // RUTE TIKET
    Route::resource('events.tickets', TicketTypeController::class)
            ->except(['show', 'create', 'edit'])
            ->middleware('permission:edit_event'); 

    // RUTE BOOKING
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/events/{event}/buy', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/checkout', [BookingController::class, 'showCheckout'])->name('bookings.checkout'); 
    Route::get('/bookings/{booking}/confirmation', [BookingController::class, 'showConfirmation'])->name('bookings.confirmation');
    Route::get('/bookings/{booking}', [BookingController::class, 'showTicketDetail'])->name('bookings.show');

    // RUTE CHECK-IN
    Route::prefix('events/{event}')->name('events.checkin.')->group(function () {
        Route::get('attendees', [CheckInController::class, 'index'])->name('index')->middleware('permission:edit_event');
        Route::get('scanner', [CheckInController::class, 'showScanner'])->name('scanner')->middleware('permission:edit_event');
        Route::post('check-in', [CheckInController::class, 'processCheckIn'])->name('process')->middleware('permission:edit_event');
    });

    // RUTE REVIEW
    Route::post('events/{event}/reviews', [ReviewController::class, 'store'])
        ->name('events.reviews.store')
        ->middleware('permission:review_event');

    // RUTE ADMIN
    Route::prefix('admin')->name('admin.')->middleware('permission:manage_users')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);

        // RUTE ADMIN REVIEWS
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    });

});

// MIDTRANS
Route::post('/midtrans/notification', [BookingController::class, 'notificationHandler'])
    ->name('midtrans.notification');


require __DIR__.'/auth.php';