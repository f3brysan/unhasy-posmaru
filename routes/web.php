<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SiakaduController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MsActivityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('beranda', [DashboardController::class, 'index']);    
});

Route::middleware(['auth:web', 'role:superadmin'])->group(function () {
    Route::get('master/pengguna', [UserController::class, 'index']);    
    Route::post('master/pengguna/reset-password', [UserController::class, 'resetPassword']);    
});

Route::middleware(['auth:web', 'role:superadmin|baak'])->group(function () {
    Route::get('kegiatan', [MsActivityController::class, 'index']);        
    Route::post('kegiatan/store', [MsActivityController::class, 'store']);        
});

// Auth
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login-as', [AuthController::class, 'loginAs']);
Route::post('auth', [AuthController::class, 'auth']);
Route::get('register', [AuthController::class, 'register']);
Route::post('store-register', [AuthController::class, 'storeRegister']);

// API SIAKADU
Route::post('api/siakadu/get-data/mahasiswa', [SiakaduController::class, 'getDataMahasiswa']);
