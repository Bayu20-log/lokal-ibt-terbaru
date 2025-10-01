<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\kontak;
use App\Models\beranda;
use App\Models\event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UsereventController extends Controller
{
 
  public function userevent()
{
    $dataevent = event::orderBy('tanggalevent', 'desc')->paginate(6);;
    $datafooter = kontak::first();
   $databeranda = beranda::first();
    return view('user.userevent', compact( 'datafooter','dataevent','databeranda'));
}    

}

