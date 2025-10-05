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
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // ===============================================
        // LOGIKA REDIRECT BERDASARKAN ROLE
        // ===============================================

        // 1. Redirect Admin ke Admin Panel Dashboard
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard')); 
        }

        // 2. Redirect Organizer ke Event Management Index (Fokus utama Organizer)
        if ($user->isOrganizer()) {
            return redirect()->intended(route('events.index')); 
        } 
        
        // 3. Redirect Attendee ke Dashboard Umum (Fokus utama Attendee)
        if ($user->isAttendee()) {
            return redirect()->intended(route('dashboard'));
        }

        // Redirect Default (Fallback jika role tidak terdefinisi)
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