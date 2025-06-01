<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Prediksi Lampu Operasi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Prediksi Maintenance Lampu Operasi</h2>

    <p><strong>Nilai Regresi:</strong><br>
    A (kemiringan): {{ number_format($a, 4) }} <br>
    B (intersep): {{ number_format($b, 2) }} <br>
    Prediksi lampu redup di jam ke-{{ number_format($prediksi_jam, 2) }} <br>
    Diperkirakan minggu ke-{{ $prediksi_minggu }}</p>

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
</body>
</html>
