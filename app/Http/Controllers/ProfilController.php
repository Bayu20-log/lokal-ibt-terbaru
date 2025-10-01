<?php

namespace App\Http\Controllers;
use App\Models\profil;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function tampilprofil()
{
    $data = \App\Models\profil::first(); // Ambil baris pertama (jika ada)
    return view('admin.tampilprofil', compact('data'));
}

public function updateprofil(Request $request)
{
$request->validate([
    'judulprofil' => 'nullable|string',
    'subjudulprofil' => 'nullable|string',
    'deskripsiprofil' => 'nullable|string|max:400', // maksimal 400 karakter
    'tujuanprofil1' => 'nullable|string',
    'deskripsitujuanprofil1' => 'nullable|string',
    'tujuanprofil2' => 'nullable|string',
    'deskripsitujuanprofil2' => 'nullable|string',
    'tujuanprofil3' => 'nullable|string',
    'deskripsitujuanprofil3' => 'nullable|string',
    'tujuanprofil4' => 'nullable|string',
    'deskripsitujuanprofil4' => 'nullable|string',

    'gambarprofil' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'gambartujuanprofil1' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'gambartujuanprofil2' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'gambartujuanprofil3' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
    'gambartujuanprofil4' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
]);


    $data = \App\Models\profil::first() ?? new \App\Models\profil;

    // Simpan input teks
    $data->judulprofil = $request->input('judulprofil');
    $data->subjudulprofil = $request->input('subjudulprofil');
    $data->deskripsiprofil = $request->input('deskripsiprofil');

    $data->tujuanprofil1 = $request->input('tujuanprofil1');
    $data->deskripsitujuanprofil1 = $request->input('deskripsitujuanprofil1');

    $data->tujuanprofil2 = $request->input('tujuanprofil2');
    $data->deskripsitujuanprofil2 = $request->input('deskripsitujuanprofil2');

    $data->tujuanprofil3 = $request->input('tujuanprofil3');
    $data->deskripsitujuanprofil3 = $request->input('deskripsitujuanprofil3');

    $data->tujuanprofil4 = $request->input('tujuanprofil4');
    $data->deskripsitujuanprofil4 = $request->input('deskripsitujuanprofil4');

    // Simpan dan hapus file lama jika ada yang baru
    $fields = ['gambarprofil', 'gambartujuanprofil1', 'gambartujuanprofil2', 'gambartujuanprofil3', 'gambartujuanprofil4'];

    foreach ($fields as $field) {
        if ($request->hasFile($field)) {
            // Hapus file lama
            if ($data->$field && Storage::disk('public')->exists($data->$field)) {
                Storage::disk('public')->delete($data->$field);
            }
            // Simpan file baru
            $data->$field = $request->file($field)->store('profil', 'public');
        }
    }

    $data->save();

    // ✅ FIX: Menggunakan nama rute yang benar yaitu 'profil'
    return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
}

}