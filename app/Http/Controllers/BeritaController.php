<?php

namespace App\Http\Controllers;
use App\Models\berita;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
     public function berita()
    {
        $routing = "berita"; //untuk hover admin 
        $data = berita::all(); 
        Session::put('halaman_url', request()->fullUrl());
        return view('admin.berita', compact('routing', 'data'));
    }

    public function tambahberita()
    { 
        return view('admin.tambahberita');
    }

    public function insertberita(Request $request)
{
    // Validasi form input
    $request->validate([
        'judulberita' => 'required|string',
        'deskripsiberita' => 'required|string',
        'tanggalberita' => 'required|date',
        'gambarberita' => 'required|image|mimes:jpg,jpeg,png|max:5120',
    ], [
        'judulberita.required' => 'Judul berita harus diisi.',
        'deskripsiberita.required' => 'Deskripsi berita harus diisi.',
        'tanggalberita.required' => 'Tanggal berita harus diisi.',
        'gambarberita.required' => 'Gambar berita harus diunggah.',
        'gambarberita.image' => 'Gambar harus berupa file gambar.',
        'gambarberita.mimes' => 'Gambar hanya boleh JPG atau PNG.',
        'gambarberita.max' => 'Ukuran gambar maksimal 5MB.',
    ]);

    // Ambil file gambar
    $file = $request->file('gambarberita');
    $filename = $file->hashName(); // Nama unik

    // Validasi ulang mime type (keamanan tambahan)
    $mimeType = mime_content_type($file->getPathname());
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
        return redirect()->back()->withErrors(['gambarberita' => 'Format gambar tidak valid.']);
    }

    // Simpan ke folder storage/app/public/berita
    $file->storeAs('berita', $filename, 'public');

    // Simpan ke database
    $berita = new \App\Models\berita(); // Ganti jika nama model berbeda
    $berita->judulberita = $request->input('judulberita');
    $berita->deskripsiberita = $request->input('deskripsiberita');
    $berita->tanggalberita = $request->input('tanggalberita');
    $berita->gambarberita = $filename;

    $berita->save();

    return redirect()->route('berita')->with('success', 'Berita berhasil ditambahkan.');
}

public function tampilberita($id)
    {
        {  
            $data = berita::find($id);
            return view('admin.tampilberita', compact('data'));
        }
        
    }

    public function updateberita(Request $request, $id)
{
    // Validasi input dari form
    $request->validate([
        'gambarberita' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        'judulberita' => 'required|string',
        'deskripsiberita' => 'required|string',
        'tanggalberita' => 'required|date',
    ], [
        'gambarberita.image' => 'Gambar berita harus berupa file JPG, JPEG, atau PNG.',
        'gambarberita.mimes' => 'Gambar berita harus berupa file JPG, JPEG, atau PNG.',
        'gambarberita.max' => 'Ukuran gambar berita maksimal adalah 5MB.',
        'judulberita.required' => 'Judul berita harus diisi.',
        'deskripsiberita.required' => 'Deskripsi berita harus diisi.',
        'tanggalberita.required' => 'Tanggal berita harus diisi.',
        'tanggalberita.date' => 'Format tanggal tidak valid.',
    ]);

    // Ambil data berita berdasarkan ID
    $data = berita::find($id);

    if (!$data) {
        return redirect()->route('berita')->with('error', 'Data berita tidak ditemukan');
    }

    // Cek dan proses file gambar jika ada
    if ($request->hasFile('gambarberita')) {
        $file = $request->file('gambarberita');
        $filename = $file->getClientOriginalName();

        // Verifikasi mime type
        $mimeType = mime_content_type($file->getPathname());
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
            return redirect()->back()->withErrors(['gambarberita' => 'Format gambar tidak valid.']);
        }

        // Hapus gambar lama jika ada
        if ($data->gambarberita && Storage::exists('public/berita/' . $data->gambarberita)) {
            Storage::delete('public/berita/' . $data->gambarberita);
        }

        // Simpan gambar baru ke storage
        $filename = $file->hashName(); // Untuk nama file unik
        $file->storeAs('berita', $filename, 'public');
        $data->gambarberita = $filename;
    }

    // Update kolom lain
    $data->judulberita = $request->input('judulberita');
    $data->deskripsiberita = $request->input('deskripsiberita');
    $data->tanggalberita = $request->input('tanggalberita');

    $data->save();

    return redirect()->route('berita')->with('success', 'Berita berhasil diperbarui.');
}

public function deleteberita($id)
{
    // Temukan berita berdasarkan ID
    $berita = berita::find($id);

    if (!$berita) {
        return redirect()->route('berita')->with('error', 'Data berita tidak ditemukan');
    }

    // Hapus file gambar dari storage jika ada
    $filePath = storage_path('app/public/berita/' . $berita->gambarberita);
    if ($berita->gambarberita && file_exists($filePath)) {
        unlink($filePath); // Hapus gambar dari sistem file
    }

    // Hapus data dari database
    $berita->delete();

    if (session('halaman_url')) {
        return redirect(session('halaman_url'))->with('success', 'Berita berhasil dihapus');
    }

    return redirect()->route('berita')->with('success', 'Berita berhasil dihapus');
}

}
