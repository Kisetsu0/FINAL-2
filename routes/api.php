<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SensorHarianController;
use App\Models\SensorData;

Route::get('/store-data', function () {
    return response()->json(['message' => 'API berjalan. Gunakan metode POST untuk mengirim data.']);
});

Route::post('/store-data', [SensorController::class, 'storeData']);


Route::post('/sensor-harian', [SensorHarianController::class, 'store']);

Route::get('/get-latest-data', function () {
    $latestData = SensorData::latest()->first();
    return Response::json($latestData);
});

Route::post('/sensor-data', [SensorController::class, 'store']);

Route::get('/device-status', [SensorController::class, 'getConnectionStatus']);
