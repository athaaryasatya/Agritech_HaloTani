<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pupuk - HaloTani</title>
    <style>
        body { font-family: sans-serif; margin: 30px; background-color: #f9f9f9; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2e7d32; color: white; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; }
        .btn-green { background: #2e7d32; color: white; }
        .btn-blue { background: #0288d1; color: white; }
        .btn-red { background: #d32f2f; color: white; }
        .alert-success { background: #e8f5e9; color: #2e7d32; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .badge-warning { background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .badge-safe { background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; }
        .btn-gray { background: #757575; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h2>Daftar Inventaris Pupuk</h2>

    {{-- Pesan Flash Sukses --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('pupuk.create') }}" class="btn btn-green">+ Tambah Pupuk</a>
    <a href="{{ route('filament.petani.pages.dashboard') }}" class="btn btn-gray">← Kembali ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pupuk</th>
                <th>Jenis</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th>Status Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Syarat ETS: @foreach dan @if --}}
            @forelse($pupuks as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->stok }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>
                        @if($item->stok <= 5)
                            <span class="badge-warning">Hampir Habis</span>
                        @else
                            <span class="badge-safe">Tersedia</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('pupuk.edit', $item->id) }}" class="btn btn-blue">Edit</a>
                        
                        <form action="{{ route('pupuk.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-red">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada data pupuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>