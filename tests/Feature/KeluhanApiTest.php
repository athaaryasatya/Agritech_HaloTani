<?php

namespace Tests\Feature;

use App\Models\Keluhan;
use App\Models\Petani;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class KeluhanApiTest extends TestCase
{
    use RefreshDatabase;

    private function createPetani(): Petani
    {
        return Petani::create([
            'id_petani' => (string) Str::uuid(),
            'nama' => 'Pak Tani',
            'email' => 'paktani@test.com',
            'password' => 'password',
            'kontak' => '08123456789',
            'alamat' => 'Desa Sukamaju',
        ]);
    }

    public function test_can_get_all_keluhan(): void
    {
        $petani = $this->createPetani();

        Keluhan::create([
            'id_keluhan' => (string) Str::uuid(),
            'id_petani' => $petani->id_petani,
            'teks_keluhan' => 'Daun padi menguning',
            'tingkat_urgensi' => 'Sedang',
            'hasil_analisis' => 'Kurang nitrogen',
            'tanggal' => now(),
            'status' => 'selesai',
        ]);

        $response = $this->getJson('/api/keluhan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id_keluhan', 'id_petani', 'teks_keluhan', 'tingkat_urgensi', 'hasil_analisis', 'tanggal', 'status'],
                ],
            ])
            ->assertJsonCount(1, 'data');
    }

    public function test_can_create_keluhan(): void
    {
        $petani = $this->createPetani();

        $payload = [
            'id_petani' => $petani->id_petani,
            'jenis_tanaman' => 'Padi',
            'lokasi_lahan' => 'Petak 3 Sawah Barat',
            'tingkat_urgensi' => 'Sedang',
            'deskripsi' => 'Daun padi berbercak coklat kemerahan dan layu',
        ];

        $response = $this->postJson('/api/keluhan', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Keluhan berhasil dikirim dan dianalisis',
            ])
            ->assertJsonPath('data.tingkat_urgensi', 'Sedang');

        $this->assertDatabaseHas('keluhan', [
            'id_petani' => $petani->id_petani,
            'tingkat_urgensi' => 'Sedang',
        ]);
    }

    public function test_can_get_detail_keluhan(): void
    {
        $petani = $this->createPetani();

        $keluhan = Keluhan::create([
            'id_keluhan' => (string) Str::uuid(),
            'id_petani' => $petani->id_petani,
            'teks_keluhan' => 'Hama wereng',
            'tingkat_urgensi' => 'Tinggi',
            'hasil_analisis' => 'Semprot pestisida nabati',
            'tanggal' => now(),
            'status' => 'selesai',
        ]);

        $response = $this->getJson('/api/keluhan/'.$keluhan->id_keluhan);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Detail keluhan ditemukan',
                'data' => [
                    'id_keluhan' => $keluhan->id_keluhan,
                    'tingkat_urgensi' => 'Tinggi',
                ],
            ]);
    }

    public function test_can_delete_keluhan(): void
    {
        $petani = $this->createPetani();

        $keluhan = Keluhan::create([
            'id_keluhan' => (string) Str::uuid(),
            'id_petani' => $petani->id_petani,
            'teks_keluhan' => 'Keluhan sementara',
            'tingkat_urgensi' => 'Rendah',
            'hasil_analisis' => 'Aman',
            'tanggal' => now(),
            'status' => 'selesai',
        ]);

        $response = $this->deleteJson('/api/keluhan/'.$keluhan->id_keluhan);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data keluhan berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('keluhan', [
            'id_keluhan' => $keluhan->id_keluhan,
        ]);
    }
}
