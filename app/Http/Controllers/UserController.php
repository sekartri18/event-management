<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\Auth; // <<--- PASTI! Menggunakan Facades Auth untuk keamanan
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna (Admin Management).
     */
    public function index(): View
    {
        // Mengambil semua pengguna dengan paginasi
        $users = User::with('role')->orderBy('id', 'desc')->paginate(10); 
        
        return view('admin.users.index', compact('users'));
    }

    // Method edit()
    public function edit(User $user): View
    {
         // Ambil semua role untuk dropdown di form edit
         $roles = Role::all(); 
         return view('admin.users.edit', compact('user', 'roles'));
    }
    
    // ===============================================
    // 1. UPDATE USER LOGIC
    // ===============================================
    public function update(Request $request, User $user): RedirectResponse
    {
         // Ambil ID user yang sedang login sekali saja
        $loggedInUserId = Auth::id();

        // Pencegahan: Admin tidak mengedit role/akun dirinya sendiri (Opsional, tapi bagus untuk mencegah kesalahan)
         if ($user->isAdmin() && $user->id === $loggedInUserId) {
             return redirect()->route('admin.users.index')->with('error', 'Anda tidak bisa mengedit role/akun Anda sendiri melalui panel ini.');
         }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role_id' => ['required', 'exists:roles,id'], 
            'password' => 'nullable|confirmed|min:8',
        ]);
        
        $dataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
        ];

        if (!empty($validated['password'])) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }
        
        $user->update($dataToUpdate);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    // ===============================================
    // 2. DELETE USER LOGIC
    // ===============================================
    public function destroy(User $user): RedirectResponse
    {
        // Pencegahan: Admin tidak boleh menghapus dirinya sendiri
        // PERBAIKAN: Menggunakan Auth::id() secara langsung
        if ($user->id === Auth::id()) { 
             return back()->with('error', 'Admin tidak dapat menghapus akunnya sendiri melalui panel ini.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ' . $userName . ' berhasil dihapus.');
    }
}