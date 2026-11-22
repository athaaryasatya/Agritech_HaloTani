@extends('layouts.app')

@section('title', 'Manajemen Pupuk - HaloTani')

@push('styles')
<style>
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #e5e7eb; padding: 10px; text-align: left; }
    th { background-color: #2e7d32; color: white; font-size: 0.875rem; }
    td { font-size: 0.875rem; }
    .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; display: inline-block; cursor: pointer; border: none; font-size: 0.875rem; }
    .btn-green { background: #2e7d32; color: white; }
    .btn-blue { background: #0288d1; color: white; }
    .btn-red { background: #d32f2f; color: white; }
    .btn-gray { background: #6b7280; color: white; }
    .alert-success { background: #e8f5e9; color: #2e7d32; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .badge-warning { background: #ffebee; color: #c62828; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    .badge-safe { background: #e8f5e9; color: #2e7d32; padding: 4px 8px; border-radius: 4px; }
</style>
@endpush

@section('content')
<div class="card">
    <h2 style="margin-bottom: 15px;">Daftar Inventaris Pupuk</h2>

    {{-- Pesan Sukses Flash --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div style="display: flex; gap: 8px; margin-bottom: 15px; flex-wrap: wrap;">
        <a href="{{ route('pupuk.create') }}" class="btn btn-green">+ Tambah Pupuk</a>
        <a href="{{ route('pupuk.export.pdf') }}" class="btn btn-red" target="_blank">📄 Unduh Laporan PDF</a>
        <a href="{{ route('pupuk.export.pdf', ['stream' => 1]) }}" class="btn btn-blue" target="_blank">👁️ Cetak / Preview PDF</a>
        <a href="{{ route('filament.petani.pages.dashboard') }}" class="btn btn-gray">← Kembali ke Dashboard</a>
    </div>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('pupuk.index') }}" style="margin-bottom: 15px; display: flex; gap: 6px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pupuk..." style="padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" class="btn btn-blue">Cari</button>
        <a href="{{ route('pupuk.index') }}" class="btn btn-gray">Reset</a>
    </form>

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
@endsection