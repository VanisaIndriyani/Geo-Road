<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geo-Road - Laporan Data Jalan</title>
    <style>
        body{font-family:DejaVu Sans, sans-serif;font-size:12px;color:#111827}
        .title{font-size:16px;font-weight:700;margin:0 0 6px}
        .sub{color:#6b7280;margin:0 0 14px}
        table{width:100%;border-collapse:collapse}
        th,td{border:1px solid #e5e7eb;padding:6px 8px;vertical-align:top}
        th{background:#f3f4f6;text-align:left}
        .right{text-align:right}
    </style>
</head>
<body>
    <div class="title">Geo-Road — Laporan Data Kerusakan Jalan</div>
    <div class="sub">Dinas Bina Marga dan Bina Konstruksi Provinsi Lampung • Generated: {{ $generatedAt->format('d/m/Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th style="width:28px">No</th>
                <th>Nama Ruas</th>
                <th>Kabupaten</th>
                <th>Kecamatan</th>
                <th class="right">Panjang</th>
                <th>Kondisi</th>
                <th>Prioritas</th>
                <th>Tahun</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roads as $i => $r)
                <tr>
                    <td class="right">{{ $i + 1 }}</td>
                    <td>{{ $r->nama_ruas }}</td>
                    <td>{{ $r->kabupaten }}</td>
                    <td>{{ $r->kecamatan }}</td>
                    <td class="right">{{ number_format((float) $r->panjang, 2, ',', '.') }} Km</td>
                    <td>{{ $r->kondisi }}</td>
                    <td>{{ $r->prioritas ?? '-' }}</td>
                    <td>{{ $r->tahun ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

