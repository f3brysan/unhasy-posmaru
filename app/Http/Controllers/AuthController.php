<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\User;
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

    public function storeRegister(Request $request)
    {
        try {

            $userExist = DB::table('users')
                ->where('no_induk', $request->nim)
                ->exists();

            if ($userExist) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda Sudah Terdaftar'
                ], 500);
            }
            
            DB::beginTransaction();
            $insertUser = User::create([
                'name' => $request->nama,
                'email' => $request->nim . '@unhasy.ac.id',
                'password' => bcrypt($request->nim),
                'no_induk' => $request->nim,                                
            ]);

            $idUser = $insertUser->id;
            $insertBiodata = Biodata::create([
                'id' => $idUser,
                'prodi_kode' => $request->prodi_kode,
                'fakultas_kode' => $request->fakultas_kode,
                'gender' => $request->jenis_kelamin,
                'chart_size' => $request->ukuran_kaos,
                'hp' => $request->nomor_hp
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Berhasil'
            ], 200);
            // $insertBiodata = DB::table('biodata')
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
