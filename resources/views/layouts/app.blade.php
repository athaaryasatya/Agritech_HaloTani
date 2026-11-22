<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manajemen Pupuk - Agritech')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        body { background-color: #f9fafb; color: #111827; }
        .wrapper { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 15rem; background: #ffffff; border-right: 1px solid #e5e7eb;
            display: flex; flex-direction: column; justify-content: space-between;
            padding: 1.25rem 0.75rem; flex-shrink: 0; position: sticky; top: 0; height: 100vh;
        }
        .brand { display: flex; align-items: center; gap: 0.75rem; padding: 0.25rem 0.5rem 1.25rem; }
        .brand-icon {
            width: 2.25rem; height: 2.25rem; border-radius: 0.6rem;
            background: #2E7D32; color: #fff; display: flex; align-items: center; justify-content: center;
        }
        .brand-text { font-size: 1.1rem; font-weight: 700; color: #111827; }

        .nav-list { list-style: none; display: flex; flex-direction: column; gap: 0.25rem; }
        .nav-link {
            display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 0.75rem;
            border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; font-weight: 500;
            color: #4b5563; transition: all 0.15s ease;
        }
        .nav-link:hover { background-color: #f3f4f6; color: #111827; }
        .nav-link svg { width: 1.25rem; height: 1.25rem; color: #6b7280; flex-shrink: 0; }
        
        /* Active Menu */
        .nav-link.active { background-color: #e8f5e9; color: #2E7D32; font-weight: 600; }
        .nav-link.active svg { color: #2E7D32; }

        /* Footer User */
        .sidebar-footer { display: flex; flex-direction: column; gap: 0.5rem; padding-top: 1rem; border-top: 1px solid #f3f4f6; }
        .user-card {
            display: flex; align-items: center; gap: 0.65rem; padding: 0.5rem 0.65rem;
            border-radius: 0.6rem; background: #f9fafb; border: 1px solid #f0f1f3; text-decoration: none;
        }
        .user-avatar {
            width: 2rem; height: 2rem; border-radius: 9999px; background: #e8f5e9;
            color: #2E7D32; display: flex; align-items: center; justify-content: center;
        }
        .user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .user-name { font-size: 0.8rem; font-weight: 600; color: #111827; }
        .user-role { font-size: 0.72rem; color: #6b7280; }
        .logout-btn {
            display: flex; align-items: center; gap: 0.65rem; width: 100%; padding: 0.5rem 0.65rem;
            background: none; border: none; font-size: 0.85rem; font-weight: 500; color: #ef4444; cursor: pointer; text-align: left; border-radius: 0.5rem;
        }
        .logout-btn:hover { background: #fef2f2; }

        /* ===== MAIN CONTENT ===== */
        .main-content { flex: 1; padding: 2rem; overflow-y: auto; }
        .card { background: #ffffff; padding: 1.5rem; border-radius: 0.6rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    </style>
    @stack('styles')
</head>
<body>
<div class="wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <!-- Brand -->
            <div class="brand">
                <span class="brand-icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </span>
                <span class="brand-text">Agritech</span>
            </div>

            <!-- Menu Navigasi -->
            <ul class="nav-list">
                <li>
                    <a href="{{ route('filament.petani.pages.dashboard') }}" class="nav-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span>Beranda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('filament.petani.pages.kirim-keluhan') }}" class="nav-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        <span>Kirim Keluhan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('filament.petani.pages.riwayat-analisis') }}" class="nav-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <span>Riwayat Analisis</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('filament.petani.auth.profile') }}" class="nav-link">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        <span>Profil Saya</span>
                    </a>
                </li>
                <li>
                    {{-- Menu aktif untuk fitur pupuk --}}
                    <a href="{{ route('pupuk.index') }}" class="nav-link active">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        <span>Manajemen Pupuk</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Footer User & Logout -->
        <div class="sidebar-footer">
            <a href="{{ route('filament.petani.auth.profile') }}" class="user-card">
                <span class="user-avatar">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                </span>
                <span class="user-info">
                    <span class="user-name">{{ auth()->user()->nama ?? 'Petani' }}</span>
                    <span class="user-role">Petani</span>
                </span>
            </a>

            <form method="POST" action="{{ route('filament.petani.auth.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="main-content">
        @yield('content')
    </main>
</div>
</body>
</html>