<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\kontak;
use App\Models\berita;
use App\Models\beranda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserberitaController extends Controller
{
 
 public function userberita()
{
    $databerita = berita::orderBy('tanggalberita', 'desc')->paginate(6); // PAGINATION
    $datafooter = kontak::first();
     $databeranda = beranda::first();
    return view('user.userberita', compact('datafooter', 'databerita','databeranda'));
}


public function userberitalengkap($id)
{
    $berita = berita::findOrFail($id);
    $datafooter = kontak::first();
     $databeranda = beranda::first();
    
    return view('user.userberitalengkap', compact('berita', 'datafooter','databeranda'));
}

}

