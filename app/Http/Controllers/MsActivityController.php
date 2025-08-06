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
}
