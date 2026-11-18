<x-filament-panels::page>
    <p style="color: #6b7280; font-size: 0.875rem; margin-top: -1.25rem; margin-bottom: 1rem;">
        Prakiraan cuaca 24 jam ke depan untuk membantu jadwal tanam, penyemprotan, dan panen
    </p>

    <!-- Form kode wilayah -->
    <x-filament::section>
        <form wire:submit="cari">
            <label style="font-size: 0.875rem; font-weight: 600; color: #374151; display: block; margin-bottom: 0.375rem;">
                Kode Wilayah (ADM4 desa/kelurahan)
            </label>
            <div style="display: flex; gap: 0.75rem; align-items: flex-start; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 240px;">
                    <x-filament::input.wrapper>
                        <x-filament::input type="text" wire:model="kodeWilayah" placeholder="Contoh: 35.77.01.1001" required />
                    </x-filament::input.wrapper>
                </div>
                <x-filament::button type="submit" color="success" wire:loading.attr="disabled" wire:target="cari">
                    <span wire:loading.remove wire:target="cari">Lihat Cuaca</span>
                    <span wire:loading wire:target="cari">Memuat...</span>
                </x-filament::button>
            </div>
            @if ($pesanError)
                <p style="color: #b91c1c; font-size: 0.8125rem; margin-top: 0.5rem;">{{ $pesanError }}</p>
            @endif
        </form>
    </x-filament::section>

    @php $cuaca = $this->adaKode() ? $this->dataCuaca() : null; @endphp

    @if ($cuaca)
        @php $r = $cuaca['ringkasan']; @endphp

        <x-filament::section>
            <x-slot name="heading">{{ $cuaca['lokasi'] }}</x-slot>

            <!-- Ringkasan -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem;">
                @foreach ([
                    ['Suhu', $r['suhu_min'].' - '.$r['suhu_maks'].' °C'],
                    ['Kelembapan rata-rata', $r['lembap_rata'].' %'],
                    ['Total curah hujan', $r['hujan_total'].' mm'],
                    ['Kondisi dominan', $r['kondisi']],
                ] as [$label, $nilai])
                    <div style="padding: 0.875rem; border: 1px solid #bbf7d0; border-radius: 0.5rem; background-color: #f0fdf4;">
                        <p style="font-size: 0.75rem; color: #15803d; margin-bottom: 0.25rem;">{{ $label }}</p>
                        <p style="font-size: 1rem; font-weight: 600; color: #111827;">{{ $nilai }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Rincian per 3 jam -->
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr style="text-align: left; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                            <th style="padding: 0.5rem;">Waktu</th>
                            <th style="padding: 0.5rem;">Kondisi</th>
                            <th style="padding: 0.5rem;">Suhu</th>
                            <th style="padding: 0.5rem;">Kelembapan</th>
                            <th style="padding: 0.5rem;">Hujan</th>
                            <th style="padding: 0.5rem;">Angin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cuaca['entri'] as $e)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.5rem; white-space: nowrap;">{{ $e['waktu']->format('d M, H:i') }}</td>
                                <td style="padding: 0.5rem;">{{ $e['kondisi'] }}</td>
                                <td style="padding: 0.5rem;">{{ round($e['suhu']) }} °C</td>
                                <td style="padding: 0.5rem;">{{ round($e['lembap']) }} %</td>
                                <td style="padding: 0.5rem;">{{ $e['hujan'] }} mm</td>
                                <td style="padding: 0.5rem;">{{ round($e['angin']) }} km/jam</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Atribusi wajib BMKG -->
            <p style="font-size: 0.75rem; color: #6b7280; margin-top: 1rem;">
                {{ \App\Services\BmkgWeatherService::SUMBER }}
            </p>
        </x-filament::section>
    @elseif ($this->adaKode())
        <x-filament::section>
            <p style="color: #b45309; font-size: 0.875rem;">
                Data cuaca belum bisa ditampilkan. Periksa kembali kode wilayah, atau coba lagi beberapa saat
                (layanan BMKG mungkin sedang sibuk).
            </p>
        </x-filament::section>
    @endif
</x-filament-panels::page>