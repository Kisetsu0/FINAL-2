<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Regresi Intensitas Lampu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/grafik.css') }}">
    <script src="{{ asset('js/grafik.js') }}"></script>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4">Grafik Regresi Intensitas Cahaya Lampu Operasi</h3>

    @if(isset($a) && isset($b) && $data->count() >= 2)
        <canvas id="chartLampu"
                data-xvalues='@json($data->pluck("jam_pemakaian"))'
                data-yactual='@json($data->pluck("lux"))'
                data-minggu='@json($data->pluck("minggu_ke")->map(fn($v) => "Minggu " . $v))'
                data-a="{{ $a }}"
                data-b="{{ $b }}"
        ></canvas>

        <div class="info-box mt-4">
            <p><strong>Persamaan Regresi:</strong> y = <span>{{ round($a, 2) }}</span>x + <span>{{ round($b, 2) }}</span></p>
            <p><strong>Prediksi redup:</strong> Setelah <strong>{{ round($prediksi_jam, 2) }} jam</strong> (~ Minggu ke-<strong>{{ $prediksi_minggu }}</strong>)</p>
            <p><small>Catatan: Garis kuning menunjukkan ambang batas <strong>40.000 lux</strong>. Di bawah itu disarankan maintenance atau penggantian lampu.</small></p>
        </div>
    @else
        <div class="alert alert-warning">
            Belum cukup data untuk menampilkan grafik regresi.
        </div>
    @endif

    <a href="/data-sensor" class="btn btn-secondary mt-3">🔙 Kembali ke Data</a>
</div>
</body>
</html>
