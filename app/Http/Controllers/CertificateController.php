<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function cetakSertifikat($id)
    {
        $id = Crypt::decrypt($id);
        
        $participant = ActivityParticipant::with(['user', 'activity'])->find($id);  

        $certificateName = $participant->activity->name.'_'.$participant->user->no_induk.'_'.date('YmdHis');

        $data = [
            'nama' => $participant->user->name,   
            'tanggal' => date('d-m-Y'),        
            'backgroundImage' => asset( $participant->activity->bg_certificate),
            'name_x' => $participant->activity->x_coordinate,
            'name_y' => $participant->activity->y_coordinate,
            'font_size' => $participant->activity->font_size,
        ];

        
        // return view('certificate.template', $data);
        $pdf = Pdf::loadView('certificate.template', $data);
        $pdf->setPaper('A4', 'landscape'); // Sesuaikan dengan orientasi template
        $pdf->setOption('margin-top', 0);
        $pdf->setOption('margin-right', 0);
        $pdf->setOption('margin-bottom', 0);
        $pdf->setOption('margin-left', 0);           
        
        return $pdf->stream('sertifikat-' . $certificateName . '.pdf');
    }
}
