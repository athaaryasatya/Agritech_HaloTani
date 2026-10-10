<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class KirimKeluhan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-plus';
    protected static ?string $navigationLabel = 'Kirim Keluhan';
    protected static ?string $title = 'Kirim Keluhan Tanaman';

    protected string $view = 'filament.pages.kirim-keluhan';

    public string $jenisTanaman = '';
    public string $lokasiLahan = '';
    public string $urgensi = '';
    public string $deskripsi = '';

    public function kirim(): void
    {
        $this->validate([
            'jenisTanaman' => 'required|max:100',
            'lokasiLahan'  => 'required|max:150',
            'urgensi'      => 'required|in:Rendah,Sedang,Tinggi',
            'deskripsi'    => 'required|min:10',
        ]);

        Notification::make()
            ->title('Keluhan berhasil dikirim')
            ->success()
            ->send();

        $this->reset(['jenisTanaman', 'lokasiLahan', 'urgensi', 'deskripsi']);    
    }

    public function batal(): void
    {
        $this->reset(['jenisTanaman', 'lokasiLahan', 'urgensi', 'deskripsi']);
    }
}