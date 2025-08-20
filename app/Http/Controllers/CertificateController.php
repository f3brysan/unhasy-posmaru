<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Crypt;

class CertificateController extends Controller
{
    public function cetakSertifikat(Request $request)
    {
        $id = Crypt::decrypt($request->id);
        
        $participant = ActivityParticipant::with(['user', 'activity'])->find($id);  
        return $participant;
        return view('certificate.cetak', compact('participant'));
    }
}
