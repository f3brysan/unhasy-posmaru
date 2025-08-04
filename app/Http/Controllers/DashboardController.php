<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    }
}
