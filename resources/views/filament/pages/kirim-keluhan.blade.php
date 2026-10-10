<x-filament-panels::page>
    <p style="color: #6b7280; font-size: 0.875rem; margin-top: -1.25rem;">
        Isi rincian kendala tanaman Anda untuk dianalisis oleh sistem AI
    </p>

    <form wire:submit="kirim">
        <x-filament::section>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem;">

                <div style="display: flex; flex-direction: column; gap: 1.25rem;">

                    <div>
                        <label style="font-weight: 600; color: #111827;">Jenis Tanaman</label>
                        <x-filament::input.wrapper :valid="! $errors->has('jenisTanaman')">
                            <x-filament::input type="text" wire:model="jenisTanaman"  />
                        </x-filament::input.wrapper>
                        @error('jenisTanaman') <small style="color: #dc2626;">{{ $message }}</small> @enderror
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #111827;">Lokasi Lahan</label>
                        <x-filament::input.wrapper :valid="! $errors->has('lokasiLahan')">
                            <x-filament::input type="text" wire:model="lokasiLahan" />
                        </x-filament::input.wrapper>
                        @error('lokasiLahan') <small style="color: #dc2626;">{{ $message }}</small> @enderror
                    </div>

                    <div>
                        <label style="font-weight: 600; color: #111827;">Tingkat Urgensi</label>
                        <div style="display: flex; gap: 0.5rem; margin-top: 0.25rem;">

                            @foreach (['Rendah', 'Sedang', 'Tinggi'] as $item)
                                <x-filament::button 
                                    type="button" 
                                    wire:click="$set('urgensi', '{{ $item }}')" 
                                    :color="$urgensi === $item ? 'success' : 'gray'">
                                    {{ $item }}
                                </x-filament::button>
                            @endforeach

                        </div>
                        @error('urgensi') <small style="color: #dc2626;">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div>
                    <label style="font-weight: 600; color: #111827;">Deskripsi Masalah / Gejala</label>
                    <x-filament::input.wrapper :valid="! $errors->has('deskripsi')">
                        <textarea wire:model="deskripsi" rows="8" placeholder="Jelaskan kondisi tanaman secara detail..."
                            style="width: 100%; padding: 0.75rem; border: 0; background: transparent; outline: none; resize: none;"></textarea>
                    </x-filament::input.wrapper>
                    @error('deskripsi') <small style="color: #dc2626;">{{ $message }}</small> @enderror
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <x-filament::button type="button" color="gray" wire:click="batal">Batal</x-filament::button>
                <x-filament::button type="submit" style="background-color: green; color: white;">Kirim &amp; Analisis via AI</x-filament::button>
            </div>
        </x-filament::section>
    </form>
</x-filament-panels::page>