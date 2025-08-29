<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Activity;
use App\Models\Prodis;
use Illuminate\Http\Request;
use App\Models\ActivityReport;
use Illuminate\Support\Facades\DB;
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
        $activities = Activity::where('is_active', 1)->first();

        $participants = ActivityParticipant::where('activity_id', $activities->id)->count();

        $activityReports = ActivityReport::where('activity_id', $activities->id)->count();

        $genderCount = DB::table('biodatas as b')
        ->select('b.gender',DB::raw('COUNT(*) as total'))
        ->join('activity_participants as ap','ap.user_id','=','b.id')
        ->where('ap.activity_id','=',$activities->id)
        ->groupBy('b.gender')
        ->get()
        ->pluck('total', 'gender')
        ->toArray();

        $participantsUser = DB::table('biodatas as b')
        ->select('b.*')
        ->join('activity_participants as ap','ap.user_id','=','b.id')
        ->where('ap.activity_id','=',$activities->id)
        ->get();        

        $getProdis = Prodis::all();

        $facultyChart = [];        

        foreach ($getProdis as $prodi) {
            $facultyChart[$prodi->kode_fakultas]['nama'] = 'Fakultas ' . $prodi->fakultas;
            $facultyChart[$prodi->kode_fakultas]['total'] = 0;
            $facultyChart[$prodi->kode_fakultas]['prodi'][trim($prodi->kode_prodi)] = ['nama' => $prodi->prodi, 'total' => 0];            
        }

        foreach ($participantsUser as $participant) {
            $facultyChart[$participant->fakultas_kode]['total']++;
            $facultyChart[$participant->fakultas_kode]['prodi'][trim($participant->prodi_kode)]['total']++;
        }        
        
        return view('superadmin.dashboard.index', compact('activities', 'participants', 'genderCount', 'activityReports', 'facultyChart'));
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
