@extends('layouts.app')

@section('title', 'Tambah Pupuk - Agritech')

@push('styles')
<style>
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 0.875rem; color: #374151; }
    input, select { width: 100%; padding: 8px 12px; box-sizing: border-box; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
    .error-msg { color: #d32f2f; font-size: 13px; margin-top: 4px; }
    .btn { padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 0.875rem; }
    .btn-green { background: #2e7d32; color: white; }
    .btn-gray { background: #6b7280; color: white; }
</style>
@endpush

@section('content')
<div class="card" style="max-width: 550px;">
    <h2 style="margin-bottom: 20px; font-size: 1.25rem;">Tambah Data Pupuk</h2>

    <form action="{{ route('pupuk.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Pupuk</label>
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="cth: Urea Nitro">
            @error('nama') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Jenis Pupuk</label>
            <select name="jenis">
                <option value="">-- Pilih Jenis --</option>
                <option value="Organik" {{ old('jenis') == 'Organik' ? 'selected' : '' }}>Organik</option>
                <option value="Kimia/Anorganik" {{ old('jenis') == 'Kimia/Anorganik' ? 'selected' : '' }}>Kimia/Anorganik</option>
                <option value="Hayati" {{ old('jenis') == 'Hayati' ? 'selected' : '' }}>Hayati</option>
            </select>
            @error('jenis') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0">
            @error('stok') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Satuan</label>
            <input type="text" name="satuan" value="{{ old('satuan') }}" placeholder="cth: kg / karung / liter">
            @error('satuan') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="display: flex; gap: 8px; margin-top: 20px;">
            <button type="submit" class="btn btn-green">Simpan</button>
            <a href="{{ route('pupuk.index') }}" class="btn btn-gray">Kembali</a>
        </div>
    </form>
</div>
@endsection