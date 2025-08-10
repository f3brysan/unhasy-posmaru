<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->get();

        $roles = Role::where('name', '!=', 'mahasiswa')->get();

        try {
            if ($request->ajax()) {
                return datatables()->of($users)
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-original-title="Edit" class="mx-auto btn btn-warning btn-sm resetPassword">Reset</a>';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-name="'.$row->name.'" data-original-title="Login As" class="mx-auto btn btn-secondary btn-sm loginAs">Login As</a>';
                        foreach ($row->roles as $role) {
                            if ($role->name != 'mahasiswa') {
                                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-name="'.$row->name.'" data-original-title="Edit" class="mx-auto btn btn-primary btn-sm editUser">Edit</a>';
                            }
                        }
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.Crypt::encrypt($row->id).'" data-original-title="Delete" class="mx-auto btn btn-danger btn-sm deleteUser">Delete</a>';
                        return $btn;
                    })
                    ->addColumn('role', function ($row) {
                        $roles = '';
                        foreach ($row->roles as $role) {
                            switch ($role->name) {
                                case 'superadmin':
                                    $roles = '<span class="badge bg-primary">Superadmin</span>';
                                    break;
                                case 'baak':
                                    $roles = '<span class="badge bg-success">BAAK</span>';
                                    break;

                                case 'mahasiswa':
                                    $roles = '<span class="badge bg-info">Mahasiswa</span>';
                                    break;
                                default:
                                    $roles = '<span class="badge bg-secondary">Pimpinan</span>';
                            }
                        }
                        return $roles;
                    })
                    ->rawColumns(['action', 'role'])
                    ->make(true);
            }

            return view('superadmin.master.user.index', compact('roles'));
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
    
    public function store(Request $request)
    {
        try {
            // Check if the user ID is empty, indicating a new user
            if (empty($request->id)) {
                // Check if a user with the same No Induk already exists
                $cekUser = User::where('no_induk', $request->no_induk)->first();
                if ($cekUser) {
                    // Return error response if user already exists
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pengguna dengan No Induk ' . $request->no_induk . ' sudah ada',
                    ], 400);
                }
            }

            // Update existing user or create a new one
            $user = User::updateOrCreate([
                'id' => $request->id ? Crypt::decrypt($request->id) : null,
            ], [
                'no_induk' => $request->no_induk,
                'name' => $request->name,
                'email' => $request->no_induk . '@unhasy.ac.id',
                'password' => bcrypt($request->no_induk),
            ]);

            // Assign or sync roles based on the user ID
            if (empty($request->id)) {
                $user->assignRole($request->role);
            } else {
                $user->syncRoles($request->role);
            }

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => empty($request->id) ? 'Pengguna berhasil ditambahkan' : 'Pengguna berhasil diubah',
            ], 200);
        } catch (\Throwable $th) {
            // Return error response if an exception occurs
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
    
    public function edit(Request $request)
    {
        try {
            // Find the user by ID
            $user = User::with('roles')
                ->findOrFail(
                    // Decrypt the ID
                    Crypt::decrypt($request->id)
                );

            // Return the response
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengambil data pengguna',
                'data' => $user,
            ], 200);
        } catch (\Throwable $th) {
            // Catch the error
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function getParticipant(Request $request)
    {
        try {
            $participant = User::where('no_induk', $request->nim)->first();
            if (!$participant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mahasiswa tidak ditemukan',
                ], 400);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengambil data mahasiswa',
                'data' => $participant,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 400);
        }
    }
}
