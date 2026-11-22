<?php

namespace Tests\Feature;

use App\Models\pupuk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PupukApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_pupuk_data(): void
    {
        pupuk::create([
            'nama' => 'Urea',
            'jenis' => 'Kimia',
            'stok' => 50,
            'satuan' => 'karung',
        ]);

        pupuk::create([
            'nama' => 'Kompos Organik',
            'jenis' => 'Organik',
            'stok' => 100,
            'satuan' => 'kg',
        ]);

        $response = $this->getJson('/api/pupuk');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => ['id', 'nama', 'jenis', 'stok', 'satuan', 'created_at', 'updated_at'],
                ],
            ])
            ->assertJson([
                'message' => 'Berhasil mengambil semua data',
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_get_detail_pupuk_by_id(): void
    {
        $pupuk = pupuk::create([
            'nama' => 'NPK Mutiara',
            'jenis' => 'Kimia',
            'stok' => 25,
            'satuan' => 'kg',
        ]);

        $response = $this->getJson('/api/pupuk/'.$pupuk->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Detail data ditemukan',
                'data' => [
                    'id' => $pupuk->id,
                    'nama' => 'NPK Mutiara',
                    'jenis' => 'Kimia',
                    'stok' => 25,
                    'satuan' => 'kg',
                ],
            ]);
    }

    public function test_returns_404_if_pupuk_not_found(): void
    {
        $response = $this->getJson('/api/pupuk/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ]);
    }

    public function test_can_create_pupuk(): void
    {
        $payload = [
            'nama' => 'Pupuk Organik Cair',
            'jenis' => 'Organik',
            'stok' => 40,
            'satuan' => 'liter',
        ];

        $response = $this->postJson('/api/pupuk', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Data pupuk berhasil ditambahkan',
                'data' => $payload,
            ]);

        $this->assertDatabaseHas('pupuks', $payload);
    }

    public function test_can_update_pupuk(): void
    {
        $pupuk = pupuk::create([
            'nama' => 'Urea Lama',
            'jenis' => 'Kimia',
            'stok' => 10,
            'satuan' => 'kg',
        ]);

        $response = $this->putJson('/api/pupuk/'.$pupuk->id, [
            'nama' => 'Urea Baru',
            'stok' => 30,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data pupuk berhasil diperbarui',
                'data' => [
                    'nama' => 'Urea Baru',
                    'stok' => 30,
                ],
            ]);

        $this->assertDatabaseHas('pupuks', [
            'id' => $pupuk->id,
            'nama' => 'Urea Baru',
            'stok' => 30,
        ]);
    }

    public function test_can_delete_pupuk(): void
    {
        $pupuk = pupuk::create([
            'nama' => 'Pupuk Dihapus',
            'jenis' => 'Kimia',
            'stok' => 5,
            'satuan' => 'kg',
        ]);

        $response = $this->deleteJson('/api/pupuk/'.$pupuk->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data pupuk berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('pupuks', [
            'id' => $pupuk->id,
        ]);
    }
}
