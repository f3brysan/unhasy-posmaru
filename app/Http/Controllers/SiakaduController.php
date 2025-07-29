<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiakaduController extends Controller
{
    public function getDataMahasiswa(Request $request)
    {
        try {
            $nim = $request->nim;
            $parr = array(
                'type' => 'auth',
                'username' => $nim,
                'password' => $nim
            );

            $url = 'https://siakad.unhasy.ac.id/api/all.php';

            $cekAuthSiakad = $this->requestData($url, 'POST', $parr);

            if ($cekAuthSiakad->code == '401') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'NIM Tidak Ditemukan',
                ], 401);
            }

            $data = [
                'nim' => $cekAuthSiakad->data->no_identitas,
                'name' => $cekAuthSiakad->data->name,
                'prodi_kode' => $cekAuthSiakad->data->prodi_kode
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'success',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }

    }
}
