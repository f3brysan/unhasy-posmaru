<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\ActivityReport;
use App\Models\ActivityParticipant;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();

        switch ($role) {
            case 'mahasiswa':
                return $this->mahasiswaDashboard();

            case 'baak':
                return $this->baakDashboard();

            case 'pimpinan':
                return $this->pimpinanDashboard();

            default:
                return $this->superadminDashboard();
        }

    }

    public function superadminDashboard()
    {
        return view('superadmin.dashboard.index');
    }

    public function mahasiswaDashboard()
    {
        $myActivities = ActivityParticipant::with('activity')->where('user_id', auth()->user()->id)->first();
        $activities = Activity::where('is_active', 1)->get();
        $myReports = ActivityReport::where('user_id', auth()->user()->id)->get();
        
        $biodata = Biodata::with('prodi', 'fakultas', 'user')->where('id', auth()->user()->id)->first();        
        
        return view('dashboard.mahasiswa', compact('myActivities', 'activities', 'biodata', 'myReports'));
    }
}
