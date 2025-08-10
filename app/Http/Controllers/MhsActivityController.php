<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityReport;
use App\Models\ActivityParticipant;
use Illuminate\Support\Facades\Crypt;

class MhsActivityController extends Controller
{
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $activity = ActivityParticipant::with('activity')->where('activity_id', $id)->first();        

        $time['start'] = date('Y-m-d').' '.$activity->activity->student_report_start;
        $time['end'] = date('Y-m-d').' '.$activity->activity->student_report_end;        
        
        return view('activity.mahasiswa.show', compact('activity', 'time'));
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
                        $button = '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Edit</a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
        
    }
}
