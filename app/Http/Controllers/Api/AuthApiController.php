<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthApiController extends Controller
{
    /**
     * Registrasi akun petani baru.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:petani,email'],
            'password' => ['required', 'string', 'min:8'],
            'kontak' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        // Password otomatis di-hash oleh cast 'password' => 'hashed' di model Petani
        $petani = Petani::create($validated);
        $petani->refresh();

        $token = $petani->createToken('postman-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => [
                'petani' => $petani,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Login petani dan terbitkan token Sanctum.
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $petani = Petani::where('email', $validated['email'])->first();

        if (! $petani || ! Hash::check($validated['password'], $petani->password)) {
            return response()->json([
                'message' => 'Email atau password salah',
                'data' => null,
            ], 401);
        }

        $tokenName = $validated['device_name'] ?? 'postman-token';
        $token = $petani->createToken($tokenName)->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => [
                'petani' => $petani,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * Ambil profil petani yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Profil petani ditemukan',
            'data' => $request->user(),
        ], 200);
    }

    /**
     * Logout dan hapus token aktif saat ini.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();

        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logout berhasil',
            'data' => null,
        ], 200);
    }
}