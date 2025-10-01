<?php

namespace App\Http\Controllers;

use App\Models\kontak;
use App\Models\event;
use App\Models\berita;
use App\Models\p2mw;
use App\Models\startup;
use App\Models\partner;
use App\Models\profil;
use App\Models\beranda;
use App\Models\katalog; 

class LandingpageController extends Controller
{
    public function landingpage()
    {
        $databeranda = beranda::orderBy('created_at', 'desc')->take(3)->get();
        $databerita = berita::orderBy('tanggalberita', 'desc')->take(3)->get();
        $dataevent = event::orderBy('tanggalevent', 'desc')->take(3)->get();
        $datap2mw = p2mw::first();
        $datastartup = startup::all();
        $datapartner = partner::all();
        $dataprofil = profil::first();
        $datafooter = kontak::first();
        $datakatalog = katalog::all();

        return view('user.landingpage', compact(
            'databerita',
            'dataevent',
            'datap2mw',
            'datastartup',
            'datapartner',
            'dataprofil',
            'databeranda',
            'datafooter',
            'datakatalog'
        ));
    }
}