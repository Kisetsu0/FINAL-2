<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Prediksi Lampu Operasi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
        h2 { margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .chart-container { text-align: center; margin-top: 20px; }
        .chart-container img { max-width: 100%; height: auto; }
    </style>
</head>
<body>

    <h2>Laporan Prediksi Maintenance Lampu Operasi</h2>

    <div class="section">
        <p><strong>Nilai Regresi:</strong><br>
        A (kemiringan): {{ number_format($a, 4) }} <br>
        B (intersep): {{ number_format($b, 2) }} <br>
        Prediksi lampu redup di jam ke-{{ number_format($prediksi_jam, 2) }} <br>
        Diperkirakan minggu ke-{{ $prediksi_minggu }}</p>
    </div>

    @if(!empty($chartImage))
    <div class="chart-container">
        <h4>Grafik Regresi Intensitas Cahaya</h4>
        <img src="{{ $chartImage }}" alt="Grafik Regresi Lampu">
    </div>
    @endif

    <div class="section">
        <h4>Data Mingguan</h4>
        <table>
            <thead>
                <tr>
                    <th>Minggu Ke</th>
                    <th>Jam Pemakaian</th>
                    <th>Intensitas (Lux)</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr>
                    <td>{{ $d->minggu_ke }}</td>
                    <td>{{ $d->jam_pemakaian }}</td>
                    <td>{{ $d->lux }}</td>
                    <td>{{ $d->tanggal }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
