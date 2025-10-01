<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\startup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StartupController extends Controller
{
     public function startup()
    {
        $routing = "startup"; //untuk hover admin 
        $data = startup::all(); 
        Session::put('halaman_url', request()->fullUrl());
        return view('admin.startup', compact('routing', 'data'));
    }

    public function tambahstartup()
    { 
        return view('admin.tambahstartup');


    }

  public function insertstartup(Request $request)
{
    // Validasi form input
    $request->validate([
        'namastartup' => 'required|string',
        'jenisstartup' => 'required|string',
        'deskripsistartup' => 'required|string|max:140',
        'gambarstartup' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        'batchstartup' => 'nullable|string',
        'igstartup' => 'nullable|url',
        'fbstartup' => 'nullable|url',
        'xstartup' => 'nullable|url',
        'linkedinstartup' => 'nullable|url',
    ], [
        'namastartup.required' => 'Nama startup harus diisi.',
        'jenisstartup.required' => 'Jenis startup harus diisi.',
        'deskripsistartup.required' => 'Deskripsi startup harus diisi.',
        'deskripsistartup.max' => 'Deskripsi startup maksimal 140 karakter.',
        'gambarstartup.required' => 'Gambar startup harus diunggah.',
        'gambarstartup.image' => 'Gambar harus berupa file gambar.',
        'gambarstartup.mimes' => 'Gambar hanya boleh JPG atau PNG.',
        'gambarstartup.max' => 'Ukuran gambar maksimal 5MB.',
        'igstartup.url' => 'Link Instagram tidak valid.',
        'fbstartup.url' => 'Link Facebook tidak valid.',
        'xstartup.url' => 'Link X (Twitter) tidak valid.',
        'linkedinstartup.url' => 'Link LinkedIn tidak valid.',
    ]);

    // Ambil file gambar
    $file = $request->file('gambarstartup');
    $filename = $file->hashName(); // Nama unik

    // Validasi ulang mime type (keamanan tambahan)
    $mimeType = mime_content_type($file->getPathname());
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
        return redirect()->back()->withErrors(['gambarstartup' => 'Format gambar tidak valid.']);
    }

    // Simpan gambar ke folder storage/app/public/startup
    $file->storeAs('startup', $filename, 'public');

    // Simpan data ke database
    $startup = new \App\Models\startup();
    $startup->namastartup = $request->input('namastartup');
    $startup->jenisstartup = $request->input('jenisstartup');
    $startup->deskripsistartup = $request->input('deskripsistartup');
    $startup->gambarstartup = $filename;
    $startup->batchstartup = $request->input('batchstartup');
    $startup->igstartup = $request->input('igstartup');
    $startup->fbstartup = $request->input('fbstartup');
    $startup->xstartup = $request->input('xstartup');
    $startup->linkedinstartup = $request->input('linkedinstartup');

    $startup->save();

    return redirect()->route('startup')->with('success', 'Mitra berhasil ditambahkan.');
}


public function tampilstartup($id)
    {
        {  
            $data = startup::find($id);
            return view('admin.tampilstartup', compact('data'));
        }
        
    }

    public function updatestartup(Request $request, $id)
{
// Validasi input dari form
$request->validate([
    'gambarstartup' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'namastartup' => 'required|string',
    'jenisstartup' => 'required|string',
    'deskripsistartup' => 'required|string|max:140',
    'batchstartup' => 'nullable|string',
    'igstartup' => 'nullable|url',
    'fbstartup' => 'nullable|url',
    'xstartup' => 'nullable|url',
    'linkedinstartup' => 'nullable|url',
], [
    'gambarstartup.image' => 'Gambar startup harus berupa file JPG, JPEG, atau PNG.',
    'gambarstartup.mimes' => 'Gambar startup harus berupa file JPG, JPEG, atau PNG.',
    'gambarstartup.max' => 'Ukuran gambar startup maksimal adalah 5MB.',
    'namastartup.required' => 'Nama startup harus diisi.',
    'jenisstartup.required' => 'Jenis startup harus diisi.',
    'deskripsistartup.required' => 'Deskripsi startup harus diisi.',
    'deskripsistartup.max' => 'Deskripsi startup maksimal 140 karakter.',
    'igstartup.url' => 'Link Instagram tidak valid.',
    'fbstartup.url' => 'Link Facebook tidak valid.',
    'xstartup.url' => 'Link X (Twitter) tidak valid.',
    'linkedinstartup.url' => 'Link LinkedIn tidak valid.',
]);

// Ambil data startup berdasarkan ID
$data = \App\Models\startup::find($id);

if (!$data) {
    return redirect()->route('startup')->with('error', 'Data startup tidak ditemukan');
}

// Cek dan proses file gambar jika ada
if ($request->hasFile('gambarstartup')) {
    $file = $request->file('gambarstartup');

    // Verifikasi mime type
    $mimeType = mime_content_type($file->getPathname());
    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg'])) {
        return redirect()->back()->withErrors(['gambarstartup' => 'Format gambar tidak valid.']);
    }

    // Hapus gambar lama jika ada
    if ($data->gambarstartup && Storage::exists('public/startup/' . $data->gambarstartup)) {
        Storage::delete('public/startup/' . $data->gambarstartup);
    }

    // Simpan gambar baru
    $filename = $file->hashName();
    $file->storeAs('startup', $filename, 'public');
    $data->gambarstartup = $filename;
}

// Update kolom lainnya
$data->namastartup = $request->input('namastartup');
$data->jenisstartup = $request->input('jenisstartup');
$data->deskripsistartup = $request->input('deskripsistartup');
$data->batchstartup = $request->input('batchstartup');
$data->igstartup = $request->input('igstartup');
$data->fbstartup = $request->input('fbstartup');
$data->xstartup = $request->input('xstartup');
$data->linkedinstartup = $request->input('linkedinstartup');

$data->save();

return redirect()->route('startup')->with('success', 'Mitra berhasil diperbarui.');

}

public function deletestartup($id)
{
    // Temukan startup berdasarkan ID
    $startup = \App\Models\startup::find($id);

    if (!$startup) {
        return redirect()->route('startup')->with('error', 'Data startup tidak ditemukan');
    }

    // Hapus file gambar dari storage jika ada
    $filePath = storage_path('app/public/startup/' . $startup->gambarstartup);
    if ($startup->gambarstartup && file_exists($filePath)) {
        unlink($filePath); // Hapus gambar dari sistem file
    }

    // Hapus data dari database
    $startup->delete();

    // Redirect ke halaman sebelumnya jika tersedia
    if (session('halaman_url')) {
        return redirect(session('halaman_url'))->with('success', 'Mitra berhasil dihapus');
    }

    return redirect()->route('startup')->with('success', 'Mitra berhasil dihapus');
}

}
