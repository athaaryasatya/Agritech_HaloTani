<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\pupuk;
use Illuminate\Http\Request;

class PupukApiController extends Controller
{
    /**
     * Menampilkan seluruh data (Get All)
     */
    public function index()
    {
        $data = pupuk::all();

        return response()->json([
            'message' => 'Berhasil mengambil semua data',
            'data' => $data,
        ], 200);
    }

    /**
     * Menampilkan data spesifik berdasarkan ID (Get By ID)
     */
    public function show($id)
    {
        $data = pupuk::find($id);

        // Validasi jika data tidak ditemukan (Status 404)
        if (! $data) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Detail data ditemukan',
            'data' => $data,
        ], 200);
    }

    /**
     * Tambah data pupuk baru (POST)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        $pupuk = pupuk::create($validated);

        return response()->json([
            'message' => 'Data pupuk berhasil ditambahkan',
            'data' => $pupuk,
        ], 201);
    }

    /**
     * Ubah data pupuk (PUT)
     */
    public function update(Request $request, $id)
    {
        $pupuk = pupuk::find($id);

        if (! $pupuk) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'jenis' => 'sometimes|required|string|max:100',
            'stok' => 'sometimes|required|integer|min:0',
            'satuan' => 'sometimes|required|string|max:50',
        ]);

        $pupuk->update($validated);

        return response()->json([
            'message' => 'Data pupuk berhasil diperbarui',
            'data' => $pupuk,
        ], 200);
    }

    /**
     * Hapus data pupuk (DELETE)
     */
    public function destroy($id)
    {
        $pupuk = pupuk::find($id);

        if (! $pupuk) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $pupuk->delete();

        return response()->json([
            'message' => 'Data pupuk berhasil dihapus',
            'data' => null,
        ], 200);
    }
}
