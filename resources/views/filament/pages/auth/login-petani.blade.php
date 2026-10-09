<div>
    <div class="fixed inset-0 z-50 flex min-h-screen w-full bg-gray-50 overflow-hidden" style="font-family: system-ui, -apple-system, sans-serif;">
        <!-- Left Section: Branding Green Split-Screen -->
        <div class="hidden lg:flex lg:w-7/12 bg-[#2E7D32] flex-col justify-center items-center p-12 text-white relative">
            <div class="max-w-md space-y-6 text-center">
                <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center shadow-xl mx-auto">
                    <svg class="w-12 h-12 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 20A7 7 0 019.8 6.1C15.5 5 17 4.4 19 2c1 2 2 4.12 2 9a7 7 0 01-10 9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 20v-5.5a2.5 2.5 0 015 0V20"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight">Agritech Assistant</h1>
                    <p class="text-green-100 text-lg mt-2">Solusi Cerdas & Digital untuk Pertanian Anda</p>
                </div>
                <div class="flex flex-wrap justify-center gap-2 pt-4">
                    <span class="bg-white/15 border border-white/20 text-white px-4 py-2 rounded-full text-xs font-semibold">✨ Analisis AI Tanaman</span>
                    <span class="bg-white/15 border border-white/20 text-white px-4 py-2 rounded-full text-xs font-semibold">📊 Riwayat & Rekomendasi</span>
                    <span class="bg-white/15 border border-white/20 text-white px-4 py-2 rounded-full text-xs font-semibold">🌾 Mudah Digunakan Petani</span>
                </div>
            </div>
        </div>

        <!-- Right Section: Login Form -->
        <div class="w-full lg:w-5/12 flex flex-col justify-center items-center p-8 bg-white overflow-y-auto min-h-screen">
            <div class="w-full max-w-sm space-y-6">
                <!-- Header Title -->
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Selamat Datang</h2>
                    <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Anda untuk melanjutkan</p>
                </div>

                <!-- Form Login Livewire -->
                <form wire:submit="authenticate" class="space-y-5">
                    {{ $this->form }}

                    <!-- Tombol Masuk Utama (Lebar / Full Width) -->
                    <div class="pt-2">
                        <button type="submit" 
                            style="background-color: #2E7D32; color: #ffffff; width: 100%; display: block; border-radius: 0.5rem; padding: 0.75rem 1rem; font-weight: 700; text-align: center; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                            Masuk ke Portal
                        </button>
                    </div>
                </form>

                <!-- Section Register -->
                <div class="pt-4 border-t border-gray-100 text-center space-y-2">
                    <p class="text-sm text-gray-600">Belum memiliki akun Petani?</p>
                    <a href="{{ url('/petani/register') }}" 
                        style="border: 2px solid #2E7D32; color: #2E7D32; width: 100%; display: block; border-radius: 0.5rem; padding: 0.625rem 1rem; font-weight: 700; text-align: center; text-decoration: none;">
                        Sign up for an account
                    </a>
                </div>

                <div class="text-center pt-2">
                    <p class="text-xs text-gray-400">© 2026 Agritech Assistant • Politeknik Negeri Madiun</p>
                </div>
            </div>
        </div>
    </div>
</div>