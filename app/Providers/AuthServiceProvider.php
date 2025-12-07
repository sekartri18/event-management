<?php

namespace App\Providers;

// Tambahkan Gate Facade (Opsional tapi sering dibutuhkan di boot)
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Event;
use App\Policies\EventPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // =================================================================
        // !! PERBAIKAN PENTING DI SINI !!
        // =================================================================
        // Anda WAJIB memanggil fungsi ini agar array $policies di atas terbaca.
        $this->registerPolicies();

        // (Opsional) Jika Anda ingin mendefinisikan Gate lain, bisa di sini
    }
}