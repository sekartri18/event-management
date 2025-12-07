<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Pastikan Facade Gate di-import
use App\Models\Event;                // Model Event
use App\Policies\EventPolicy;        // Policy Event

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Kita daftarkan Policy secara MANUAL di sini agar PASTI terbaca
        Gate::policy(Event::class, EventPolicy::class);
    }
}