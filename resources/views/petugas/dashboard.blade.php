<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="bg-[#fcfdfe] antialiased">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="hidden lg:flex flex-col w-72 h-screen sticky top-0 bg-white border-r border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-10 px-2">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-white" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-slate-800 tracking-tight">PinjamAlat</span>
                </div>

                @php
                    $sidebarItem = function($href, $active, $icon, $label) {
                        $classes = $active
                                    ? 'flex items-center gap-3 px-4 py-3 bg-indigo-50 text-indigo-700 rounded-2xl transition-all duration-200 shadow-sm shadow-indigo-100 ring-1 ring-indigo-100'
                                    : 'flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 hover:text-indigo-600 rounded-2xl transition-all duration-200';
                        
                        $icons = [
                            'home' => 'm3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z',
                            'users' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2',
                            'tool' => 'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.77 3.77z',
                            'tag' => 'M12 2H2v10l9.29 9.29c.94.94 2.48.94 3.42 0l6.58-6.58c.94-.94.94-2.48 0-3.42L12 2Z',
                            'clipboard' => 'M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2',
                            'activity' => 'M22 12h-4l-3 9L9 3l-3 9H2',
                            'search' => 'm21 21-4.35-4.35M19 11a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z',
                            'clock' => 'M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',
                        ];
                        $iconPath = $icons[$icon] ?? $icons['home'];
                        
                        echo '<a href="'.$href.'" class="'.$classes.'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="'.$iconPath.'"></path>';
                                if($icon == 'users') echo '<circle cx="9" cy="7" r="4"></circle>';
                                if($icon == 'clipboard') echo '<rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>';
                        echo '    </svg>
                            <span class="font-semibold text-sm">'.$label.'</span>
                        </a>';
                    };
                @endphp

                <nav class="flex-1 space-y-2">
                    {!! $sidebarItem(route('dashboard'), request()->routeIs('dashboard'), 'home', 'Dashboard') !!}

                    @if(Auth::user()->role === 'admin')
                        <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Master Data</div>
                        {!! $sidebarItem(route('admin.users.index'), request()->routeIs('admin.users.*'), 'users', 'User') !!}
                        {!! $sidebarItem(route('admin.kategoris.index'), request()->routeIs('admin.kategoris.*'), 'tag', 'Kategori') !!}
                        {!! $sidebarItem(route('admin.alats.index'), request()->routeIs('admin.alats.*'), 'tool', 'Alat') !!}
                    @endif

                    @if(in_array(Auth::user()->role, ['admin', 'petugas']))
                        <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Operasional</div>
                        {!! $sidebarItem(route('loans.manage'), request()->routeIs('loans.manage'), 'clipboard', 'Peminjaman') !!}
                    @endif

                    @if(Auth::user()->role === 'peminjam')
                        {!! $sidebarItem(route('alats.index'), request()->routeIs('alats.*'), 'search', 'Cari Alat') !!}
                        {!! $sidebarItem(route('loans.index'), request()->routeIs('loans.index'), 'clock', 'Pinjaman Saya') !!}
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Sistem</div>
                        {!! $sidebarItem(route('admin.logs.index'), request()->routeIs('admin.logs.*'), 'activity', 'Log Aktivitas') !!}
                    @endif
                </nav>

                <div class="pt-6 border-t border-slate-50 space-y-4">
                    <div class="glass-card p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-700">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="truncate">
                            <div class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">{{ Auth::user()->role }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-50 rounded-2xl transition-all duration-200 group">
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="font-bold text-sm">Keluar</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none p-4 md:p-8">
                    @php
                        $totalStock = \App\Models\Alat::sum('stok');
                        $borrowedCount = \App\Models\Peminjaman::where('status', 'borrowed')->count();
                        $recentPending = \App\Models\Peminjaman::with(['user', 'alat'])->where('status', 'pending')->latest()->limit(5)->get();
                    @endphp

                    <div class="mb-10">
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Panel Petugas 👋</h2>
                        <p class="text-slate-400 text-sm mt-1 font-medium">Manajemen peminjaman dan inventaris.</p>
                    </div>

                    {{-- STATS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                        <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-indigo-500 transition-all">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">Total Stok</span>
                            </div>
                            <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $totalStock }} <span class="text-sm font-bold text-slate-400 uppercase">Unit</span></h3>
                        </div>

                        <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-emerald-500 transition-all">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shadow-sm"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" /></svg></div>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">Dipinjam</span>
                            </div>
                            <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $borrowedCount }} <span class="text-sm font-bold text-slate-400 uppercase">Unit</span></h3>
                        </div>
                    </div>

                    {{-- PENDING TABLE --}}
                    <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-indigo-500 transition-all">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Peminjaman Perlu Verifikasi</h4>
                            <a href="{{ route('loans.manage') }}" class="text-xs font-bold text-indigo-600 hover:underline decoration-2 underline-offset-4">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($recentPending as $loan)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-5 flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 text-xs">{{ substr($loan->user->name ?? 'U', 0, 1) }}</div>
                                                <div><p class="text-sm font-bold text-slate-800">{{ $loan->user->name }}</p><p class="text-[10px] text-slate-400 uppercase font-black">{{ $loan->user->username }}</p></div>
                                            </td>
                                            <td class="py-5">
                                                <p class="text-sm font-bold text-slate-700">{{ $loan->alat->nama_alat }}</p>
                                                <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">{{ $loan->jumlah }} Unit</p>
                                            </td>
                                            <td class="py-5 text-right"><a href="{{ route('loans.manage') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase hover:bg-slate-800 transition-all shadow-md">Review</a></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="py-20 text-center text-slate-300 italic font-medium">Bagus! Tidak ada antrian pengajuan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>