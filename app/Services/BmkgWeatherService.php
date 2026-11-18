<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Mengambil prakiraan cuaca terbuka BMKG berdasarkan kode wilayah ADM4
 * (tingkat kelurahan/desa) dan menyusunnya untuk 24 jam ke depan.
 *
 * Syarat dari BMKG: sumber data WAJIB dicantumkan di aplikasi (lihat SUMBER).
 */
class BmkgWeatherService
{
    public const SUMBER = 'Sumber data cuaca: BMKG (Badan Meteorologi, Klimatologi, dan Geofisika)';

    /** Zona waktu untuk membaca local_datetime (Madiun = WIB). */
    private const TIMEZONE = 'Asia/Jakarta';

    /** Format kode ADM4 BMKG, contoh: 35.77.01.1001 */
    public static function formatValid(string $kode): bool
    {
        return (bool) preg_match('/^\d{2}\.\d{2}\.\d{2}\.\d{4}$/', trim($kode));
    }

    /**
     * Prakiraan 24 jam ke depan, atau null bila kode tidak valid / BMKG tidak terjangkau.
     *
     * @return array{lokasi:string, ringkasan:array, entri:array<int,array>}|null
     */
    public static function prakiraan(?string $kodeAdm4): ?array
    {
        $kode = trim((string) $kodeAdm4);

        if (! self::formatValid($kode)) {
            return null;
        }

        // Cache 1 jam: prakiraan BMKG per 3 jam, tidak perlu panggil API tiap buka halaman.
        $json = Cache::remember("bmkg:adm4:{$kode}", now()->addHour(), fn () => self::ambil($kode));

        return is_array($json) ? self::susun($json) : null;
    }

    private static function ambil(string $kode): ?array
    {
        try {
            $response = Http::timeout(8)
                ->retry(2, 300)
                ->acceptJson()
                ->get(config('services.bmkg.url'), ['adm4' => $kode]);

            return $response->json();
        } catch (\Throwable $e) {
            Log::warning('BMKG gagal diambil', ['adm4' => $kode, 'error' => $e->getMessage()]);

            return null;
        }
    }

    private static function susun(array $json): ?array
    {
        $lokasi = $json['data'][0]['lokasi'] ?? $json['lokasi'] ?? [];

        // 'cuaca' = daftar hari, tiap hari = daftar prakiraan per 3 jam.
        $semua = collect($json['data'][0]['cuaca'] ?? [])->collapse()->values();

        $batas = Carbon::now(self::TIMEZONE)->subHours(3);

        $entri = $semua
            ->filter(fn ($e) => ! empty($e['local_datetime'])
                && Carbon::parse($e['local_datetime'], self::TIMEZONE)->gte($batas))
            ->take(8) // 8 x 3 jam = 24 jam
            ->map(fn ($e) => [
                'waktu' => Carbon::parse($e['local_datetime'], self::TIMEZONE),
                'suhu' => (float) ($e['t'] ?? 0),
                'lembap' => (float) ($e['hu'] ?? 0),
                'hujan' => (float) ($e['tp'] ?? 0),
                'angin' => (float) ($e['ws'] ?? 0),
                'kondisi' => $e['weather_desc'] ?? '-',
                'ikon' => $e['image'] ?? null,
            ])
            ->values();

        if ($entri->isEmpty()) {
            return null;
        }

        $kondisiDominan = $entri->pluck('kondisi')->countBy()->sortDesc()->keys()->first();

        $nama = collect([
            $lokasi['desa'] ?? null,
            $lokasi['kecamatan'] ?? null,
            $lokasi['kotkab'] ?? null,
        ])->filter()->implode(', ');

        return [
            'lokasi' => $nama ?: 'Lokasi lahan',
            'ringkasan' => [
                'suhu_min' => round($entri->min('suhu')),
                'suhu_maks' => round($entri->max('suhu')),
                'lembap_rata' => round($entri->avg('lembap')),
                'hujan_total' => round($entri->sum('hujan'), 1),
                'kondisi' => $kondisiDominan,
            ],
            'entri' => $entri->all(),
        ];
    }
}