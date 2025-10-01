<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\katalog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KatalogController extends Controller
{
    public function katalog()
    {
        $routing = "katalog";
        $data = katalog::latest()->get();
        Session::put('halaman_url', request()->fullUrl());
        return view('admin.katalog', compact('routing', 'data'));
    }

    public function tambahkatalog()
    {
        return view('admin.tambahkatalog');
    }

    public function insertkatalog(Request $request)
    {
        // Validasi form input
        $validatedData = $request->validate([
            'namaproduk'        => 'required|string|max:255',
            'tahunkatalog'      => 'nullable|string|max:4', // TAMBAHKAN INI
            'namapencipta'      => 'required|string|max:255',
            'emailpencipta'     => 'required|email',
            'deskripsipencipta' => 'required|string',
            'gambarproduk'      => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'igpencipta'        => 'nullable|url',
            'fbpencipta'        => 'nullable|url',
            'xpencipta'         => 'nullable|url',
            'linkedinpencipta'  => 'nullable|url',
        ]);

        // Proses dan simpan gambar
        if ($request->hasFile('gambarproduk')) {
            $path = $request->file('gambarproduk')->store('public/katalog');
            $validatedData['gambarproduk'] = basename($path);
        }

        // Simpan data ke database
        katalog::create($validatedData);

        return redirect()->route('katalog')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function tampilkatalog($id)
    {
        $data = katalog::findOrFail($id);
        return view('admin.tampilkatalog', compact('data'));
    }

    public function updatekatalog(Request $request, $id)
    {
        $data = katalog::findOrFail($id);

        // Validasi input dari form
        $validatedData = $request->validate([
            'gambarproduk'      => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'namaproduk'        => 'required|string|max:255',
            'tahunkatalog'      => 'nullable|string|max:4', // TAMBAHKAN INI
            'namapencipta'      => 'required|string|max:255',
            'emailpencipta'     => 'required|email',
            'deskripsipencipta' => 'required|string',
            'igpencipta'        => 'nullable|url',
            'fbpencipta'        => 'nullable|url',
            'xpencipta'         => 'nullable|url',
            'linkedinpencipta'  => 'nullable|url',
        ]);

        // Cek dan proses file gambar jika ada
        if ($request->hasFile('gambarproduk')) {
            // Hapus gambar lama
            if ($data->gambarproduk) {
                Storage::delete('public/katalog/' . $data->gambarproduk);
            }
            // Simpan gambar baru
            $path = $request->file('gambarproduk')->store('public/katalog');
            $validatedData['gambarproduk'] = basename($path);
        }

        // Update data di database
        $data->update($validatedData);

        return redirect()->route('katalog')->with('success', 'Produk berhasil diperbarui.');
    }

    public function deletekatalog($id)
    {
        $katalog = katalog::findOrFail($id);

        // Hapus file gambar dari storage jika ada
        if ($katalog->gambarproduk) {
            Storage::delete('public/katalog/' . $katalog->gambarproduk);
        }

        // Hapus data dari database
        $katalog->delete();

        // Redirect
        if (session('halaman_url')) {
            return redirect(session('halaman_url'))->with('success', 'Produk berhasil dihapus');
        }

        return redirect()->route('katalog')->with('success', 'Produk berhasil dihapus');
    }
}