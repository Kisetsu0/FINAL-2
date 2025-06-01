<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\LampuMingguanController;
use App\Http\Controllers\RegresiController;




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

Route::get('/data-sensor', [SensorController::class, 'index']);

Route::get('/prediksi/manual', [PrediksiController::class, 'analisisRegresi'])->name('prediksi.manual');

Route::get('/rekap-mingguan', [LampuMingguanController::class, 'rekapMingguan'])->name('rekap.mingguan');
Route::get('/grafik', [RegresiController::class, 'index']);


Route::get('/riwayat', function () {
    $data = \App\Models\SensorData::orderBy('id', 'desc')->paginate(7); // tampilkan 10 per halaman
    return view('riwayat', compact('data'));
});


