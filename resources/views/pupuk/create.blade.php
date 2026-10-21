<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pupuk</title>
    <style>
        body { font-family: sans-serif; margin: 30px; background-color: #f9f9f9; }
        .container { max-width: 500px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .error-msg { color: #d32f2f; font-size: 13px; margin-top: 4px; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .btn-green { background: #2e7d32; color: white; }
        .btn-gray { background: #757575; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Data Pupuk</h2>

    <form action="{{ route('pupuk.store') }}" method="POST">
        {{-- Syarat ETS: Form Protection @csrf --}}
        @csrf

        <div class="form-group">
            <label>Nama Pupuk</label>
            {{-- Syarat ETS: Retensi masukan old() --}}
            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="cth: Urea Nitro">
            {{-- Syarat ETS: Penanganan error $errors --}}
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

        <button type="submit" class="btn btn-green">Simpan</button>
        <a href="{{ route('pupuk.index') }}" class="btn btn-gray">Kembali</a>
    </form>
</div>
</body>
</html>