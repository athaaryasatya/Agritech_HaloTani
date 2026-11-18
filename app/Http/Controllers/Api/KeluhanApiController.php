<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keluhan;
use Illuminate\Http\Request;

class KeluhanApiController extends Controller
{
    public function index()
    {
        return response()->json(Keluhan::all());
    }

    public function store(Request $request)
    {
        if ($request->bearerToken() !== 'TOKEN-RAHASIA-123') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'id_petani'   => 'required',  
            'teks_keluhan' => 'required|string',
            'tingkat_urgensi' => 'required|string|max:50',
            'tanggal' => 'nullable|date',
        ]);
        
        $data['tanggal'] = $data['tanggal'] ?? now();

        $keluhan = Keluhan::create($data);

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data'    => $keluhan,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->bearerToken() !== 'TOKEN-RAHASIA-123') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'teks_keluhan' => 'required|string',
            'tingkat_urgensi' => 'required|string|max:50',
        ]);

        $keluhan = Keluhan::findOrFail($id);
        $keluhan->update($data);

        return response()->json([
            'message' => 'Data berhasil diubah',
            'data'    => $keluhan,
        ], 200);
    }
}