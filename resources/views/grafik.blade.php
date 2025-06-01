<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Regresi Intensitas Lampu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            padding: 40px 0;
        }
        .container {
            max-width: 1000px;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin: auto;
        }
        .info-box {
            background: #f0f4f7;
            padding: 15px 20px;
            border-left: 5px solid #3498db;
            border-radius: 8px;
            margin-top: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="text-center mb-4">Grafik Regresi Intensitas Cahaya Lampu Operasi</h3>

    @if(isset($a) && isset($b) && $data->count() >= 2)
        <canvas id="chartLampu"></canvas>

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

@if(isset($a) && isset($b) && $data->count() >= 2)
<script>
    const xValues = @json($data->pluck('jam_pemakaian'));
    const yActual = @json($data->pluck('lux'));
    const yRegresi = xValues.map(x => {{ $a }} * x + {{ $b }});
    const threshold = 40000;
    const labels = @json($data->pluck('minggu_ke')->map(fn($v) => 'Minggu ' . $v));

    const minY = Math.min(...yActual, ...yRegresi, threshold);
    const maxY = Math.max(...yActual, ...yRegresi, threshold);

    const ctx = document.getElementById('chartLampu').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Data Lux Asli',
                    data: yActual,
                    borderColor: '#2980b9',
                    backgroundColor: '#2980b9',
                    tension: 0.3,
                    pointRadius: 5,
                    fill: false
                },
                {
                    label: 'Garis Regresi',
                    data: yRegresi,
                    borderColor: '#e74c3c',
                    borderDash: [6, 6],
                    tension: 0,
                    fill: false
                },
                {
                    label: 'Threshold 40.000 Lux',
                    data: new Array(xValues.length).fill(threshold),
                    borderColor: '#f1c40f',
                    borderDash: [3, 3],
                    pointRadius: 0,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Prediksi Penurunan Intensitas Lampu Berdasarkan Jam Pemakaian',
                    font: {
                        size: 18
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `(${ctx.label}, ${ctx.formattedValue} lux)`
                    }
                },
                legend: {
                    position: 'bottom'
                }
            },
            interaction: {
                mode: 'nearest',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Minggu ke-'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Intensitas Cahaya (lux)'
                    },
                    suggestedMin: minY - 2000,
                    suggestedMax: maxY + 2000
                }
            }
        }
    });
</script>
@endif

</body>
</html>
