<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor Real-Time</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    <div class="container">
        <h1>Data Sensor Real-Time</h1>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <p style="margin: 0;"><b>Waktu Sekarang:</b> <span id="current-time"></span></p>
            <div id="device-status" style="font-weight:bold;">Status ESP32: Memuat...</div>
        </div>    
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Lux</th>
                    <th>Jarak (cm)</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody id="sensor-table-body">
                <!-- Data masuk otomatis -->
            </tbody>
        </table>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div class="text-end mt-3">
                <a href="/" class="btn btn-primary">🔙 Kembali</a>
            </div>
                <div class="text-end mt-3">
                <a href="/riwayat" class="btn btn-primary"> 📜 Lihat Semua Riwayat</a>
            </div>
                <div class="text-end mt-3">
                <a href="/grafik" class="btn btn-primary">📈 Lihat Grafik Regresi</a>
            </div>
                <div class="text-end mt-3">
                <a href="{{ route('rekap.mingguan') }}" class="btn btn-warning">🔁 Rekap Mingguan (7 Hari)</a>
            </div>
        </div> 
    </div>

    <script>
        function updateTime() {
            let now = new Date();
            let formattedTime = now.toLocaleTimeString('id-ID', { hour12: false }) + " - " +
                                now.toLocaleDateString('id-ID');
            document.getElementById("current-time").innerText = formattedTime;
        }

        function fetchSensorData() {
            $.ajax({
                url: "http://192.168.1.4:8000/api/get-latest-data",
                method: "GET",
                success: function(data) {
                    if (data) {
                        let formattedTime = new Date(data.created_at).toLocaleTimeString('id-ID', { hour12: false }) + " - " +
                                            new Date(data.created_at).toLocaleDateString('id-ID');

                        let statusClass = data.status === "Redup" ? "status-redup" : "status-normal";
                        let statusText = data.status;

                        let newRow = `
                            <tr>
                                <td>${data.id}</td>
                                <td>${data.lux}</td>
                                <td>${data.distance}</td>
                                <td class="${statusClass}">${statusText}</td>
                                <td>${formattedTime}</td>
                            </tr>
                        `;

                        // Cek duplikasi
                        $("#sensor-table-body tr").each(function () {
                            if ($(this).find("td:first").text() === String(data.id)) {
                                $(this).remove();
                            }
                        });

                        // Tambahkan baris baru di awal
                        $("#sensor-table-body").prepend(newRow);

                        // Batasi hanya 5 data terbaru
                        const rows = $("#sensor-table-body tr");
                        if (rows.length > 5) {
                            rows.slice(5).remove(); // Hapus yang lebih dari 5
                        }
                    }
                },
                error: function () {
                    console.log("Gagal mengambil data sensor.");
                }
            });
        }


        function fetchDeviceStatus() {
            $.ajax({
                url: "http://192.168.1.4:8000/api/device-status",
                method: "GET",
                success: function(data) {
                    let statusElem = $("#device-status");
                    if (data.status === "Terhubung") {
                        statusElem.text("Status ESP32: Terhubung ✅");
                        statusElem.css("color", "green");
                    } else {
                        statusElem.text("Status ESP32: Tidak Terhubung ❌");
                        statusElem.css("color", "red");
                    }
                },
                error: function() {
                    $("#device-status").text("Status ESP32: Gagal Mengecek").css("color", "gray");
                }
            });
        }

        setInterval(fetchSensorData, 3000);  // Update tabel setiap n detik
        setInterval(updateTime, 1000);  // Update jam real-time setiap n detik
        setInterval(fetchDeviceStatus, 1000);
    </script>

</body>
</html>
