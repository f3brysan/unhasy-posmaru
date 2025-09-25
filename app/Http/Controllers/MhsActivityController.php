<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityReport;
use App\Models\ActivitySession;
use App\Models\ActivityParticipant;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MhsActivityController extends Controller
{
    /**
     * Menampilkan halaman laporan kegiatan.
     *
     * @param  mixed  $id  ID kegiatan yang dienkripsi
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Dekripsi ID kegiatan
        $id = Crypt::decrypt($id);

        // Cari data kegiatan dan peserta
        $activity = ActivityParticipant::with('activity')->where('activity_id', $id)->first();

        // Cari data laporan kegiatan yang diinput oleh user
        $activityReport = ActivityReport::where('activity_id', $id)->where('user_id', auth()->user()->id)->get();

        $activitySession = ActivitySession::where('activity_id', $id)->get();

        $sessionNow = $activitySession->where('student_report_start', '<=', date('H:i:s'))->where('student_report_end', '>=', date('H:i:s'))->first();

        // Hitung jumlah laporan kegiatan yang diinput oleh user
        $countActivityReport = $activityReport->count();

        // Tentukan waktu mulai dan akhir kegiatan
        $time['start'] = date('Y-m-d').' '.$activity->activity->student_report_start;
        $time['end'] = date('Y-m-d').' '.$activity->activity->student_report_end;

        // Hitung rentang hari antara tanggal mulai dan tanggal akhir kegiatan
        $startDate = \Carbon\Carbon::parse($activity->activity->activity_start_date);
        $endDate = \Carbon\Carbon::parse($activity->activity->activity_end_date);
        $rentangHari = $startDate->diffInDays($endDate) + 1; // +1 agar inklusif

        // Tentukan apakah user boleh mengunduh sertifikat
        $allowCertificate = $countActivityReport >= $rentangHari ? true : false;
        $allowCertificate = $activity->is_permitted == 1 ? true : $allowCertificate;

        // Kembalikan view dengan data yang dibutuhkan
        return view('activity.mahasiswa.show', compact('activity', 'time', 'allowCertificate', 'startDate', 'endDate', 'sessionNow'));
    }

    public function getActivity($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $reports = ActivityReport::with('activitySession')->where('activity_id', $id)->where('user_id', auth()->user()->id)->get();
        try {
            if ($request->ajax()) {
                return datatables()->of($reports)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $button = '';
                        
                        $start = $row->activitySession->student_report_start;
                        $end = $row->activitySession->student_report_end;
                        $now = date('H:i:s');

                        if ($now >= $start && $now <= $end) {
                            $button = '<a href="javascript:void(0)" class="btn btn-sm btn-danger delete-report" data-id="'.Crypt::encrypt($row->id).'"><i class="fa fa-trash"></i></a>';
                        }
                        return $button;
                    })
                    ->addColumn('session', function ($row) {
                        return $row->activitySession->name;
                    })
                    ->addColumn('file', function ($row) {                        
                        $filePath = public_path($row->picture);
                        if (file_exists($filePath)) {
                            return '<a class="btn btn-sm btn-primary" href="'.asset($row->picture).'" target="_blank"><i class="fa fa-file"></i>&nbsp;File Laporan</a>';
                        } else {
                            return '-';
                        }
                    })
                    ->rawColumns(['action', 'file', 'session'])
                    ->make(true);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }

    }

    /**
     * Store activity report
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeActivityReport(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Check if the user has already submitted the report today
            $checkOldReport = ActivityReport::where('activity_id', $request->activity_id)
                ->where('user_id', auth()->user()->id)
                ->where('activity_session_id', $request->session_id)
                ->where('tgl_setor', date('Y-m-d'))
                ->first();

            if ($checkOldReport) {
                // Delete the old report
                unlink(public_path($checkOldReport->picture));
            }

            // Get the user's NIM
            $nim = auth()->user()->no_induk;

            // Handle the file
            $file = $request->file('file');

            // Get the file extension
            $extension = $file->getClientOriginalExtension();

            // Set the new filename
            $filename = $nim.'_'.date('YmdHis').'.'.$extension;

            // Set the destination path
            $destination = 'activity-report/'.$request->activity_id.'/'.date('Ymd').'/';

            // Move the file to the destination
            $file->move(public_path($destination), $filename);

            // Get the new path
            $path = $destination.$filename;

            // Update or create the report
            $activityReport = ActivityReport::updateOrCreate([
                'activity_id' => $request->activity_id,
                'user_id' => auth()->user()->id,
                'activity_session_id' => $request->activity_session_id,
                'tgl_setor' => date('Y-m-d'),
            ], [
                'picture' => $path,
                'description' => $request->description,
                'tgl_setor' => date('Y-m-d'),
            ]);

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil disimpan'
            ]);

        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Delete activity report
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteActivityReport(Request $request)
    {
        try {
            // Decrypt the ID
            $id = Crypt::decrypt($request->id);

            // Find the report by ID
            $activityReport = ActivityReport::find($id);

            // Delete the report
            unlink(public_path($activityReport->picture));
            $activityReport->delete();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
