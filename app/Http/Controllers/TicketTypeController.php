<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TicketTypeController extends Controller
{
    // Memastikan hanya organizer pemilik event yang bisa mengelola tiket
    public function __construct()
    {
        // Panggil middleware permission untuk melindungi semua aksi
        $this->middleware('permission:edit_event');
    }

    /**
     * Menampilkan daftar tipe tiket dan form tambah untuk event.
     */
    public function index(Event $event)
    {
        // Memastikan hanya organizer pemilik event yang bisa melihat
        if (Auth()->user()->id !== $event->organizer_id) {
            abort(403, 'Akses Ditolak. Anda bukan penyelenggara event ini.');
        }

        $ticketTypes = $event->ticketTypes()->orderBy('harga', 'asc')->get();

        return view('events.tickets.index', compact('event', 'ticketTypes'));
    }

    /**
     * Menyimpan Tipe Tiket baru.
     */
    public function store(Request $request, Event $event)
    {
        // Memastikan hanya organizer pemilik event yang bisa menyimpan
        if (Auth()->user()->id !== $event->organizer_id) {
            abort(403, 'Akses Ditolak.');
        }
        
        $validated = $request->validate([
            'nama_tiket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
        ]);

        $event->ticketTypes()->create($validated);

        return redirect()->route('events.tickets.index', $event)
                         ->with('success', 'Tipe tiket baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui Tipe Tiket yang sudah ada.
     */
    public function update(Request $request, Event $event, TicketType $ticket)
    {
        // Memastikan kepemilikan
        if (Auth()->user()->id !== $event->organizer_id) {
            abort(403, 'Akses Ditolak.');
        }

        $validated = $request->validate([
            'nama_tiket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kuota' => 'required|integer|min:1',
        ]);

        $ticket->update($validated);

        return redirect()->route('events.tickets.index', $event)
                         ->with('success', 'Tipe tiket berhasil diperbarui!');
    }

    /**
     * Menghapus Tipe Tiket.
     */
    public function destroy(Event $event, TicketType $ticket)
    {
        // Memastikan kepemilikan
        if (Auth()->user()->id !== $event->organizer_id) {
            abort(403, 'Akses Ditolak.');
        }
        
        $ticket->delete();

        return redirect()->route('events.tickets.index', $event)
                         ->with('success', 'Tipe tiket berhasil dihapus.');
    }
}