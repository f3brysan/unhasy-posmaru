<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterActivity;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

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
                        if ($row->is_active == 1) {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-status="0" data-original-title="Non Aktifkan" class="mx-auto btn btn-success btn-sm change-status">Non Aktifkan</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-status="1" data-original-title="Aktifkan" class="mx-auto btn btn-secondary btn-sm change-status">Aktifkan</a>';
                        }
                        return $btn;
                    })
                    ->addColumn('peserta', function ($row) {
                        return 99;
                    })
                    ->addColumn('status_btn', function ($row) {
                        if ($row->is_active == 1) {
                            return '<span class="badge bg-success">Aktif</span>';
                        } else {
                            return '<span class="badge bg-secondary">Non Aktif</span>';
                        }
                    })
                    ->rawColumns(['action', 'peserta', 'status_btn'])
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
            $validator = $this->validateActivity($request);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
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

    /**
     * Validate activity data
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateActivity(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'activity_start_date' => 'required|date',
            'activity_end_date' => 'required|date|after_or_equal:activity_start_date',
            'registration_start_date' => 'required|date',
            'registration_end_date' => 'required|date|after_or_equal:registration_start_date',
            'student_report_start' => 'required',
            'student_report_end' => 'required|after_or_equal:student_report_start',
        ];

        $messages = [
            'name.required' => 'Nama kegiatan wajib diisi',
            'year.required' => 'Tahun wajib diisi',
            'activity_end_date.after_or_equal' => 'Pelaksanaan akhir harus lebih besar dari atau sama dengan pelaksanaan awal',
            'registration_end_date.after_or_equal' => 'Pendaftaran akhir harus lebih besar dari atau sama dengan pendaftaran awal',
            'student_report_end.after_or_equal' => 'Absensi akhir harus lebih besar dari atau sama dengan absensi awal',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // Custom validation for date ranges
        $validator->after(function ($validator) use ($request) {
            // Check if activity dates don't overlap with registration dates
            if ($request->activity_start_date <= $request->registration_end_date) {
                $validator->errors()->add('activity_start_date', 'Pelaksanaan harus lebih besar dari pendaftaran akhir');
            }

            if ($request->activity_end_date <= $request->registration_end_date) {
                $validator->errors()->add('activity_end_date', 'Pelaksanaan harus lebih besar dari pendaftaran akhir');
            }
        });

        return $validator;
    }

    public function changeStatus(Request $request)
    {
        try {
            $activity = MasterActivity::find(Crypt::decrypt($request->id));
            $activity->is_active = $request->status;
            $activity->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status berhasil diubah',
                'data' => $activity
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function edit(Request $request)
    {
        try {
            $activity = MasterActivity::find(Crypt::decrypt($request->id));
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $activity
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $activity = MasterActivity::find(Crypt::decrypt($request->id));
            $activity->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
