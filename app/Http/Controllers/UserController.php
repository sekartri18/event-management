<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna (Admin Management).
     */
    public function index(): View
    {
        // Mengambil semua pengguna (kecuali Admin jika tidak diperlukan)
        $users = User::with('role')->orderBy('id', 'desc')->paginate(10); 
        
        // Mengirimkan data pengguna ke view
        return view('admin.users.index', compact('users'));
    }


    public function edit(User $user): View
    {
         return view('admin.users.edit', compact('user'));
    }

}