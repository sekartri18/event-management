<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk System Administrator.
     * Rute ini dilindungi oleh middleware 'permission:manage_users'
     * dan merupakan halaman tujuan redirect setelah Admin login.
     */
    public function index(): View
    {
        // Pastikan file view ini ada di: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard');
    }

    // Anda bisa menambahkan method lain di sini, seperti:
    
    // public function manageUsers(): View 
    // {
    //     // Logika untuk menampilkan daftar dan mengelola pengguna
    //     return view('admin.users.index');
    // }

    // public function manageEvents(): View
    // {
    //     // Logika untuk meninjau semua event di sistem
    //     return view('admin.events.index');
    // }
}
