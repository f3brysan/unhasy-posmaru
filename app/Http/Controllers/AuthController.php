<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        $faculties = DB::table('ms_prodi')
            ->select('kode_fakultas', 'fakultas')
            ->groupBy(['kode_fakultas', 'fakultas'])
            ->orderBy('kode_fakultas', 'ASC')
            ->get();
        $prodis = DB::table('ms_prodi')
            ->orderBy('kode_fakultas', 'ASC')
            ->orderBy('kode_prodi', 'ASC')
            ->get();
        
        return view('auth.register', compact('faculties', 'prodis'));
    }
}
