<?php

namespace App\Http\Controllers;

use App\Models\MasterActivity;
use Illuminate\Http\Request;

class MsActivityController extends Controller
{
    public function index()
    {
        $activities = MasterActivity::all();

        return $activities;
    }
}
