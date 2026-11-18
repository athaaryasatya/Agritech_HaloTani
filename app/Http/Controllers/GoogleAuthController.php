<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $google = Socialite::driver('google')->user();}
             catch (\Throwable $e) {
    dd(get_class($e), $e->getMessage());
}
        // Cari berdasarkan google_id, lalu email; kalau belum ada, buat akun baru.
        $petani = Petani::where('google_id', $google->getId())
            ->orWhere('email', $google->getEmail())
            ->first();

        if (! $petani) {
            $petani = Petani::create([
                'nama'      => $google->getName(),
                'email'     => $google->getEmail(),
                'google_id' => $google->getId(),
                'password'  => Str::random(32), // kolom password wajib terisi; otomatis di-hash oleh cast
            ]);
        } elseif (! $petani->google_id) {
            $petani->update(['google_id' => $google->getId()]);
        }

        Auth::login($petani, remember: true);
        request()->session()->regenerate();

        return redirect()->intended('/petani');
    }
}