<?php

namespace App\Filament\Pages;

use App\Services\BmkgWeatherService;
use BackedEnum;
use Filament\Pages\Page;

class CuacaLahan extends Page
{
    protected string $view = 'filament.pages.cuaca-lahan';

    protected static ?string $title = 'Cuaca Lahan';
    protected static ?string $navigationLabel = 'Cuaca Lahan';
    protected static ?int $navigationSort = 3;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cloud';

    public string $kodeWilayah = '';
    public ?string $pesanError = null;

    public function mount(): void
    {
        // Ingat kode terakhir selama sesi login masih aktif.
        $this->kodeWilayah = (string) session('kode_wilayah', '');
    }

    public function cari(): void
    {
        $this->pesanError = null;
        $kode = trim($this->kodeWilayah);

        if (! BmkgWeatherService::formatValid($kode)) {
            $this->pesanError = 'Format kode wilayah tidak valid. Contoh: 35.77.01.1001';

            return;
        }

        session(['kode_wilayah' => $kode]);
        $this->kodeWilayah = $kode;
    }

    /** Dipanggil dari blade. Hasil di-cache 1 jam di dalam service. */
    public function dataCuaca(): ?array
    {
        return BmkgWeatherService::prakiraan(session('kode_wilayah'));
    }

    public function adaKode(): bool
    {
        return (string) session('kode_wilayah', '') !== '';
    }
}