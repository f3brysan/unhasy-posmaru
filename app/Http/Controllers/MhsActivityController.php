<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityReport;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Crypt;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MhsActivityController extends Controller
{
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $activity = ActivityParticipant::with('activity')->where('activity_id', $id)->first();

        $activityReport = ActivityReport::where('activity_id', $id)->where('user_id', auth()->user()->id)->get();

        $countActivityReport = $activityReport->count();


        $time['start'] = date('Y-m-d').' '.$activity->activity->student_report_start;
        $time['end'] = date('Y-m-d').' '.$activity->activity->student_report_end;

        // Hitung rentang hari antara tanggal mulai dan tanggal akhir kegiatan
        $startDate = \Carbon\Carbon::parse($activity->activity->activity_start_date);
        $endDate = \Carbon\Carbon::parse($activity->activity->activity_end_date);
        $rentangHari = $startDate->diffInDays($endDate) + 1; // +1 agar inklusif

        $allowCertificate = $countActivityReport >= $rentangHari ? true : false;

        return view('activity.mahasiswa.show', compact('activity', 'time', 'allowCertificate', 'startDate', 'endDate'));
    }

    public function getActivity($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $reports = ActivityReport::where('activity_id', $id)->where('user_id', auth()->user()->id)->get();
        try {
            if ($request->ajax()) {
                return datatables()->of($reports)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $button = '';
                        if ($row->created_at->format('Y-m-d') == date('Y-m-d')) {
                            $button = '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-report" data-id="'.Crypt::encrypt($row->id).'"><i class="fa fa-trash"></i></a>';
                        }
                        return $button;
                    })
                    ->addColumn('file', function ($row) {
                        $filePath = public_path($row->picture);
                        if (file_exists($filePath)) {
                            return '<a class="btn btn-sm btn-primary" href="'.asset($row->picture).'" target="_blank"><i class="fa fa-file"></i>&nbsp;File Laporan</a>';
                        } else {
                            return '-';
                        }
                    })
                    ->rawColumns(['action', 'file'])
                    ->make(true);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }

    }

    public function storeActivityReport(Request $request)
    {
        // try {


        $validator = Validator::make($request->all(), [
            'file' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        $checkOldReport = ActivityReport::where('activity_id', $request->activity_id)
            ->where('user_id', auth()->user()->id)
            ->where('tgl_setor', date('Y-m-d'))
            ->first();
        if ($checkOldReport) {
            unlink(public_path($checkOldReport->picture));
        }

        $nim = auth()->user()->no_induk;

        $file = $request->file('file');

        $extension = $file->getClientOriginalExtension();
        $filename = $nim.'_'.date('YmdHis').'.'.$extension;
        $destination = 'activity-report/'.$request->activity_id.'/'.date('Ymd').'/';
        $file->move(public_path($destination), $filename);
        $path = $destination.$filename;

        $activityReport = ActivityReport::updateOrCreate([
            'activity_id' => $request->activity_id,
            'user_id' => auth()->user()->id,
            'tgl_setor' => date('Y-m-d'),
        ], [
            'picture' => $path,
            'description' => $request->description,
            'tgl_setor' => date('Y-m-d'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil disimpan'
        ]);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => $th->getMessage()
        //     ], 500);
        // }
    }

    public function deleteActivityReport(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
            $activityReport = ActivityReport::find($id);
            unlink(public_path($activityReport->picture));
            $activityReport->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
