<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

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
                    $text = $row->user->biodata->prodi->prodi . '<br>';
                    $text .= 'Fakultas ' . $row->user->biodata->fakultas->fakultas;
                    return $text;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->rawColumns(['faculty', 'action'])
                ->make(true);
        }        
    }
    
    public function addParticipant(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'activity_id' => 'required',
                'nim' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Check if participant already exists
            $getParticipant = User::where('no_induk', $request->nim)->first();

            if (!$getParticipant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak bisa menambahkan peserta, NIM tidak ditemukan',
                ], 400);
            }

            $checkParticipantExist = ActivityParticipant::where('activity_id', Crypt::decrypt($request->activity_id))->where('user_id', $getParticipant->id)->first();

            if ($checkParticipantExist) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Peserta sudah terdaftar',
                ], 400);
            }

            // Create new participant
            $participant = ActivityParticipant::create([
                'activity_id' => Crypt::decrypt($request->activity_id),
                'user_id' => Crypt::decrypt($getParticipant->id),
            ]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Peserta berhasil ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
