<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\ActivityReport;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

/**
 * Master Activity Controller
 * 
 * This controller handles all CRUD operations for master activities including:
 * - Displaying activities list with DataTables
 * - Creating and updating activities
 * - Changing activity status (active/inactive)
 * - Editing and deleting activities
 * 
 * @package App\Http\Controllers
 */
class MsActivityController extends Controller
{
    /**
     * Display the activities index page with DataTables support
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get all activities from database
        $activities = Activity::all();

        try {
            // Check if request is AJAX (DataTables request)
            if ($request->ajax()) {
                return datatables()->of($activities)
                    // Add action buttons column (Edit, Delete, Status Toggle)
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="btn-group" role="group" aria-label="Aksi">';
                        // Show button with encrypted ID (eye icon)
                        $btn .= '<a href="'.URL::to('kegiatan/show/'.Crypt::encrypt($row->id)).'" target="_blank" data-toggle="tooltip" title="Lihat" class="btn btn-info btn-sm">
                                    <i class="ti ti-eye"></i>
                                </a>';
                        // Edit button with encrypted ID (pencil icon)
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encrypt($row->id).'" title="Edit" class="btn btn-warning btn-sm edit">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                        // Delete button with encrypted ID (trash icon)
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encrypt($row->id).'" title="Delete" class="btn btn-danger btn-sm delete">
                                    <i class="ti ti-trash"></i>
                                </a>';
                        // Status toggle button based on current status
                        if ($row->is_active == 1) {
                            // If active, show "Non Aktifkan" (Deactivate) button (toggle-left icon)
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encrypt($row->id).'" data-status="0" title="Non Aktifkan" class="btn btn-success btn-sm change-status">
                                        <i class="ti ti-toggle-right"></i>
                                    </a>';
                        } else {
                            // If inactive, show "Aktifkan" (Activate) button (toggle-right icon)
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.Crypt::encrypt($row->id).'" data-status="1" title="Aktifkan" class="btn btn-secondary btn-sm change-status">
                                        <i class="ti ti-toggle-left"></i>
                                    </a>';
                        }
                        $btn .= '</div>';

                        return $btn;                        
                    })
                    // Add participant count column (hardcoded to 99 for now)
                    ->addColumn('peserta', function ($row) {
                        return 99;
                    })
                    // Add status badge column
                    ->addColumn('status_btn', function ($row) {
                        if ($row->is_active == 1) {
                            return '<span class="badge bg-success">Aktif</span>';
                        } else {
                            return '<span class="badge bg-secondary">Non Aktif</span>';
                        }
                    })
                    // Specify which columns contain raw HTML
                    ->rawColumns(['action', 'peserta', 'status_btn'])
                    ->make(true);
            }

            // Return view for non-AJAX requests
            return view('activity.index');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store or update activity data
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request data
            $validator = $this->validateActivity($request);

            // If validation fails, return error response
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Create new activity or update existing one based on ID
            $updateOrCreate = Activity::updateOrCreate(
                [
                    'id' => $request->id  // Search condition
                ],
                [
                    // Activity details
                    'name' => $request->name,
                    'year' => $request->year,
                    'activity_start_date' => $request->activity_start_date,
                    'activity_end_date' => $request->activity_end_date,
                    'registration_start_date' => $request->registration_start_date,
                    'registration_end_date' => $request->registration_end_date,
                    'student_report_start' => $request->student_report_start,
                    'student_report_end' => $request->student_report_end,
                    'updated_by' => auth()->user()->name  // Track who updated the record
                ]
            );

            // Return success response with saved data
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan',
                    'data' => $updateOrCreate
                ],
                200
            );
        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Validate activity data with custom business rules
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateActivity(Request $request)
    {
        // Define validation rules for each field
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

        // Custom error messages in Indonesian
        $messages = [
            'name.required' => 'Nama kegiatan wajib diisi',
            'year.required' => 'Tahun wajib diisi',
            'activity_end_date.after_or_equal' => 'Pelaksanaan akhir harus lebih besar dari atau sama dengan pelaksanaan awal',
            'registration_end_date.after_or_equal' => 'Pendaftaran akhir harus lebih besar dari atau sama dengan pendaftaran awal',
            'student_report_end.after_or_equal' => 'Absensi akhir harus lebih besar dari atau sama dengan absensi awal',
        ];

        // Create validator instance
        $validator = Validator::make($request->all(), $rules, $messages);

        // Add custom validation rules for date ranges
        $validator->after(function ($validator) use ($request) {
            // Business rule: Activity dates must be after registration end date
            if ($request->activity_start_date <= $request->registration_end_date) {
                $validator->errors()->add('activity_start_date', 'Pelaksanaan harus lebih besar dari pendaftaran akhir');
            }

            if ($request->activity_end_date <= $request->registration_end_date) {
                $validator->errors()->add('activity_end_date', 'Pelaksanaan harus lebih besar dari pendaftaran akhir');
            }
        });

        return $validator;
    }

    /**
     * Change activity status (active/inactive)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        try {
            // Find activity by decrypted ID
            $activity = Activity::find(Crypt::decrypt($request->id));
            
            // Update the status
            $activity->is_active = $request->status;
            $activity->save();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Status berhasil diubah',
                'data' => $activity
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get activity data for editing
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        try {
            // Find activity by decrypted ID
            $activity = Activity::find(Crypt::decrypt($request->id));
            
            // Return activity data for editing
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diambil',
                'data' => $activity
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Delete activity
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            // Find activity by decrypted ID
            $activity = Activity::find(Crypt::decrypt($request->id));
            
            // Delete the activity
            $activity->delete();
            
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Request $request)
    {
        $activity = Activity::find(Crypt::decrypt($request->id));

        $reports = [];
        if ($activity->activity_start_date && $activity->activity_end_date && $activity->activity_start_date <= $activity->activity_end_date) {
            for ($date = $activity->activity_start_date; $date <= $activity->activity_end_date; $date++) {
                $reports[$date] = [];
            }
        }        

        $activityReports = ActivityReport::with(['user.biodata.prodi', 'user.biodata.fakultas'])->where('activity_id', $activity->id)->get();
        
        foreach ($activityReports as $report) {
            $reports[$report->tgl_setor][] = $report;
        }        

        return view('activity.show', compact('activity', 'reports'));
    }
}
