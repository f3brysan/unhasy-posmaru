<?php

namespace App\Http\Controllers;

use App\Models\ActivityReport;
use Illuminate\Http\Request;
use App\Models\ActivitySession;
use Illuminate\Support\Facades\Crypt;

class MsActivitySessionController extends Controller
{
    public function getActivitySession(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $activitySession = ActivitySession::where('activity_id', $id)->get();

        try {
            if ($request->ajax()) {
                return datatables()->of($activitySession)
                    ->addColumn('action', function ($row) {
                        return '<div class="btn-group" role="group" aria-label="Aksi">
                                    <a href="javascript:void(0)" class="btn btn-info btn-sm edit-activity-session" data-id="'.Crypt::encrypt($row->id).'">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-activity-session" data-id="'.Crypt::encrypt($row->id).'">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>';
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()
                    ->make(true);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function storeActivitySession(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'student_report_start' => 'required|',
                'student_report_end' => 'required|after_or_equal:student_report_start',
            ]);

            $start = $request->student_report_start;
            $end = $request->student_report_end;

            $exists = ActivitySession::where('activity_id', $request->activity_id)
                ->where(function ($query) use ($start, $end) {
                    $query->where(function ($q) use ($start, $end) {
                        $q->where('student_report_start', '<', $end)
                            ->where('student_report_end', '>', $start);
                    });
                })
                ->exists();

            $exists = ! empty($request->id) ? false : $exists;
            
            if ($exists) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Waktu mulai dan waktu selesai tidak boleh overlap dengan sesi lain'
                ], 422);
            }

            $activitySession = ActivitySession::updateOrCreate([
                'id' => $request->id
            ], [
                'name' => $request->name,
                'activity_id' => $request->activity_id,
                'student_report_start' => $request->student_report_start,
                'student_report_end' => $request->student_report_end,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $activitySession
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }

    }

    public function editActivitySession(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $activitySession = ActivitySession::find($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $activitySession
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteActivitySession(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $checkTransactionExist = ActivityReport::where('activity_session_id', $id)->exists();

            if ($checkTransactionExist) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sesi ini tidak dapat dihapus karena sudah ada laporan kegiatan'
                ], 422);
            }

            $delete = ActivitySession::find($id);
            $delete->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Sesi berhasil dihapus'
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
