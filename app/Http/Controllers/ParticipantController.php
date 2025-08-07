<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Crypt;

class ParticipantController extends Controller
{
    public function getParticipants($id, Request $request)
    {
        $participants = ActivityParticipant::with(['user.biodata.prodi', 'user.biodata.fakultas'])->where('activity_id', Crypt::decrypt($id))->get();     
        
        if ($request->ajax()) {
            return datatables()->of($participants)
                ->addColumn('nim', function ($row) {
                    return $row->user->no_induk;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('faculty', function ($row) {
                    $text = 'Fakultas ' . $row->user->biodata->fakultas->fakultas;
                    $text .= '<br>';
                    $text .= 'Program Studi ' . $row->user->biodata->prodi->prodi;
                    return $text;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->rawColumns(['faculty', 'action'])
                ->make(true);
        }        
    }
}
