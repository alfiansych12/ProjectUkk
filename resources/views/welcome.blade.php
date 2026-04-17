<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Peminjaman Alat - Solusi Cepat & Terpercaya</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Outfit', sans-serif; }
            .hero-gradient {
                background: radial-gradient(circle at top right, #e0f2fe 0%, #f0f9ff 40%, #ffffff 100%);
            }
        </style>
    </head>
    <body class="antialiased hero-gradient min-h-screen">
        <nav class="p-6 max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z"/></svg>
                </div>
                <span class="text-xl font-extrabold text-slate-800 tracking-tight">PinjamAlat</span>
            </div>
            
            <div class="flex space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 font-bold hover:text-primary-600 transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primary">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-20 pb-12 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-12 md:mb-0">
                <h1 class="text-6xl md:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-6">
                    Solusi <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">Peminjaman Alat</span> Modern
                </h1>
                <p class="text-xl text-slate-600 mb-8 max-w-lg leading-relaxed">
                    Kelola, pinjam, dan pantau penggunaan alat workshop atau multimedia Anda dengan sistem yang terintegrasi dan transparan.
                </p>
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="btn-primary px-8 py-4 text-lg">Mulai Sekarang</a>
                    <a href="#features" class="px-8 py-4 text-lg font-bold text-slate-700 bg-white shadow-sm border border-slate-100 rounded-xl hover:shadow-md transition-all">Pelajari Fitur</a>
                </div>
            </div>
            
            <div class="md:w-1/2 relative">
                <!-- Decorative element -->
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-200/50 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-emerald-200/50 rounded-full blur-3xl -z-10"></div>
                
                <div class="glass-card p-1">
                    <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?q=80&w=1000" alt="Tool Workshop" class="rounded-xl shadow-2xl">
                </div>
            </div>
        </main>

        <section id="features" class="max-w-7xl mx-auto px-6 py-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="glass-card p-8 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Monitoring Real-time</h3>
                    <p class="text-slate-500">Pantau status alat apakah tersedia, sedang dipinjam, atau terlambat secara praktis.</p>
                </div>
                <div class="glass-card p-8 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Sistem Terintegrasi</h3>
                    <p class="text-slate-500">Logika bisnis terjamin dengan Stored Procedures dan Function di database untuk denda otomatis.</p>
                </div>
                <div class="glass-card p-8 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Multi-Level User</h3>
                    <p class="text-slate-500">Akses terkontrol untuk Admin, Petugas, dan Peminjam dengan antarmuka yang dipersonalisasi.</p>
                </div>
            </div>
        </section>

        <footer class="text-center py-12 border-t border-slate-100">
            <p class="text-slate-400">&copy; {{ date('Y') }} PinjamAlat. Built with Professionalism & Precision.</p>
        </footer>
    </body>
</html>
