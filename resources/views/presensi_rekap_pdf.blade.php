<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Presensi</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            vertical-align: middle;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
        }

        .foto {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Rekap Data Presensi</h1>
        <p>Tanggal Cetak: {{ date('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th> {{-- Tambah kolom foto --}}
                <th>Nama</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Check In</th>
                <th>Status</th>
                <th>Alasan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($presensi as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{-- Logika untuk menampilkan foto --}}
                        @php
                            $fotoPath = $item->foto_lokasi ? str_replace('public/', '', $item->foto_lokasi) : null;
                        @endphp

                        @if ($fotoPath)
                            {{-- Gunakan public_path() untuk mendapatkan path absolut server --}}
                            <img src="{{ public_path('storage/' . $fotoPath) }}" alt="Foto" class="foto">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>{{ $item->clock_in ? $item->clock_in->format('H:i:s') : '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->catatan ? $item->catatan : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>