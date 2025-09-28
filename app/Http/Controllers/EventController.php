<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    // List semua event (untuk attendee dan organizer)
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    // Form untuk buat event (hanya organizer)
    public function create()
    {
        return view('events.create');
    }

    // Simpan event baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        Event::create([
            'organizer_id' => auth()->id(), // organizer yg login
            'nama_event' => $request->nama_event,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'lokasi' => $request->lokasi,
            'status' => $request->status,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    // Tampilkan detail event
    public function show(Event $event)
    {
        Gate::authorize('view', $event);
        return view('events.show', compact('event'));
    }

    // Update event
    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
        ]);

        $event->update($request->only([
            'nama_event',
            'tanggal_mulai',
            'tanggal_selesai',
            'lokasi',
            'status',
            'deskripsi'
        ]));

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Hapus event
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }
}
