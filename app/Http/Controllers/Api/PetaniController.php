<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani; // Sesuaikan dengan nama Model proyek PjBL Anda
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    /**
     * Menampilkan seluruh data (Get All)
     */
    public function index()
    {
      $data = Petani::select('id_petani', 'nama')->get();

        return response()->json([
            'message' => 'Berhasil mengambil semua data',
            'data' => $data
        ], 200);
    }

    /**
     * Menampilkan data spesifik berdasarkan ID (Get By ID)
     */
    public function show($id)
    {
        $data = Petani::find($id);

        // Validasi jika data tidak ditemukan (Status 404)
        if (!$data) {
            return response()->json([
                'message' => 'Data tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Detail data ditemukan',
            'data' => $data
        ], 200);
    }

        private function tokenValid(Request $request): bool
    {
        return $request->bearerToken() === 'TOKEN-RAHASIA-123';
    }

    /**
     * Menyimpan data baru (POST)
     */
    public function store(Request $request)
    {
        if (!$this->tokenValid($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|max:100|unique:petani,email',
            'password' => 'required|string|min:8',
            'kontak'   => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        $petani = Petani::create($validated);

        return response()->json([
            'message' => 'Petani berhasil ditambahkan',
            'data'    => $petani
        ], 201);
    }

    /**
     * Mengubah data (PUT)
     */
    public function update(Request $request, $id)
    {
        if (!$this->tokenValid($request)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $petani = Petani::find($id);
        if (!$petani) {
            return response()->json(['message' => 'Data tidak ditemukan', 'data' => null], 404);
        }

        $validated = $request->validate([
            'nama'   => 'sometimes|required|string|max:100',
            'email'  => 'sometimes|required|email|max:100|unique:petani,email,' . $id . ',id_petani',
            'kontak' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $petani->update($validated);

        return response()->json([
            'message' => 'Petani berhasil diperbarui',
            'data'    => $petani
        ], 200);
    }
}
