<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    /**
     * Method BEFORE: Memberikan Admin Utama (System Admin) akses penuh (Bypass Check).
     * Method ini dieksekusi sebelum semua method policy lainnya.
     * @param \App\Models\User $user
     * @param string $ability
     * @return bool|null
     */
    public function before(User $user, string $ability): ?bool
    {
        // Pengecekan role Admin menggunakan helper isAdmin() dari User Model
        if ($user->isAdmin()) { 
            return true; // Jika Admin, lewati semua pengecekan policy di bawah
        }

        return null; // Lanjutkan ke method Policy yang spesifik
    }

    // Policy view: Tidak perlu diubah, karena Admin sudah di-bypass di before()
    public function view(User $user, Event $event): bool
    {
        // Organizer yang membuat event bisa melihat eventnya
        if ($user->isOrganizer() && $user->id === $event->organizer_id) {
            return true; 
        }
        // Attendee bisa melihat semua event
        return $user->hasPermission('view_event'); 
    }

    // Policy update: Hanya Organizer pemilik event
    public function update(User $user, Event $event): bool
    {
        // Admin sudah di-bypass oleh before()
        return $user->id === $event->organizer_id 
            && $user->hasPermission('edit_event');
    }

    // Policy delete: Hanya Organizer pemilik event
    public function delete(User $user, Event $event): bool
    {
        // Admin sudah di-bypass oleh before()
        return $user->id === $event->organizer_id 
            && $user->hasPermission('delete_event');
    }
}