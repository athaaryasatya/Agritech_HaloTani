<?php

namespace App\Http\Controllers;

use App\Models\pupuk;
use Illuminate\Http\Request;

class PupukController extends Controller
{
    public function index()
    {
        $pupuks = pupuk::all();
        return view('pupuk.index', compact('pupuks'));
    }

    public function create()
    {
        return view('pupuk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        pupuk::create($request->all());

        return redirect()->route('pupuk.index')->with('success', 'Pupuk berhasil ditambahkan.');
    }

    public function edit(pupuk $pupuk)
    {
        return view('pupuk.edit', compact('pupuk'));
    }

    public function update(Request $request, pupuk $pupuk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        $pupuk->update($validated);

        return redirect()->route('pupuk.index')->with('success', 'Pupuk berhasil diperbarui.');
    }

    public function destroy(pupuk $pupuk)
    {
        $pupuk->delete();
        return redirect()->route('pupuk.index')->with('success', 'Pupuk berhasil dihapus.');
    }
}
