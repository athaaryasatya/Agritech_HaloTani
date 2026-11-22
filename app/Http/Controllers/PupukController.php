<?php

namespace App\Http\Controllers;

use App\Models\pupuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PupukController extends Controller
{
    public function index(Request $request)
    {
        $query = pupuk::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%'.$request->search.'%');
        }
        $pupuks = $query->latest()->get();

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

    /**
     * Export data inventaris pupuk ke dokumen PDF menggunakan Barryvdh\DomPDF
     */
    public function exportPdf(Request $request)
    {
        $pupuks = pupuk::all();
        $tanggal = now()->format('d-m-Y H:i:s');

        $pdf = Pdf::loadView('pupuk.pdf', compact('pupuks', 'tanggal'))
            ->setPaper('a4', 'portrait');

        if ($request->has('stream')) {
            return $pdf->stream('laporan-inventaris-pupuk.pdf');
        }

        return $pdf->download('laporan-inventaris-pupuk.pdf');
    }
}
