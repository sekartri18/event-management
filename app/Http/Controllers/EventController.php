<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 
// Tidak perlu 'use Illuminate\Support\Collection;' karena kita pakai helper collect()

class EventController extends Controller
{
    // List semua event (untuk attendee, organizer, dan admin)
    public function index()
    {
        // 1. Ambil user yang sedang login
        $user = Auth::user(); 
        
        // 2. Inisialisasi $events menggunakan helper function collect()
        $events = collect(); 
        
        // 3. Logika pemfilteran event 
        if ($user) {
            if ($user->isOrganizer()) {
                // Organizer hanya melihat event yang dia buat
                // PASTIKAN: Menggunakan kolom 'organizer_id'
                $events = Event::where('organizer_id', $user->id)
                                ->orderBy('tanggal_mulai', 'asc')
                                ->get();
            } else {
                // Admin dan Attendee melihat semua event
                $events = Event::orderBy('tanggal_mulai', 'asc')->get();
            }
        } 
        
        // 4. Kirim variabel $events ke view
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
        // 1. Validasi Input
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
        
        $imagePath = null;
        
        // 2. Handle File Upload Gambar
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('events', 'public');
        }

        // 3. Simpan Event ke Database
        $organizerId = Auth::id(); 
        Event::create([
            'organizer_id' => $organizerId, 
            'nama_event' => $validated['nama_event'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'lokasi' => $validated['lokasi'],
            'status' => $validated['status'],
            'deskripsi' => $validated['deskripsi'],
            'gambar' => $imagePath,
        ]);

        // Setelah berhasil, redirect ke index, tombol 'Buat Event Baru' akan tetap ada di header
        return redirect()->route('events.index')->with('success', 'Event berhasil dibuat!');
    }

    // Tampilkan detail event
    public function show(Event $event)
    {
        Gate::authorize('view', $event); 
        return view('events.show', compact('event'));
    }

    // Form untuk edit event
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    // Update event
    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        // 1. Validasi Input
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'status' => 'required|string',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
        
        $dataToUpdate = $validated;
        
        // 2. Handle File Upload Gambar (Jika ada file baru)
        if ($request->hasFile('gambar')) {
            if ($event->gambar) {
                Storage::disk('public')->delete($event->gambar);
            }
            $dataToUpdate['gambar'] = $request->file('gambar')->store('events', 'public');
        }

        // 3. Update Event
        $event->update($dataToUpdate);

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // Hapus event
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);
        
        // Hapus file gambar dari storage sebelum menghapus record
        if ($event->gambar) {
            Storage::disk('public')->delete($event->gambar);
        }
        
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }
}