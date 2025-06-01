<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Grafik Regresi Intensitas Lampu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        canvas { max-width: 100%; height: auto; margin-top: 20px; }
        .info-box { background: #f8f9fa; padding: 15px; border-radius: 8px; }
    </style>
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
            data-b="{{ $b }}">
        </canvas>

        <div class="info-box mt-4">
            <p><strong>Persamaan Regresi:</strong> y = <span>{{ round($a, 2) }}</span>x + <span>{{ round($b, 2) }}</span></p>
            <p><strong>Prediksi redup:</strong> Setelah <strong>{{ round($prediksi_jam, 2) }} jam</strong> (~ Minggu ke-<strong>{{ $prediksi_minggu }}</strong>)</p>
            <p><small>Catatan: Garis kuning menunjukkan ambang batas <strong>40.000 lux</strong>. Di bawah itu disarankan maintenance atau penggantian lampu.</small></p>
        </div>

        <!-- Tombol Ekspor PDF dengan Grafik -->
        <form id="exportForm" action="{{ route('export.pdf.withchart') }}" method="POST">
            @csrf
            <input type="hidden" name="chart_image" id="chart_image">
            <button type="submit" class="btn btn-danger mt-3">🖨️ Ekspor PDF (dengan Grafik)</button>
        </form>
    @else
        <div class="alert alert-warning">
            Belum cukup data untuk menampilkan grafik regresi.
        </div>
    @endif

    <a href="/data-sensor" class="btn btn-secondary mt-3">🔙 Kembali ke Data</a>
</div>

<!-- Script Chart.js + Simpan Gambar -->
<script>
    const canvas = document.getElementById('chartLampu');
    const ctx = canvas.getContext('2d');

    const xValues = JSON.parse(canvas.dataset.xvalues);
    const yValues = JSON.parse(canvas.dataset.yactual);
    const mingguLabels = JSON.parse(canvas.dataset.minggu);
    const a = parseFloat(canvas.dataset.a);
    const b = parseFloat(canvas.dataset.b);

    // Hitung regresi prediksi y berdasarkan a dan b
    const yPrediksi = xValues.map(x => a * x + b);
    const luxThreshold = 40000;
    const thresholdLine = Array(xValues.length).fill(luxThreshold);

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: mingguLabels,
            datasets: [
                {
                    label: 'Data Aktual (Lux)',
                    data: yValues,
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    tension: 0.3
                },
                {
                    label: 'Garis Regresi',
                    data: yPrediksi,
                    borderColor: 'green',
                    borderDash: [5, 5],
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Ambang Batas (40.000 lux)',
                    data: thresholdLine,
                    borderColor: 'orange',
                    borderWidth: 2,
                    borderDash: [2, 2],
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { enabled: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Siapkan ekspor sebagai gambar ke input hidden saat form dikirim
    const form = document.getElementById('exportForm');
    form?.addEventListener('submit', function () {
        const imageData = canvas.toDataURL('image/png');
        document.getElementById('chart_image').value = imageData;
    });
</script>

</body>
</html>
