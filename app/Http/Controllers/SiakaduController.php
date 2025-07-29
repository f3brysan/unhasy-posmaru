<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiakaduController extends Controller
{
    public function getDataMahasiswa($nim)
    {
        $nim = '1795114020';
        $parr = array(
                'type' => 'auth',
                'username' => $nim,
                'password' => $nim
            );
            
        $url = 'https://siakad.unhasy.ac.id/api/all.php';
        
        $cekAuthSiakad = $this->requestData($url, 'POST', $parr);

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
                
    }
}
