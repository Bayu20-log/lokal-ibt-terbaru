<?php

namespace App\Http\Controllers;

use App\Models\kontak;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class KontakController extends Controller
{
public function tampilkontak()
{
    $data = \App\Models\kontak::first();
    return view('admin.tampilkontak', compact('data'));
}

public function updatekontak(Request $request)
{
    $request->validate([
        'alamatfooter' => 'nullable|string',
        'emailfooter' => 'nullable|email',
        'namausefullinks1' => 'nullable|string',
        'namausefullinks2' => 'nullable|string',
        'namausefullinks3' => 'nullable|string',
        'usefullinks1' => 'nullable|url',
        'usefullinks2' => 'nullable|url',
        'usefullinks3' => 'nullable|url',
        'xfooter' => 'nullable|url',
        'igfooter' => 'nullable|url',
        'fbfooter' => 'nullable|url',
        'ytfooter' => 'nullable|url',
        'telpfooter' => 'nullable|string', // ✅ Tambahan validasi telpfooter
    ]);

    // Ambil baris pertama, kalau tidak ada buat baru
    $data = \App\Models\kontak::first();

    if (!$data) {
        $data = new \App\Models\kontak;
    }

    $data->alamatfooter = $request->input('alamatfooter');
    $data->emailfooter = $request->input('emailfooter');
    $data->namausefullinks1 = $request->input('namausefullinks1');
    $data->namausefullinks2 = $request->input('namausefullinks2');
    $data->namausefullinks3 = $request->input('namausefullinks3');
    $data->usefullinks1 = $request->input('usefullinks1');
    $data->usefullinks2 = $request->input('usefullinks2');
    $data->usefullinks3 = $request->input('usefullinks3');
    $data->xfooter = $request->input('xfooter');
    $data->igfooter = $request->input('igfooter');
    $data->fbfooter = $request->input('fbfooter');
    $data->ytfooter = $request->input('ytfooter');
    $data->telpfooter = $request->input('telpfooter'); // ✅ Tambahan simpan telpfooter

    $data->save();

    return redirect()->route('tampilkontak')->with('success', 'Kontak footer berhasil disimpan.');
}


}
