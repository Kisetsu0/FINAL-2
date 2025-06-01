<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">

</head>
<body>

    <div class="container">
        <h2 class="mb-4">Riwayat Semua Data Sensor</h2>
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
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->lux }}</td>
                    <td>{{ $row->distance }}</td>
                    <td class="{{ $row->status === 'Redup' ? 'status-redup' : 'status-normal' }}">
                        {{ $row->status }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('H:i:s - d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
        <div class="text-start mt-3">
            <a href="/data-sensor" class="btn btn-primary">🔙 Kembali</a>
        </div>
    </div>  
</body>
</html>