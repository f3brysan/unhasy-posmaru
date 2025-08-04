<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiakaduController;
use App\Http\Controllers\DashboardController;

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
    Route::get('dashboard', [DashboardController::class, 'index']);    
});

// Auth
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('auth', [AuthController::class, 'auth']);
Route::get('register', [AuthController::class, 'register']);
Route::post('store-register', [AuthController::class, 'storeRegister']);

// API SIAKADU
Route::post('api/siakadu/get-data/mahasiswa', [SiakaduController::class, 'getDataMahasiswa']);
