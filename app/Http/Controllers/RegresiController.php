<?php

namespace App\Http\Controllers;

use App\Models\LampData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatPrediksi;

class RegresiController extends Controller
{
    public function index()
{
    $data = LampData::orderBy('minggu_ke', 'asc')->get();

    if ($data->count() == 0) {
        return view('grafik', ['message' => 'Belum ada data rekap mingguan.']);
    }

    $a = null;
    $b = null;
    $prediksi_jam = null;
    $prediksi_minggu = null;

    if ($data->count() >= 2) {
        $x = $data->pluck('jam_pemakaian')->toArray();
        $y = $data->pluck('lux')->toArray();
        $n = count($x);
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = array_sum(array_map(fn($xi, $yi) => $xi * $yi, $x, $y));
        $sumX2 = array_sum(array_map(fn($xi) => $xi * $xi, $x));

        $a = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $b = ($sumY - $a * $sumX) / $n;

        $lux_threshold = 40000;
        $prediksi_jam = ($a != 0) ? ($lux_threshold - $b) / $a : null;
        $prediksi_minggu = ceil($prediksi_jam / 12 / 7);
    }

    return view('grafik', compact('data', 'a', 'b', 'prediksi_jam', 'prediksi_minggu'));
}

}
