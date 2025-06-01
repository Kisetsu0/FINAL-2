<?php

namespace App\Http\Controllers;

use App\Models\LampData;
use Illuminate\Http\Request;
use App\Services\RegresiLinearService;
use PDF;

class RegresiController extends Controller
{
    public function index()
    {
        $data = LampData::orderBy('minggu_ke', 'asc')->get();

        if ($data->isEmpty()) {
            return view('grafik', ['message' => 'Belum ada data rekap mingguan.']);
        }

        $a = null;
        $b = null;
        $prediksi_jam = null;
        $prediksi_minggu = null;

        if ($data->count() >= 2) {
            $x = $data->pluck('jam_pemakaian')->toArray();
            $y = $data->pluck('lux')->toArray();

            $regresi = RegresiLinearService::hitungRegresi($x, $y);

            if ($regresi) {
                $a = $regresi['a'];
                $b = $regresi['b'];

                $lux_threshold = 40000;
                $prediksi_jam = RegresiLinearService::prediksiJam($a, $b, $lux_threshold);
                $prediksi_minggu = RegresiLinearService::prediksiMinggu($prediksi_jam);
            }
        }
        return view('grafik', compact('data', 'a', 'b', 'prediksi_jam', 'prediksi_minggu'));
    }

    public function exportPDF()
{
    $data = LampData::orderBy('minggu_ke', 'asc')->get();

    if ($data->count() < 2) {
        return redirect()->back()->with('error', 'Data tidak cukup untuk membuat prediksi.');
    }

    $x = $data->pluck('jam_pemakaian')->toArray();
    $y = $data->pluck('lux')->toArray();

    $regresi = \App\Services\RegresiLinearService::hitungRegresi($x, $y);

    $a = $regresi['a'];
    $b = $regresi['b'];
    $lux_threshold = 40000;
    $prediksi_jam = \App\Services\RegresiLinearService::prediksiJam($a, $b, $lux_threshold);
    $prediksi_minggu = \App\Services\RegresiLinearService::prediksiMinggu($prediksi_jam);

    $pdf = PDF::loadView('export_pdf', compact('data', 'a', 'b', 'prediksi_jam', 'prediksi_minggu'))
        ->setPaper('a4', 'portrait');

    return $pdf->download('prediksi_lampu_operasi.pdf');
}
}
