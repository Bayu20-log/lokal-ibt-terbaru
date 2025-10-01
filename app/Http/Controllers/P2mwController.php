<?php

namespace App\Http\Controllers;

use App\Models\p2mw;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class P2mwController extends Controller
{
     
    public function tampilp2mw()
{
       $data = \App\Models\p2mw::first();
    return view('admin.tampilp2mw', compact('data'));
}

public function updatep2mw(Request $request)
{
    // Validasi semua input
    $request->validate([
        'judulp2mw' => 'nullable|string',
        'subjudulp2mw' => 'nullable|string',
        'deskripsip2mw' => 'nullable|string|max:250',
        'linkp2mw' => 'nullable|string',

        // Validasi alur dan deskripsinya (1–10)
        'alurp2mw1' => 'nullable|string',
        'deskripsialurp2mw1' => 'nullable|string|max:140',
        'alurp2mw2' => 'nullable|string',
        'deskripsialurp2mw2' => 'nullable|string|max:140',
        'alurp2mw3' => 'nullable|string',
        'deskripsialurp2mw3' => 'nullable|string|max:140',
        'alurp2mw4' => 'nullable|string',
        'deskripsialurp2mw4' => 'nullable|string|max:140',
        'alurp2mw5' => 'nullable|string',
        'deskripsialurp2mw5' => 'nullable|string|max:140',
        'alurp2mw6' => 'nullable|string',
        'deskripsialurp2mw6' => 'nullable|string|max:140',
        'alurp2mw7' => 'nullable|string',
        'deskripsialurp2mw7' => 'nullable|string|max:140',
        'alurp2mw8' => 'nullable|string',
        'deskripsialurp2mw8' => 'nullable|string|max:140',
        'alurp2mw9' => 'nullable|string',
        'deskripsialurp2mw9' => 'nullable|string|max:140',
        'alurp2mw10' => 'nullable|string',
        'deskripsialurp2mw10' => 'nullable|string|max:140',

        // Validasi nama & deskripsi file (boleh kosong)
        'namafilep2mw1' => 'nullable|string',
        'deskripsifilep2mw1' => 'nullable|string|max:60',
        'namafilep2mw2' => 'nullable|string',
        'deskripsifilep2mw2' => 'nullable|string|max:60',
        'namafilep2mw3' => 'nullable|string',
        'deskripsifilep2mw3' => 'nullable|string|max:60',
        'namafilep2mw4' => 'nullable|string',
        'deskripsifilep2mw4' => 'nullable|string|max:60',
        'namafilep2mw5' => 'nullable|string',
        'deskripsifilep2mw5' => 'nullable|string|max:60',

        // Validasi file PDF (boleh kosong, tapi jika ada harus PDF dan max 5MB)
        'filep2mw1' => 'nullable|file|mimes:pdf|max:5120',
        'filep2mw2' => 'nullable|file|mimes:pdf|max:5120',
        'filep2mw3' => 'nullable|file|mimes:pdf|max:5120',
        'filep2mw4' => 'nullable|file|mimes:pdf|max:5120',
        'filep2mw5' => 'nullable|file|mimes:pdf|max:5120',

        // Validasi link drive (opsional, format URL)
        'drivep2mw1' => 'nullable|url',
        'drivep2mw2' => 'nullable|url',
        'drivep2mw3' => 'nullable|url',
        'drivep2mw4' => 'nullable|url',
        'drivep2mw5' => 'nullable|url',
    ]);

    // Ambil data pertama, atau buat baru jika belum ada
    $data = \App\Models\p2mw::first();
    if (!$data) {
        $data = \App\Models\p2mw::create([]); // Buat baris kosong di DB
    }

    // Isi semua field biasa (kecuali drive/file, nanti di-handle khusus)
    $fields = [
        'judulp2mw', 'subjudulp2mw', 'deskripsip2mw', 'linkp2mw',
        'alurp2mw1', 'deskripsialurp2mw1',
        'alurp2mw2', 'deskripsialurp2mw2',
        'alurp2mw3', 'deskripsialurp2mw3',
        'alurp2mw4', 'deskripsialurp2mw4',
        'alurp2mw5', 'deskripsialurp2mw5',
        'alurp2mw6', 'deskripsialurp2mw6',
        'alurp2mw7', 'deskripsialurp2mw7',
        'alurp2mw8', 'deskripsialurp2mw8',
        'alurp2mw9', 'deskripsialurp2mw9',
        'alurp2mw10', 'deskripsialurp2mw10',
        'namafilep2mw1', 'deskripsifilep2mw1',
        'namafilep2mw2', 'deskripsifilep2mw2',
        'namafilep2mw3', 'deskripsifilep2mw3',
        'namafilep2mw4', 'deskripsifilep2mw4',
        'namafilep2mw5', 'deskripsifilep2mw5',
    ];

    foreach ($fields as $field) {
        $data->$field = $request->input($field);
    }

    // ✅ Perbaikan: handle drive/file agar hanya salah satu aktif
    for ($i = 1; $i <= 5; $i++) {
        $fileField  = "filep2mw{$i}";
        $driveField = "drivep2mw{$i}";

        if ($request->hasFile($fileField)) {
            // Hapus file lama
            if ($data->$fileField && Storage::disk('public')->exists($data->$fileField)) {
                Storage::disk('public')->delete($data->$fileField);
            }

            // Simpan file baru
            $data->$fileField = $request->file($fileField)->store('p2mw', 'public');

            // Kosongkan link drive
            $data->$driveField = null;
        }
        elseif ($request->filled($driveField)) {
            // Hapus file lama kalau ada
            if ($data->$fileField && Storage::disk('public')->exists($data->$fileField)) {
                Storage::disk('public')->delete($data->$fileField);
                $data->$fileField = null;
            }

            // Simpan link drive
            $data->$driveField = $request->input($driveField);
        }
    }

    $data->save();
    
    // ✅ FIX: Menggunakan nama rute yang benar yaitu 'p2mw'
    return redirect()->route('p2mw')->with('success', 'Data Program berhasil diperbarui.');
}


}