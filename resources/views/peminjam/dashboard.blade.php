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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#fcfdfe] antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="hidden lg:flex flex-col w-72 h-screen sticky top-0 bg-white border-r border-slate-100 p-6">
            <div class="flex items-center gap-3 mb-10 px-2">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                    <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6 text-white" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                    </svg>
                </div>
                <span class="text-xl font-extrabold text-slate-800 tracking-tight">PinjamAlat</span>
            </div>

            @php
                $sidebarItem = function ($href, $active, $icon, $label) {
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

                    echo '<a href="' . $href . '" class="' . $classes . '">
                                                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                                                <path d="' . $iconPath . '"></path>';
                    if ($icon == 'users')
                        echo '<circle cx="9" cy="7" r="4"></circle>';
                    if ($icon == 'clipboard')
                        echo '<rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>';
                    echo '    </svg>
                                                                                            <span class="font-semibold text-sm">' . $label . '</span>
                                                                                        </a>';
                };
            @endphp

            <nav class="flex-1 space-y-2">
                {!! $sidebarItem(route('dashboard'), request()->routeIs('dashboard'), 'home', 'Dashboard') !!}

                @if(Auth::user()->role === 'admin')
                    <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Master Data
                    </div>
                    {!! $sidebarItem(route('admin.users.index'), request()->routeIs('admin.users.*'), 'users', 'User') !!}
                    {!! $sidebarItem(route('admin.kategoris.index'), request()->routeIs('admin.kategoris.*'), 'tag', 'Kategori') !!}
                    {!! $sidebarItem(route('admin.alats.index'), request()->routeIs('admin.alats.*'), 'tool', 'Alat') !!}
                @endif

                @if(in_array(Auth::user()->role, ['admin', 'petugas']))
                    <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Operasional
                    </div>
                    {!! $sidebarItem(route('loans.manage'), request()->routeIs('loans.manage'), 'clipboard', 'Peminjaman') !!}
                @endif

                @if(Auth::user()->role === 'peminjam')
                    {!! $sidebarItem(route('alats.index'), request()->routeIs('alats.*'), 'search', 'Cari Alat') !!}
                    {!! $sidebarItem(route('loans.index'), request()->routeIs('loans.index'), 'clock', 'Pinjaman Saya') !!}
                @endif

                @if(Auth::user()->role === 'admin')
                    <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Sistem
                    </div>
                    {!! $sidebarItem(route('admin.logs.index'), request()->routeIs('admin.logs.*'), 'activity', 'Log Aktivitas') !!}
                @endif
            </nav>

            <div class="pt-6 border-t border-slate-50 space-y-4">
                <div class="glass-card p-4 flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-700">
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
                    $borrowedCount = \App\Models\Peminjaman::where('user_id', Auth::id())->where('status', 'borrowed')->count();
                    $pendingCount = \App\Models\Peminjaman::where('user_id', Auth::id())->where('status', 'pending')->count();
                    $finishedCount = \App\Models\Peminjaman::where('user_id', Auth::id())->where('status', 'returned')->count();
                    $recentLoans = \App\Models\Peminjaman::with('alat')->where('user_id', Auth::id())->latest()->limit(5)->get();
                    
                    // Pinjaman yang sedang dibawa
                    $activeLoans = \App\Models\Peminjaman::with('alat')
                        ->where('user_id', Auth::id())
                        ->where('status', 'borrowed')
                        ->get();
                @endphp

                <div class="mb-10">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Halo,
                        {{ explode(' ', Auth::user()->name)[0] }}! 👋
                    </h2>
                    <p class="text-slate-400 text-sm mt-1 font-medium">Ringkasan aktivitas peminjaman hari ini.</p>
                </div>

                {{-- CARDS STATS --}}
                {{-- CARDS STATS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-indigo-500 transition-all">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">Aktif</span>
                        </div>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $borrowedCount }} <span
                                class="text-sm font-bold text-slate-400 uppercase">Item</span></h3>
                    </div>

                    <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-amber-500 transition-all">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">Status</span>
                        </div>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $pendingCount }} <span
                                class="text-sm font-bold text-slate-400 uppercase">Antrian</span></h3>
                    </div>

                    <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-emerald-500 transition-all">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                </svg>
                            </div>
                            <span
                                class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">Total</span>
                        </div>
                        <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ $finishedCount }} <span
                                class="text-sm font-bold text-slate-400 uppercase">Kali</span></h3>
                    </div>
                </div>

                {{-- PINJAMAN AKTIF & OVERDUE CHECK --}}
                @if($activeLoans->count() > 0)
                    <div class="mb-10">
                        <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs mb-5">Barang di Tangan Anda</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activeLoans as $active)
                                @php
                                    $isOverdue = \Carbon\Carbon::parse($active->tgl_kembali_rencana)->isPast();
                                    $delay = \Carbon\Carbon::parse($active->tgl_kembali_rencana)->diffInDays(now(), false);
                                @endphp
                                <div class="glass-card overflow-hidden transition-all hover:translate-x-1 duration-300 {{ $isOverdue ? 'ring-2 ring-rose-500 bg-rose-50/20' : 'ring-1 ring-slate-100 hover:ring-indigo-500' }} flex items-center p-2 gap-3">
                                    {{-- Photo Section - Left Side Mini --}}
                                    <div class="relative w-14 h-14 bg-slate-50 rounded-xl overflow-hidden shadow-inner flex-shrink-0">
                                        @if($active->alat->images && count($active->alat->images) > 0)
                                            <img src="{{ asset($active->alat->images[0]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        @if($isOverdue)
                                            <div class="absolute inset-0 bg-rose-500/10"></div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2 mb-0.5">
                                            <h5 class="text-[10px] font-black text-slate-800 truncate uppercase tracking-tight">{{ $active->alat->nama_alat }}</h5>
                                            <span class="text-[8px] font-black text-indigo-600 bg-indigo-50 px-1 rounded">{{ $active->jumlah }}x</span>
                                        </div>
                                        
                                        <div class="flex items-center gap-2 text-[7px] font-bold text-slate-400 uppercase tracking-tighter mb-1">
                                            <span>Deadline: {{ \Carbon\Carbon::parse($active->tgl_kembali_rencana)->format('d/m') }}</span>
                                            @if($isOverdue)
                                                <span class="text-rose-500 font-black">+{{ floor($delay) }} Hari</span>
                                            @endif
                                        </div>
                                        
                                        @if($isOverdue)
                                            <div class="flex items-center justify-between">
                                                @php
                                                    $finePerDay = $active->alat->denda_per_hari ?? 5000;
                                                    $totalFine = floor(max(0, $delay)) * $finePerDay;
                                                @endphp
                                                <div class="flex items-center gap-1">
                                                    <div class="w-1 h-1 bg-rose-500 rounded-full animate-ping"></div>
                                                    <span class="text-[9px] font-black text-rose-600 tracking-tighter">Rp{{ number_format($totalFine, 0, ',', '.') }}</span>
                                                </div>
                                                <form method="POST" action="{{ route('loans.requestReturn', $active->id) }}" onsubmit="return confirm('Ajukan pengembalian alat ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-2 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded text-[7px] font-black uppercase tracking-widest shadow-sm transition">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-1.5 opacity-60">
                                                    <svg class="w-2 h-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    <p class="text-[6px] font-bold text-slate-400 uppercase">Aman</p>
                                                </div>
                                                <form method="POST" action="{{ route('loans.requestReturn', $active->id) }}" onsubmit="return confirm('Ajukan pengembalian alat ini?');">
                                                    @csrf
                                                    <button type="submit" class="px-2 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-[7px] font-black uppercase tracking-widest shadow-sm transition">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- RIWAYAT TABLE --}}
                <div class="glass-card p-6 flex flex-col group hover:ring-2 hover:ring-indigo-500 transition-all">
                    <div class="flex items-center justify-between mb-8">
                        <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Aktivitas Terakhir</h4>
                        <a href="{{ route('loans.index') }}"
                            class="text-xs font-bold text-indigo-600 hover:underline decoration-2 underline-offset-4">Selengkapnya</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($recentLoans as $loan)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="py-6 pr-6 flex items-center gap-5">
                                            <div class="w-12 h-12 bg-slate-50 rounded-xl overflow-hidden flex items-center justify-center text-slate-400 group-hover:ring-2 group-hover:ring-indigo-500 transition-all font-black">
                                                @if($loan->alat->images && count($loan->alat->images) > 0)
                                                    <img src="{{ asset($loan->alat->images[0]) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($loan->alat->nama_alat ?? 'A', 0, 1) }}
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-800 tracking-tight">{{ $loan->alat->nama_alat }}</p>
                                                <p class="text-[10px] text-slate-300 font-bold uppercase">{{ $loan->created_at->format('d M, H:i') }}</p>
                                            </div>
                                        </td>
                                        <td class="py-6 text-sm font-black text-slate-600 tracking-tight">{{ $loan->jumlah }} Unit</td>
                                        <td class="py-6 text-right">
                                            @php $s = $loan->status; @endphp
                                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $s == 'borrowed' ? 'bg-indigo-50 text-indigo-600' : ($s == 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                                {{ $s }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-20 text-center text-slate-300 italic font-bold">Data tidak ditemukan.</td></tr>
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