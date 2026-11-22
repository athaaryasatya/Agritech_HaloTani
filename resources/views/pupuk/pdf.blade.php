<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Pupuk - HaloTani</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #333333;
            margin: 20px;
            line-height: 1.4;
        }

        .header-container {
            border-bottom: 3px double #2e7d32;
            padding-bottom: 12px;
            margin-bottom: 20px;
            text-align: center;
        }

        .header-title {
            font-size: 18pt;
            font-weight: bold;
            color: #2e7d32;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-subtitle {
            font-size: 10pt;
            color: #555555;
            margin: 4px 0 0 0;
        }

        .header-meta {
            font-size: 8.5pt;
            color: #777777;
            margin-top: 4px;
        }

        .report-meta {
            margin-bottom: 15px;
            font-size: 9.5pt;
        }

        .report-meta table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-meta td {
            padding: 2px 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid #cccccc;
            padding: 8px 10px;
            font-size: 9.5pt;
        }

        table.data-table th {
            background-color: #2e7d32;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
        }

        table.data-table tr:nth-child(even) {
            background-color: #f8faf8;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge-warning {
            color: #c62828;
            font-weight: bold;
        }

        .badge-safe {
            color: #2e7d32;
            font-weight: bold;
        }

        .footer-summary {
            margin-top: 15px;
            font-size: 9.5pt;
        }

        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 10pt;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT / HEADER LAPORAN --}}
    <div class="header-container">
        <h1 class="header-title">AGRITECH HALOTANI</h1>
        <p class="header-subtitle">Sistem Manajemen Pertanian & Distribusi Saprotan Terintegrasi</p>
        <p class="header-meta">Jl. Serayu No. 84 Madiun, Jawa Timur | Email: support@halotani.id | Website: halotani.id</p>
    </div>

    {{-- INFO LAPORAN --}}
    <div class="report-meta">
        <table>
            <tr>
                <td style="width: 20%; font-weight: bold;">Jenis Dokumen</td>
                <td style="width: 30%;">: Laporan Inventaris Pupuk</td>
                <td style="width: 20%; font-weight: bold;">Tanggal Cetak</td>
                <td style="width: 30%;">: {{ $tanggal }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Modul Sistem</td>
                <td>: Modul 10 (Ekspor Dokumen PDF)</td>
                <td style="font-weight: bold;">Status Data</td>
                <td>: Sinkronisasi Pangkalan Data</td>
            </tr>
        </table>
    </div>

    {{-- TABEL DATA PUPUK --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Pupuk</th>
                <th style="width: 20%;">Jenis</th>
                <th style="width: 12%;">Stok</th>
                <th style="width: 13%;">Satuan</th>
                <th style="width: 15%;">Status Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pupuks as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->nama }}</strong></td>
                    <td class="text-center">{{ $item->jenis }}</td>
                    <td class="text-center">{{ number_format($item->stok, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-center">
                        @if($item->stok <= 5)
                            <span class="badge-warning">Hampir Habis</span>
                        @else
                            <span class="badge-safe">Tersedia</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Belum ada data inventaris pupuk.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f1; font-weight: bold;">
                <td colspan="3" class="text-right">Total Macam Pupuk:</td>
                <td class="text-center">{{ count($pupuks) }} Item</td>
                <td colspan="2" class="text-center">Total Stok: {{ $pupuks->sum('stok') }} Unit</td>
            </tr>
        </tfoot>
    </table>

    {{-- TANDA TANGAN / PENGESAHAN --}}
    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Koordinator Pengelola Saprotan
                <div class="signature-space"></div>
                <span class="signature-name">Dr. Ir. Budi Santoso, M.P.</span><br>
                NIP. 19780512 200312 1 002
            </td>
            <td>
                Madiun, {{ date('d F Y') }}<br>
                Pengembang Sistem (PjBL)
                <div class="signature-space"></div>
                <span class="signature-name">Leo Rhamanata Adrio</span><br>
                NIM. 254311012
            </td>
        </tr>
    </table>

</body>
</html>
