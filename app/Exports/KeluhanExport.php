<?php

namespace App\Exports;

use App\Models\Keluhan;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class KeluhanExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private int $no = 0;

    public function __construct(
        private string $idPetani,
        private string $status = 'semua',
    ) {}

    public function query(): Builder
    {
        return Keluhan::query()
            ->where('id_petani', $this->idPetani)
            ->when($this->status !== 'semua', fn ($q) => $q->where('status', $this->status))
            ->orderBy('tanggal', 'desc');
    }

    public function headings(): array
    {
        return ['No', 'Tanggal', 'Tingkat Urgensi', 'Status', 'Keluhan', 'Hasil Analisis AI'];
    }

    public function map($keluhan): array
    {
        return [
            ++$this->no,
            $keluhan->tanggal?->format('d/m/Y H:i'),
            $keluhan->tingkat_urgensi ?? '-',
            ucfirst($keluhan->status),
            $keluhan->teks_keluhan,
            Str::of($keluhan->hasil_analisis ?? 'Belum ada hasil analisis.')
                ->replace(['**', '##', '#'], '')
                ->toString(),
        ];
    }

    public function columnWidths(): array
    {
        return ['A' => 6, 'B' => 18, 'C' => 16, 'D' => 12, 'E' => 50, 'F' => 70];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('E:F')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:F')->getAlignment()->setVertical('top');

        return [1 => ['font' => ['bold' => true]]];
    }
}