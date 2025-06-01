document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('chartLampu');
    if (!canvas) return;

    const xValues = JSON.parse(canvas.dataset.xvalues);
    const yActual = JSON.parse(canvas.dataset.yactual);
    const labels = JSON.parse(canvas.dataset.minggu);
    const a = parseFloat(canvas.dataset.a);
    const b = parseFloat(canvas.dataset.b);
    const threshold = 40000;

    const yRegresi = xValues.map(x => a * x + b);

    const minY = Math.min(...yActual, ...yRegresi, threshold);
    const maxY = Math.max(...yActual, ...yRegresi, threshold);

    const ctx = canvas.getContext('2d');
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
});
