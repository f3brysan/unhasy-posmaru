<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->get();

        try {
            if ($request->ajax()) {
                return datatables()->of($users)
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-original-title="Edit" class="mx-auto btn btn-warning btn-sm resetPassword">Reset</a>';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-name="'.$row->name.'" data-original-title="Login As" class="mx-auto btn btn-secondary btn-sm loginAs">Login As</a>';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-original-title="Delete" class="mx-auto btn btn-danger btn-sm deleteUser">Delete</a>';
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

    /**
     * Reset the password for the specified user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            // Decrypt the user ID from the request
            $userId = Crypt::decrypt($request->id);
            $user = User::findOrFail($userId);

            // Find the user by ID
            $user = User::findOrFail($userId);
            $newPassword = bcrypt($user->no_induk);
            $user->update(['password' => $newPassword]);

            // Generate a new password using the user's no_induk
            $newPassword = bcrypt($user->no_induk);

            // Update the user's password
            $user->update(['password' => $newPassword]);

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => "Password {$user->name} berhasil direset",
            ]);
            
        } catch (\Throwable $th) {
            // Return an error response if an exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }
}
