<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Biodata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'no_induk' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('no_induk', 'password');

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            $user = auth()->user();      
            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No Induk atau Password Salah',
            ], 401);
        }
    }

    public function loginAs(Request $request) 
    {
        try {
            $user = User::findOrFail(Crypt::decrypt($request->id));
            auth()->login($user);
            $request->session()->regenerate();

            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login Gagal',
            ]);
        }
    }
    
    public function register()
    {
        // Retrieve faculties from the database, grouping and ordering them by faculty code
        $faculties = DB::table('ms_prodi')
            ->select('kode_fakultas', 'fakultas')
            ->groupBy(['kode_fakultas', 'fakultas'])
            ->orderBy('kode_fakultas', 'ASC')
            ->get();

        // Retrieve programs of study, ordered by faculty and program codes
        $prodis = DB::table('ms_prodi')
            ->orderBy('kode_fakultas', 'ASC')
            ->orderBy('kode_prodi', 'ASC')
            ->get();

        // Return the registration view with faculties and programs of study
        return view('auth.register', compact('faculties', 'prodis'));
    }
    
    public function storeRegister(Request $request)
    {
        try {
            // Check if the user already exists in the database
            $userExist = DB::table('users')
                ->where('no_induk', $request->nim)
                ->exists();

            if ($userExist) {
                // Return an error if the user already exists
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda Sudah Terdaftar'
                ], 500);
            }

            // Begin the transaction
            DB::beginTransaction();

            // Insert the user into the users table
            $insertUser = User::create([
                'name' => $request->nama,
                'email' => $request->nim . '@unhasy.ac.id',
                'password' => bcrypt($request->nim),
                'no_induk' => $request->nim,
            ]);

            // Get the id of the inserted user
            $idUser = $insertUser->id;

            // Insert the biodata into the biodata table
            $insertBiodata = Biodata::create([
                'id' => $idUser,
                'prodi_kode' => $request->prodi_kode,
                'fakultas_kode' => $request->fakultas_kode,
                'gender' => $request->jenis_kelamin,
                'chart_size' => $request->ukuran_kaos,
                'hp' => $request->nomor_hp
            ]);

            $insertUser->assignRole('mahasiswa');

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Pendaftaran Berhasil'
            ], 200);

        } catch (\Throwable $th) {
            // Roll back the transaction if an error occurs
            DB::rollBack();
            // Return an error response
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
