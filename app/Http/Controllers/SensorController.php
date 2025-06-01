<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SensorData;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function store(Request $request)
    
    {
        $validatedData = $request->validate([
            'lux' => 'required|numeric',
            'distance' => 'required|numeric',
            'status' => 'required|string',
        ]);
    
        // Simpan ke database
        $sensorData = SensorData::create($validatedData);
    
        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $sensorData
        ], 200);
        
    }
    public function index()
    {
        $sensorData = SensorData::orderBy('id', 'asc')->get();
        return view('sensor.index', compact('sensorData'));
    }

    public function storeData(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'lux' => 'required|numeric',
            'distance' => 'required|numeric',
            'status' => 'required|string',
        ]);
        
        $sensorData = SensorData::create($validatedData);
        return response()->json([
            'message' => 'Data berhasil diterima',
            'data' => $validatedData
        ], 201);
    }

    public function getLatestData()
    {
    $sensorData = SensorData::latest()->first();
    
    if ($sensorData) {
        return response()->json([
            'id' => $sensorData->id,
            'lux' => $sensorData->lux,
            'distance' => $sensorData->distance,
            'status'=> $sensorData->status,
            'created_at' => Carbon::parse($sensorData->created_at)->locale('id')->isoFormat('HH:mm:ss DD-MM-YYYY')
        ]);
    }

    return response()->json(null);
    }

    public function getConnectionStatus()
    {
        $latest = SensorData::latest()->first();

        if (!$latest) {
            return response()->json(['status' => 'Tidak Terhubung']);
        }

        $now = Carbon::now();
        $lastUpdated = Carbon::parse($latest->created_at);
        $diff = $now->diffInSeconds($lastUpdated);

        // Jika update terakhir lebih dari 70 detik, anggap tidak terhubung
        if ($diff > 70) {
            return response()->json(['status' => 'Tidak Terhubung']);
        } else {
            return response()->json(['status' => 'Terhubung']);
        }
    }

}
