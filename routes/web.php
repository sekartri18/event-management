<?php

use Illuminate\Support\Facades\Route;

use App\Models\Organizer;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Booking;
use App\Models\TicketType;
use App\Models\Ticket;
use App\Models\Review;
use App\Models\CalendarIntegration;

// route default untuk welcome page
Route::get('/', function () {
    return view('welcome');
});

// route ke dashboard
Route::get('/dashboard', function () {
    return view('dashboard', [
        'organizers' => Organizer::all(),
        'attendees' => Attendee::all(),
        'events' => Event::all(),
        'bookings' => Booking::all(),
        'ticketTypes' => TicketType::all(),
        'tickets' => Ticket::all(),
        'reviews' => Review::all(),
        'calendarIntegrations' => CalendarIntegration::all(),
    ]);
});
