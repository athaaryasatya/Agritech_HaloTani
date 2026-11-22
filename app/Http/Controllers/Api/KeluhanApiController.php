<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use App\Models\Petani;
use App\Services\KiraAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KeluhanApiController extends Controller
{
    /**
     * Ambil semua data keluhan
     */
    public function index()
    {
        $data = Keluhan::with('petani')->latest('tanggal')->get();

        return response()->json([
            'message' => 'Berhasil mengambil daftar keluhan',
            'data' => $data,
        ], 200);
    }

    /**
     * Detail keluhan berdasarkan ID
     */
    public function show($id)
    {
        $keluhan = Keluhan::with('petani')->find($id);

        if (! $keluhan) {
            return response()->json([
                'message' => 'Data keluhan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Detail keluhan ditemukan',
            'data' => $keluhan,
        ], 200);
    }

    /**
     * Kirim keluhan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_petani' => 'nullable|uuid|exists:petani,id_petani',
            'jenis_tanaman' => 'required|string|max:100',
            'lokasi_lahan' => 'required|string|max:255',
            'tingkat_urgensi' => 'required|in:Rendah,Sedang,Tinggi',
            'deskripsi' => 'required|string',
        ]);

        // Fallback ID petani pertama jika tidak login / tidak dikirim
        $idPetani = $validated['id_petani'] ?? Petani::first()?->id_petani;

        $teksLengkap = "Jenis Tanaman: {$validated['jenis_tanaman']}\nLokasi: {$validated['lokasi_lahan']}\nUrgensi: {$validated['tingkat_urgensi']}\nKeluhan: {$validated['deskripsi']}";

        // Analisis AI (fallback jika API key belum ada/gagal)
        try {
            $hasilAi = KiraAIService::generateSolusi($validated['deskripsi'], $validated['lokasi_lahan']);
        } catch (\Throwable $e) {
            $hasilAi = 'Analisis otomatis: Periksa kelembaban tanah dan gunakan dosis pupuk berimbang.';
        }

        $keluhan = Keluhan::create([
            'id_keluhan' => (string) Str::uuid(),
            'id_petani' => $idPetani,
            'teks_keluhan' => $teksLengkap,
            'tingkat_urgensi' => $validated['tingkat_urgensi'],
            'hasil_analisis' => $hasilAi,
            'tanggal' => now(),
            'status' => 'selesai',
        ]);

        return response()->json([
            'message' => 'Keluhan berhasil dikirim dan dianalisis',
            'data' => $keluhan->load('petani'),
        ], 201);
    }

    /**
     * Update data keluhan
     */
    public function update(Request $request, $id)
    {
        $keluhan = Keluhan::find($id);

        if (! $keluhan) {
            return response()->json([
                'message' => 'Data keluhan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'sometimes|required|string|max:50',
            'tingkat_urgensi' => 'sometimes|required|in:Rendah,Sedang,Tinggi',
        ]);

        $keluhan->update($validated);

        return response()->json([
            'message' => 'Data keluhan berhasil diperbarui',
            'data' => $keluhan,
        ], 200);
    }

    /**
     * Hapus keluhan
     */
    public function destroy($id)
    {
        $keluhan = Keluhan::find($id);

        if (! $keluhan) {
            return response()->json([
                'message' => 'Data keluhan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $keluhan->delete();

        return response()->json([
            'message' => 'Data keluhan berhasil dihapus',
            'data' => null,
        ], 200);
    }
}
