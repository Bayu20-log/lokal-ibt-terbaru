<?php

namespace App\Http\Controllers;

use App\Models\beranda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class BerandaController extends Controller
{
    public function tampilberanda()
    {
        $routing = "beranda";

        // Ambil semua data beranda (maksimal 3)
        $data = beranda::all();

        // Jika data kurang dari 3, buat data kosong agar form selalu ada 3
        if ($data->count() < 3) {
            for ($i = $data->count(); $i < 3; $i++) {
                beranda::create([
                    'judulhero' => 'Data Belum Diisi',
                    'deskripsihero' => 'Data Belum Diisi',
                    'angka1' => '0',
                    'teks1' => 'Start Up Dirintis',
                    'angka2' => '0',
                    'teks2' => 'Calon Founders',
                ]);
            }
            $data = beranda::all();
        }

        return view('admin.tampilberanda', compact('routing', 'data'));
    }

    public function updateberanda(Request $request)
    {
        // Ambil semua data beranda yang ada di database
        $beranda_items = Beranda::all();

        // Loop untuk memproses setiap slide
        foreach ($beranda_items as $index => $item) {
            // Validasi data untuk setiap slide
            $request->validate([
                "judulhero.{$index}" => 'required|string',
                "deskripsihero.{$index}" => 'required|string',
                "angka1.{$index}" => 'required|numeric',
                "teks1.{$index}" => 'nullable|string|max:150',
                "angka2.{$index}" => 'required|numeric',
                "teks2.{$index}" => 'nullable|string|max:150',
                "gambarhero.{$index}" => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
                "linkdaftar1.{$index}" => 'nullable|url',
                "linkdaftar2.{$index}" => 'nullable|url',
            ]);

            // Siapkan data untuk update
            $item->judulhero = $request->input("judulhero.{$index}");
            $item->deskripsihero = $request->input("deskripsihero.{$index}");
            $item->angka1 = $request->input("angka1.{$index}");
            $item->teks1 = $request->input("teks1.{$index}");
            $item->angka2 = $request->input("angka2.{$index}");
            $item->teks2 = $request->input("teks2.{$index}");
            $item->linkdaftar1 = $request->input("linkdaftar1.{$index}");
            $item->linkdaftar2 = $request->input("linkdaftar2.{$index}");

            // Proses gambar jika ada file baru diunggah
            if ($request->hasFile("gambarhero.{$index}")) {
                $file = $request->file("gambarhero.{$index}");
                $filename = $file->hashName();

                // Hapus gambar lama jika ada
                // BARIS DI BAWAH INI TELAH DIPERBAIKI
                if ($item->gambarhero && Storage::disk('public')->exists('beranda/' . $item->gambarhero)) {
                    // BARIS DI BAWAH INI TELAH DIPERBAIKI
                    Storage::disk('public')->delete('beranda/' . $item->gambarhero);
                }

                // Simpan gambar baru
                $file->storeAs('beranda', $filename, 'public');
                $item->gambarhero = $filename;
            }

            // Simpan perubahan
            $item->save();
        }

        return redirect()->route('beranda')->with('success', 'Data Beranda berhasil diperbarui.');
    }
}