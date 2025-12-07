<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) { 
            return true; 
        }
        return null;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create_event');
    }

    /**
     * PERBAIKAN DI SINI:
     * Kita gunakan strtolower() agar tidak peduli huruf besar/kecil.
     */
    public function view(User $user, Event $event): bool
    {
        // 1. Organizer Pemilik Event: Selalu boleh
        if ($user->isOrganizer() && $user->id === $event->organizer_id) {
            return true; 
        }

        // 2. Attendee / Public:
        // Ubah status jadi huruf kecil semua sebelum dicek
        $status = strtolower($event->status); 
        
        // Cek apakah status ada di daftar yang diperbolehkan
        return in_array($status, ['upcoming', 'ongoing', 'finished']);
    }

    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id && $user->hasPermission('edit_event');
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id && $user->hasPermission('delete_event');
    }
}