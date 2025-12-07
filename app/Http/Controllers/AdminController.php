<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Event;
use App\Models\Review;
use App\Models\Booking; // <--- JANGAN LUPA IMPORT INI

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk System Administrator.
     */
    public function index(): View
    {
        // Data Statistik Umum
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalReviews = Review::count();

        // Data Keuangan (Hanya hitung yang statusnya 'paid')
        // 1. Total Dana Masuk (Gross) - Uang tiket + Fee
        $totalGrossRevenue = Booking::where('status_pembayaran', 'paid')->sum('total_amount');

        // 2. Total Pendapatan Bersih (Net) - Hanya Admin Fee
        $totalNetRevenue = Booking::where('status_pembayaran', 'paid')->sum('admin_fee');

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalEvents' => $totalEvents,
            'totalReviews' => $totalReviews,
            'totalGrossRevenue' => $totalGrossRevenue, // Kirim ke view
            'totalNetRevenue' => $totalNetRevenue,     // Kirim ke view
        ]);
    }
}