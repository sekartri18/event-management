<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request, including role-based redirect.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ambil objek pengguna yang baru login
        $user = $request->user();

        // ===============================================
        // LOGIKA REDIRECT BERDASARKAN ROLE
        // ===============================================

        // 1. Cek Admin sebagai prioritas tertinggi
        if ($user->isAdmin()) {
            // Arahkan System Administrator ke dashboard khusus Admin
            return redirect()->intended(route('admin.dashboard')); 
        }

        // 2. Cek Organizer
        if ($user->isOrganizer()) {
            // Arahkan Event Organizer ke halaman Event mereka
            return redirect()->intended(route('events.index')); 
        } 
        
        // 3. Cek Attendee
        if ($user->isAttendee()) {
            // Arahkan Event Attendee ke dashboard umum
            return redirect()->intended(route('dashboard'));
        }

        // Redirect Default (Fallback)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
