<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KiraAIService
{
    public static function generateSolusi(string $keluhanPetani, string $lokasiLahan): string
    {
        $apiKey = env('CEREBRAS_API_KEY') ?? config('services.cerebras.key');

        if (empty($apiKey)) {
            throw new \Exception('ERROR: CEREBRAS_API_KEY belum terdeteksi!');
        }

        $prompt = "Kamu adalah asisten ahli pertanian 'Agritech Assistant'.\n".
            "Lokasi Lahan: {$lokasiLahan}\n".
            "Keluhan Tanaman: {$keluhanPetani}\n\n".
            "ATURAN FORMATTING:\n".
            "1. JANGAN gunakan simbol '•' inline dalam satu paragraf.\n".
            "2. WAJIB gunakan format Markdown list (gunakan tanda '-' atau angka di baris baru) untuk setiap poin agar mudah dibaca.\n".
            '3. Tulis jawaban secara ringkas, padat, dan langsung ke poin utama.';

        $models = [
            'openrouter/nvidia/nemotron-3-super-120b-a12b:free',
            'nvidia/nemotron-3-super-120b-a12b:free',
            'gpt-oss-120b',

        ];

        foreach ($models as $model) {
            try {
                $response = Http::timeout(35)
                    ->withHeaders([
                        'Authorization' => 'Bearer '.$apiKey,
                        'HTTP-Referer' => 'http://localhost',
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.cerebras.ai/v1/chat/completions', [
                        'model' => $model,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 1000,
                        'temperature' => 0.7,
                    ]);

                if ($response->successful()) {
                    $rawBody = $response->body();

                    // Buang 'data: [DONE]' dan spasi/enter liar di awal/akhir
                    $cleanBody = preg_replace('/data:\s*\[DONE\].*/s', '', $rawBody);
                    $cleanBody = trim($cleanBody);

                    $json = json_decode($cleanBody, true);
                    $content = $json['choices'][0]['message']['content'] ?? null;

                    if (! empty($content) && ! str_contains($content, 'antrean padat')) {
                        return $content;
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        throw new \Exception('Sistem AI sedang mengalami antrean padat di semua server. Silakan klik tombol analisis sekali lagi.');
    }
}
