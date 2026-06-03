<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(){
        $daftar_mhs = \App\Models\Mahasiswa::all();
    return view('index', compact('daftar_mhs'));
    }

}