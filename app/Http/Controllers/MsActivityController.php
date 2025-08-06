<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterActivity;
use Illuminate\Support\Facades\Crypt;

class MsActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = MasterActivity::all();

        try {
            if ($request->ajax()) {
                return datatables()->of($activities)
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-original-title="Edit" class="mx-auto btn btn-warning btn-sm edit">Edit</a>';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-original-title="Delete" class="mx-auto btn btn-danger btn-sm delete">Delete</a>';
                        return $btn;
                    })
                    ->addColumn('peserta', function ($row) {
                        return 99;
                    })
                    ->rawColumns(['action', 'peserta'])
                    ->make(true);
            }

            return view('activity.index');
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function store(Request $request)
    {
        try {

            if ($request->student_report_start > $request->student_report_end) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Absensi akhir harus lebih besar dari absensi awal'
                ], 500);
            }

            if ($request->registration_start_date > $request->registration_end_date) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pendaftaran akhir harus lebih besar dari pendaftaran awal'
                ], 500);
            }

            if ($request->activity_start_date > $request->activity_end_date) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pelaksanaan akhir harus lebih besar dari pelaksanaan awal'
                ], 500);
            }

            if (($request->activity_start_date >= $request->registration_start_date) && ($request->activity_start_date <= $request->registration_end_date)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pelaksanaan harus lebih besar dari pendaftaran akhir'
                ], 500);
            }

            if (($request->activity_end_date >= $request->registration_start_date) && ($request->activity_end_date <= $request->registration_end_date)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pelaksanaan harus lebih besar dari pendaftaran awal'
                ], 500);
            }

            $updateOrCreate = MasterActivity::updateOrCreate(
                [
                    'id' => $request->id
                ],
                [
                    'name' => $request->name,
                    'year' => $request->year,
                    'activity_start_date' => $request->activity_start_date,
                    'activity_end_date' => $request->activity_end_date,
                    'registration_start_date' => $request->registration_start_date,
                    'registration_end_date' => $request->registration_end_date,
                    'student_report_start' => $request->student_report_start,
                    'student_report_end' => $request->student_report_end,
                    'updated_by' => auth()->user()->name
                ]
            );

            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'data' => $updateOrCreate
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
