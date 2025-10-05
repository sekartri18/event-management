<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Event;
use App\Models\Review;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk System Administrator.
     * Mengambil data statistik penting dari database.
     */
    public function index(): View
    {
        // Mengambil data statistik aktual dari database
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalReviews = Review::count();

        // Mengirimkan data statistik ke view
        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalEvents' => $totalEvents,
            'totalReviews' => $totalReviews,
        ]);
    }

    // Anda bisa menambahkan method lain di sini
}