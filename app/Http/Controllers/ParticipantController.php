<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\ActivityReport;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function getParticipants($id, Request $request)
    {
        $participants = ActivityParticipant::with(['user.biodata.prodi', 'user.biodata.fakultas'])->where('activity_id', Crypt::decrypt($id))->get();     
        
        $activityReportCountByUser = ActivityReport::where('activity_id', Crypt::decrypt($id))->select('user_id', DB::raw('COUNT(*) as total'))->groupBy('user_id')->get()->pluck('total', 'user_id')->toArray();
        
        $activity = Activity::find(Crypt::decrypt($id));

        
        $activity->activity_start_date = date('d-m-Y', strtotime($activity->activity_start_date));
        $activity->activity_end_date = date('d-m-Y', strtotime($activity->activity_end_date));

        // count day between activity_start_date and activity_end_date
        $start = \Carbon\Carbon::parse($activity->activity_start_date);
        $end = \Carbon\Carbon::parse($activity->activity_end_date);
        $daysCount = $start->diffInDays($end) + 1;        
        
        if ($request->ajax()) {
            return datatables()->of($participants)
                ->addColumn('nim', function ($row) {
                    $text = $row->user->no_induk;
                    $text .= '<br>';
                    $text .= $row->user->name;
                    return $text;
                })        
                ->addColumn('total_report', function ($row) use ($activityReportCountByUser) {
                    return $activityReportCountByUser[$row->user_id] ?? 0;
                })
                ->addColumn('status', function ($row) use ($activityReportCountByUser, $daysCount) {

                    $reportCount = $activityReportCountByUser[$row->user_id] ?? 0;
                    if ($row->is_permitted == 1) {
                        return '<span class="badge bg-success">Lengkap</span>';
                    } 

                    if ($reportCount == $daysCount) {
                        return '<span class="badge bg-success">Lengkap</span>';
                    } else {
                        return '<span class="badge bg-danger">Belum Lengkap</span>';
                    }
                })
                ->addColumn('faculty', function ($row) {
                    $text = $row->user->biodata->prodi->prodi . '<br>';
                    $text .= 'Fakultas ' . $row->user->biodata->fakultas->fakultas;
                    return $text;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->hasRole('superadmin|baak')) {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-sm btn-primary m-1" data-id="'.Crypt::encrypt($row->id).'">Edit</a>';
                        if ($row->is_permitted == 0) {
                            $btn .= '<a href="javascript:void(0)" class="btn btn-sm btn-warning m-1" data-id="'.Crypt::encrypt($row->id).'">Amnesti</a>';
                        }
                    }
                    $btn .= '<a href="'.URL::to('sertifikat/cetak/'.Crypt::encrypt($row->id)).'" class="btn btn-sm btn-danger m-1" target="_blank">Cetak Sertifikat</a>';
                    
                    return $btn;
                })
                ->rawColumns(['faculty', 'action', 'nim', 'status'])
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
