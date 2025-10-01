<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
     public function partner()
    {
        $routing = "partner"; //untuk hover admin 
        $data = partner::all(); 
        Session::put('halaman_url', request()->fullUrl());
        return view('admin.partner', compact('routing', 'data'));
    }

      public function tambahpartner()
    { 
        return view('admin.tambahpartner');
    }

    public function insertpartner(Request $request)
{
    // Validasi form input
   $request->validate([
    'namapartner' => 'required|string',
    'jenispartner' => 'required|string',
    'deskripsipartner' => 'required|string|max:110',
    'gambarpartner' => 'required|image|mimes:jpg,jpeg,png|max:5120',
], [
    'namapartner.required' => 'Nama partner harus diisi.',
    'jenispartner.required' => 'Jenis partner harus diisi.',
    'deskripsipartner.required' => 'Deskripsi partner harus diisi.',
    'deskripsipartner.max' => 'Deskripsi partner maksimal 110 karakter.',
    'gambarpartner.required' => 'Gambar partner harus diunggah.',
    'gambarpartner.image' => 'Gambar harus berupa file gambar.',
    'gambarpartner.mimes' => 'Gambar hanya boleh JPG atau PNG.',
    'gambarpartner.max' => 'Ukuran gambar maksimal 5MB.',
]);


    // Ambil file gambar
    $file = $request->file('gambarpartner');
    $filename = $file->hashName(); // Nama unik

    // Validasi ulang mime type (keamanan tambahan)
    $mimeType = mime_content_type($file->getPathname());
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
        return redirect()->back()->withErrors(['gambarpartner' => 'Format gambar tidak valid.']);
    }

    // Simpan ke folder storage/app/public/partner
    $file->storeAs('partner', $filename, 'public');

    // Simpan ke database
    $partner = new \App\Models\partner(); // Sesuaikan namespace jika perlu
    $partner->namapartner = $request->input('namapartner');
    $partner->jenispartner = $request->input('jenispartner');
    $partner->deskripsipartner = $request->input('deskripsipartner');
    $partner->gambarpartner = $filename;

    $partner->save();

    return redirect()->route('partner')->with('success', 'Sponsor berhasil ditambahkan.');
}

public function tampilpartner($id)
    {
        {  
            $data = partner::find($id);
            return view('admin.tampilpartner', compact('data'));
        }
        
    }

    public function updatepartner(Request $request, $id)
{
    // Validasi input dari form
   $request->validate([
    'gambarpartner' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'namapartner' => 'required|string',
    'jenispartner' => 'required|string',
    'deskripsipartner' => 'required|string|max:100',
], [
    'gambarpartner.image' => 'Gambar partner harus berupa file JPG, JPEG, atau PNG.',
    'gambarpartner.mimes' => 'Gambar partner harus berupa file JPG, JPEG, atau PNG.',
    'gambarpartner.max' => 'Ukuran gambar partner maksimal adalah 5MB.',
    'namapartner.required' => 'Nama partner harus diisi.',
    'jenispartner.required' => 'Jenis partner harus diisi.',
    'deskripsipartner.required' => 'Deskripsi partner harus diisi.',
    'deskripsipartner.max' => 'Deskripsi partner maksimal 110 karakter.',
]);


    // Ambil data partner berdasarkan ID
    $data = \App\Models\partner::find($id);

    if (!$data) {
        return redirect()->route('partner')->with('error', 'Data partner tidak ditemukan');
    }

    // Cek dan proses file gambar jika ada
    if ($request->hasFile('gambarpartner')) {
        $file = $request->file('gambarpartner');

        // Verifikasi mime type
        $mimeType = mime_content_type($file->getPathname());
        if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
            return redirect()->back()->withErrors(['gambarpartner' => 'Format gambar tidak valid.']);
        }

        // Hapus gambar lama jika ada
        if ($data->gambarpartner && Storage::exists('public/partner/' . $data->gambarpartner)) {
            Storage::delete('public/partner/' . $data->gambarpartner);
        }

        // Simpan gambar baru
        $filename = $file->hashName(); // nama file unik
        $file->storeAs('partner', $filename, 'public');
        $data->gambarpartner = $filename;
    }

    // Update kolom lainnya
    $data->namapartner = $request->input('namapartner');
    $data->jenispartner = $request->input('jenispartner');
    $data->deskripsipartner = $request->input('deskripsipartner');

    $data->save();

    return redirect()->route('partner')->with('success', 'Sponsor berhasil diperbarui.');
}

public function deletepartner($id)
{
    // Temukan partner berdasarkan ID
    $partner = \App\Models\partner::find($id);

    if (!$partner) {
        return redirect()->route('partner')->with('error', 'Data partner tidak ditemukan');
    }

    // Hapus file gambar dari storage jika ada
    $filePath = storage_path('app/public/partner/' . $partner->gambarpartner);
    if ($partner->gambarpartner && file_exists($filePath)) {
        unlink($filePath); // Hapus gambar dari sistem file
    }

    // Hapus data dari database
    $partner->delete();

    // Redirect ke halaman sebelumnya jika tersedia
    if (session('halaman_url')) {
        return redirect(session('halaman_url'))->with('success', 'Sponsor berhasil dihapus');
    }

    return redirect()->route('partner')->with('success', 'Sponsor berhasil dihapus');
}

}
