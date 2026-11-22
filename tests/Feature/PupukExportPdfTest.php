<?php

namespace Tests\Feature;

use App\Models\pupuk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PupukExportPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_export_pupuk_data_to_pdf_download(): void
    {
        pupuk::create([
            'nama' => 'Urea Test',
            'jenis' => 'Kimia',
            'stok' => 20,
            'satuan' => 'kg',
        ]);

        $response = $this->get(route('pupuk.export.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('attachment; filename=laporan-inventaris-pupuk.pdf', $response->headers->get('content-disposition'));
    }

    public function test_can_stream_pupuk_pdf_in_browser(): void
    {
        pupuk::create([
            'nama' => 'NPK Test',
            'jenis' => 'Kimia',
            'stok' => 15,
            'satuan' => 'kg',
        ]);

        $response = $this->get(route('pupuk.export.pdf', ['stream' => 1]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('inline; filename=laporan-inventaris-pupuk.pdf', $response->headers->get('content-disposition'));
    }

    public function test_index_page_contains_export_pdf_button(): void
    {
        $response = $this->get(route('pupuk.index'));

        $response->assertStatus(200);
        $response->assertSee(route('pupuk.export.pdf'));
        $response->assertSee('Unduh Laporan PDF');
    }
}
