<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->get(); 

        try {
            if ($request->ajax()) {
                return datatables()->of($users)
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    })
                    ->addColumn('role', function ($row) {
                        $roles = '';
                        foreach ($row->roles as $role) {
                            $roles .= $role->name;
                        }
                        return $roles;
                    })
                    ->rawColumns(['action', 'role'])
                    ->make(true);
            }

            return view('superadmin.master.user.index');
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
