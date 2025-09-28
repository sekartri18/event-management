<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    // Organizer boleh melihat event yang dia buat
    // Attendee bisa lihat semua event
    public function view(User $user, Event $event): bool
    {
        if ($user->hasPermission('view_all_events')) {
            return true; // organizer role
        }
        return $user->hasPermission('view_event');
    }

    // Hanya organizer yang membuat event bisa mengupdate
    public function update(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id 
            && $user->hasPermission('edit_event');
    }

    // Hanya organizer yang membuat event bisa menghapus
    public function delete(User $user, Event $event): bool
    {
        return $user->id === $event->organizer_id 
            && $user->hasPermission('delete_event');
    }
}
