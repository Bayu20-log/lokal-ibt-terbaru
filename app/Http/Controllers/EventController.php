<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\event;

class EventController extends Controller
{
     public function event()
    {
        $routing = "berita"; //untuk hover admin 
        $data = event::all(); 
        Session::put('halaman_url', request()->fullUrl());
        return view('admin.event', compact('routing', 'data'));
    }

    
     public function tambahevent()
    { 
        return view('admin.tambahevent');
    }


public function insertevent(Request $request)
{
    // Validasi form input
  $request->validate([
    'judulevent' => 'required|string',
    'deskripsievent' => 'required|string|max:100', 
    'linkevent' => 'required|url',
    'tanggalevent' => 'required|date',
    'lokasievent' => 'required|string',
    'pukulevent' => 'required|string',
    'gambarevent' => 'required|image|mimes:jpg,jpeg,png|max:5120',
], [
    'judulevent.required' => 'Judul event harus diisi.',
    'deskripsievent.required' => 'Deskripsi event harus diisi.',
    'deskripsievent.max' => 'Deskripsi event maksimal 100 karakter.', 
    'linkevent.required' => 'Link event harus diisi.',
    'linkevent.url' => 'Link event harus berupa URL yang valid.',
    'tanggalevent.required' => 'Tanggal event harus diisi.',
    'lokasievent.required' => 'Lokasi event harus diisi.',
    'pukulevent.required' => 'Waktu event harus diisi.',
    'gambarevent.required' => 'Gambar event harus diunggah.',
    'gambarevent.image' => 'Gambar harus berupa file gambar.',
    'gambarevent.mimes' => 'Gambar hanya boleh berformat JPG atau PNG.',
    'gambarevent.max' => 'Ukuran gambar maksimal 5MB.',
]);

    // Ambil file gambar
    $file = $request->file('gambarevent');
    $filename = $file->hashName(); // Nama file unik

    // Validasi mime type tambahan
    $mimeType = mime_content_type($file->getPathname());
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
        return redirect()->back()->withErrors(['gambarevent' => 'Format gambar tidak valid.']);
    }

    // Simpan ke storage/app/public/event
    $file->storeAs('event', $filename, 'public');

    // Simpan data ke database
    $event = new event();
    $event->judulevent = $request->input('judulevent');
    $event->deskripsievent = $request->input('deskripsievent');
    $event->linkevent = $request->input('linkevent');
    $event->tanggalevent = $request->input('tanggalevent');
    $event->lokasievent = $request->input('lokasievent');
    $event->pukulevent = $request->input('pukulevent');
    $event->gambarevent = $filename;

    $event->save();

    return redirect()->route('event')->with('success', 'Event berhasil ditambahkan.');
}


public function tampilevent($id)
    {
        {  
            $data = event::find($id);
            return view('admin.tampilevent', compact('data'));
        }
        
    }


    public function updateevent(Request $request, $id)
{
    // Validasi input dari form
   $request->validate([
    'gambarevent' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'judulevent' => 'required|string',
    'deskripsievent' => 'required|string|max:100', 
    'linkevent' => 'required|url',
    'tanggalevent' => 'required|date',
    'lokasievent' => 'required|string',
    'pukulevent' => 'required|string',
], [
    'gambarevent.image' => 'Gambar event harus berupa file JPG, JPEG, atau PNG.',
    'gambarevent.mimes' => 'Gambar event harus berupa file JPG, JPEG, atau PNG.',
    'gambarevent.max' => 'Ukuran gambar maksimal adalah 5MB.',
    'judulevent.required' => 'Judul event harus diisi.',
    'deskripsievent.required' => 'Deskripsi event harus diisi.',
    'deskripsievent.max' => 'Deskripsi event maksimal 100 karakter.', 
    'linkevent.required' => 'Link event harus diisi.',
    'linkevent.url' => 'Link event harus berupa URL yang valid.',
    'tanggalevent.required' => 'Tanggal event harus diisi.',
    'lokasievent.required' => 'Lokasi event harus diisi.',
    'pukulevent.required' => 'Waktu event harus diisi.',
]);

    // Ambil data event berdasarkan ID
    $event = event::find($id);

    if (!$event) {
        return redirect()->route('event')->with('error', 'Data event tidak ditemukan.');
    }

    // Cek dan proses file gambar jika ada
    if ($request->hasFile('gambarevent')) {
        $file = $request->file('gambarevent');

        // Validasi mime type manual tambahan
        $mimeType = mime_content_type($file->getPathname());
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
            return redirect()->back()->withErrors(['gambarevent' => 'Format gambar tidak valid.']);
        }

        // Hapus gambar lama jika ada
        if ($event->gambarevent && Storage::exists('public/event/' . $event->gambarevent)) {
            Storage::delete('public/event/' . $event->gambarevent);
        }

        // Simpan gambar baru
        $filename = $file->hashName(); // nama unik
        $file->storeAs('event', $filename, 'public');
        $event->gambarevent = $filename;
    }

    // Update data lainnya
    $event->judulevent = $request->input('judulevent');
    $event->deskripsievent = $request->input('deskripsievent');
    $event->linkevent = $request->input('linkevent');
    $event->tanggalevent = $request->input('tanggalevent');
    $event->lokasievent = $request->input('lokasievent');
    $event->pukulevent = $request->input('pukulevent');

    $event->save();

    return redirect()->route('event')->with('success', 'Event berhasil diperbarui.');
}

public function deleteevent($id)
{
    // Temukan event berdasarkan ID
    $event = event::find($id);

    if (!$event) {
        return redirect()->route('event')->with('error', 'Data event tidak ditemukan');
    }

    // Hapus file gambar dari storage jika ada
    $filePath = storage_path('app/public/event/' . $event->gambarevent);
    if ($event->gambarevent && file_exists($filePath)) {
        unlink($filePath); // Hapus gambar dari sistem file
    }

    // Hapus data dari database
    $event->delete();

    // Redirect kembali ke halaman sebelumnya jika ada
    if (session('halaman_url')) {
        return redirect(session('halaman_url'))->with('success', 'Event berhasil dihapus');
    }

    // Default redirect ke route event
    return redirect()->route('event')->with('success', 'Event berhasil dihapus');
}

}
