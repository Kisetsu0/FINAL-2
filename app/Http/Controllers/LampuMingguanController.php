<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\LampData;
use App\Models\RiwayatPrediksi;
use Carbon\Carbon;

class LampuMingguanController extends Controller
{
    public function rekapMingguan()
    {
        $data = SensorData::orderBy('created_at', 'desc')->limit(7)->get()->reverse();

        if ($data->count() < 7) {
            return redirect('/grafik')->with('error', 'Data kurang dari 7 hari.');
        }

        // Cek apakah minggu ini sudah direkap (gunakan tanggal terbaru di sensor_data)
        $last_date = $data->last()->created_at->format('Y-m-d');
        $sudahAda = LampData::where('tanggal', $last_date)->exists();

        if ($sudahAda) {
            return redirect('/grafik')->with('info', 'Data minggu ini sudah direkap.');
        }

        $total_jam = $data->sum('jam_pemakaian');
        $rata_lux = $data->avg('lux');
        $minggu_ke = LampData::max('minggu_ke') + 1;

        LampData::create([
            'minggu_ke' => $minggu_ke,
            'tanggal' => $last_date,
            'jam_pemakaian' => $total_jam,
            'lux' => $rata_lux,
        ]);

        // Regresi linear dari data mingguan (bukan harian lagi)
        $weekly = LampData::orderBy('minggu_ke')->get();

         $x = $weekly->pluck('jam_pemakaian')->toArray();
        $y = $weekly->pluck('lux')->toArray();
        $n = count($x);

        if ($n >= 2) {
            $sumX = array_sum($x);
            $sumY = array_sum($y);
            $sumXY = array_sum(array_map(fn($xi, $yi) => $xi * $yi, $x, $y));
            $sumX2 = array_sum(array_map(fn($xi) => $xi * $xi, $x));

            $denominator = $n * $sumX2 - $sumX * $sumX;

            if ($denominator != 0) {
                $a = ($n * $sumXY - $sumX * $sumY) / $denominator;
                $b = ($sumY - $a * $sumX) / $n;

                $lux_threshold = 40000;
                $prediksi_jam = ($a != 0) ? ($lux_threshold - $b) / $a : null;
                $prediksi_minggu = ($prediksi_jam !== null) ? ceil($prediksi_jam / 12 / 7) : null;

                RiwayatPrediksi::create([
                    'a' => $a,
                    'b' => $b,
                    'prediksi_jam' => $prediksi_jam,
                    'prediksi_minggu' => $prediksi_minggu,
                    'waktu_analisis' => now(),
                ]);
            }
        }


        return redirect('/grafik')->with('success', 'Rekap & analisis minggu ke-' . $minggu_ke . ' berhasil.');
    }
}
