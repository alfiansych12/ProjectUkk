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

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 10px;
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
                    <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Master Data</div>
                    {!! $sidebarItem(route('admin.users.index'), request()->routeIs('admin.users.*'), 'users', 'User') !!}
                    {!! $sidebarItem(route('admin.kategoris.index'), request()->routeIs('admin.kategoris.*'), 'tag', 'Kategori') !!}
                    {!! $sidebarItem(route('admin.alats.index'), request()->routeIs('admin.alats.*'), 'tool', 'Alat') !!}
                @endif

                @if(in_array(Auth::user()->role, ['admin', 'petugas']))
                    <div class="pt-4 pb-2 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4">Operasional</div>
                    {!! $sidebarItem(route('loans.manage'), request()->routeIs('loans.manage'), 'clipboard', 'Peminjaman') !!}
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
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden" x-data="offlineLoanData()">
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none p-4 md:p-8 custom-scrollbar">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Pinjaman</h2>
                        <p class="text-slate-400 text-sm mt-1 font-medium">Verifikasi dan kelola transaksi peminjaman alat.</p>
                    </div>
                    <div class="flex gap-4">
                        <button @click="offlineModal = true"
                            class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Entri Manual
                        </button>
                        @if(Auth::user()->role !== 'admin')
                            <a href="{{ route('loans.report') }}"
                                class="flex items-center gap-2 px-6 py-3 bg-white border border-slate-100 text-slate-600 font-bold text-sm rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m6-10V4a1 1 0 00-1-1H6a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Laporan Penuh
                            </a>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div
                        class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div
                        class="mb-8 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3 text-rose-700 font-bold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="glass-card overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Peminjam</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Alat & Jumlah</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Periode</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($loans as $loan)
                                <tr class="group hover:bg-slate-50/30 transition-colors">
                                    @php
                                        $borrowerName = $loan->borrower_name ?: optional($loan->user)->name ?: 'Unknown';
                                        $borrowerMeta = $loan->borrower_whatsapp ? 'WA: ' . $loan->borrower_whatsapp : (optional($loan->user)->role ?: 'Peminjam');
                                    @endphp
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs border-2 border-white shadow-sm group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-all">
                                                {{ substr($borrowerName, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-black text-slate-800 text-sm tracking-tight">{{ $borrowerName }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $borrowerMeta }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-slate-600">
                                        <div class="flex items-center gap-2">
                                            <span class="p-1 px-2 bg-slate-100 rounded-lg text-indigo-600 text-[10px] font-black">{{ $loan->jumlah }}x</span>
                                            <span class="truncate">{{ $loan->alat->nama_alat }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col gap-0.5">
                                            <p class="text-[11px] font-black text-slate-600 tracking-tight">
                                                {{ \Carbon\Carbon::parse($loan->tgl_pinjam)->format('d M') }} — {{ \Carbon\Carbon::parse($loan->tgl_kembali_rencana)->format('d M') }}
                                            </p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase uppercase tracking-widest">
                                                {{ \Carbon\Carbon::parse($loan->tgl_kembali_rencana)->format('Y') }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col gap-2">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
                                                {{ $loan->status == 'pending' ? 'bg-amber-50 text-amber-600' : '' }}
                                                {{ $loan->status == 'borrowed' ? 'bg-indigo-50 text-indigo-600' : '' }}
                                                {{ $loan->status == 'menunggu_pengembalian' ? 'bg-cyan-50 text-cyan-600' : '' }}
                                                {{ $loan->status == 'returned' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                                {{ $loan->status == 'rejected' ? 'bg-rose-50 text-rose-500' : '' }}
                                            ">
                                                @if($loan->status == 'pending')
                                                    <span class="relative flex h-1.5 w-1.5">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-amber-500"></span>
                                                    </span>
                                                    Menunggu
                                                @elseif($loan->status == 'borrowed')
                                                    Dipinjam
                                                @elseif($loan->status == 'menunggu_pengembalian')
                                                    Menunggu Kembali
                                                @elseif($loan->status == 'returned')
                                                    Kembali
                                                @elseif($loan->status == 'rejected')
                                                    Ditolak
                                                @else
                                                    {{ $loan->status }}
                                                @endif
                                            </span>
                                            
                                            @if($loan->denda > 0)
                                                <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-black border border-rose-100 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Rp{{ number_format($loan->denda, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if(Auth::user()->role !== 'admin')
                                                @if($loan->status == 'pending')
                                                    <form action="{{ route('loans.approve', $loan) }}" method="POST" class="inline"> @csrf
                                                        <button type="submit" class="w-9 h-9 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Setujui">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('loans.reject', $loan) }}" method="POST" class="inline"> @csrf
                                                        <button type="submit" class="w-9 h-9 flex items-center justify-center bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Tolak">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                @elseif($loan->status == 'borrowed' || $loan->status == 'menunggu_pengembalian')
                                                    <button type="button" @click="selectedLoan = {id: {{ $loan->id }}, user_name: {{ json_encode($borrowerName) }}, alat_name: {{ json_encode($loan->alat->nama_alat) }}, tgl_pinjam: '{{ $loan->tgl_pinjam }}', tgl_kembali_rencana: '{{ $loan->tgl_kembali_rencana }}', jumlah: {{ $loan->jumlah }} }; returnModal = true" 
                                                        class="px-4 py-2 bg-indigo-50 text-indigo-600 font-black text-[10px] rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm uppercase tracking-widest border border-indigo-100">
                                                        Proses Kembali
                                                    </button>
                                                @elseif(Auth::user()->role !== 'admin' && in_array($loan->status, ['returned', 'rejected']))
                                                    <span class="text-[10px] font-bold text-slate-300 italic tracking-tight mr-2">Selesai</span>
                                                @endif
                                            @endif
                                            
                                            <button type="button" @click='selectedLoan = {id: {{ $loan->id }}, user_name: {{ json_encode($borrowerName) }}, borrower_name: {{ json_encode($loan->borrower_name) }}, borrower_whatsapp: {{ json_encode($loan->borrower_whatsapp) }}, alat_name: {{ json_encode($loan->alat->nama_alat) }}, tgl_pinjam: "{{ $loan->tgl_pinjam }}", tgl_kembali_rencana: "{{ $loan->tgl_kembali_rencana }}", jumlah: {{ $loan->jumlah }} }; editModal = true' 
                                                class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-indigo-500 hover:text-white transition-all shadow-sm" title="Edit Data">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                            
                                            <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data peminjaman ini?')">
                                                @csrf @method('DELETE')
                                                <button class="w-9 h-9 flex items-center justify-center text-rose-300 hover:text-rose-600 transition-colors" title="Hapus Data">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-20">
                                            <svg class="w-16 h-16 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <p class="font-black text-xl text-slate-800">Tidak ada pengajuan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Row Filter Section -->
                    <div class="border-t border-slate-100 bg-slate-50/50 px-8 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-slate-600">Tampilkan:</span>
                            @php
                                $currentPerPage = request()->query('per_page', 5);
                            @endphp
                            <select onchange="window.location.href='{{ route('loans.manage') }}?per_page=' + this.value" 
                                    class="px-6 py-1.5 pr-10 rounded-lg text-sm font-semibold border border-slate-200 bg-white text-slate-600 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer appearance-none bg-no-repeat bg-right"
                                    style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22><path d=%22M6 9l6 6 6-6%22></path></svg>'); background-position: right 8px center; background-size: 20px;">
                                <option value="5" {{ $currentPerPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $currentPerPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $currentPerPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $currentPerPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                            <span class="text-sm text-slate-500">Baris</span>
                        </div>
                        @if($loans->hasPages())
                            <div class="flex items-center gap-4 pl-8">
                                {{ $loans->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- MODAL OFFLINE LOAN --}}
                <template x-if="offlineModal">
                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="offlineModal = false"></div>
                        <div class="relative w-full max-w-4xl bg-white rounded-3xl p-8 shadow-2xl animate-in zoom-in duration-200">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-black text-slate-800 tracking-tight">Peminjaman Offline</h3>
                                <button @click="offlineModal = false" class="text-slate-400 hover:text-slate-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form action="{{ route('loans.storeOffline') }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <!-- Row 1: Nama Peminjam & Nomor WhatsApp -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Peminjam</label>
                                        <input type="text" name="borrower_name" required placeholder="Nama lengkap peminjam"
                                            class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nomor WhatsApp</label>
                                        <div class="flex gap-2">
                                            <input x-model="borrowerWhatsapp" type="tel" name="borrower_whatsapp" required placeholder="+628..."
                                                class="flex-1 rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                            <button type="button" @click="sendOfflineOtp()"
                                                class="px-3 py-2 bg-indigo-600 text-white font-black text-xs rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 uppercase tracking-widest whitespace-nowrap">
                                                Kirim OTP
                                            </button>
                                        </div>
                                        <p x-text="otpMessage" class="mt-1.5 text-xs" :class="otpStatus === 'error' ? 'text-rose-600' : 'text-emerald-600'"></p>
                                    </div>
                                </div>

                                <!-- Row 2: OTP Message Info -->
                                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p class="text-[10px] text-slate-500 font-medium italic">Kode OTP telah dikirim ke WhatsApp. Gunakan kode OTP untuk memverifikasi nomor WhatsApp peminjam.</p>
                                </div>

                                <!-- Row 3: Kode OTP & Pilih Alat -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kode OTP</label>
                                        <input type="text" name="otp_code" maxlength="6" required placeholder="Masukkan 6 digit"
                                            class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-center tracking-widest">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Pilih Alat</label>
                                        <select name="alat_id" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                            <option value="">-- Pilih Alat --</option>
                                            @foreach($alats as $alat)
                                                <option value="{{ $alat->id }}">
                                                    {{ $alat->nama_alat }} (Stok: {{ $alat->stok }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Row 4: Tgl Pinjam, Estimasi Kembali, Jumlah -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tgl Pinjam</label>
                                        <input type="date" name="tgl_pinjam" value="{{ date('Y-m-d') }}" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Estimasi Kembali</label>
                                        <input type="date" name="tgl_kembali_rencana" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jumlah</label>
                                        <input type="number" name="jumlah" value="1" min="1" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500 transition-all text-center">
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="flex gap-3 pt-2">
                                    <button type="button" @click="offlineModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold text-xs rounded-2xl hover:bg-slate-200 transition-all uppercase tracking-widest">Batal</button>
                                    <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white font-black text-xs rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 uppercase tracking-widest">Simpan Transaksi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                {{-- MODAL RETURN --}}
                <template x-if="returnModal">
                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="returnModal = false"></div>
                        <div class="relative w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl animate-in zoom-in duration-200">
                            <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2">Proses Pengembalian</h3>
                            <p class="text-xs font-bold text-slate-400 mb-6 uppercase tracking-widest">Alat: <span class="text-indigo-600" x-text="selectedLoan.alat_name"></span></p>

                            <form :action="'/loans/' + selectedLoan.id + '/return'" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tanggal Kembali Real</label>
                                    <input type="date" name="tgl_kembali_real" value="{{ date('Y-m-d') }}" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-indigo-500">
                                    <p class="mt-2 text-[10px] text-slate-400 italic font-medium">*Denda akan dihitung otomatis jika melampaui estimasi.</p>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" @click="returnModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold text-xs rounded-2xl hover:bg-slate-200 transition-all">Batal</button>
                                    <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-black text-xs rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 uppercase tracking-widest">Konfirmasi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                {{-- MODAL EDIT LOAN --}}
                <template x-if="editModal">
                    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>
                        <div class="relative w-full max-w-lg bg-white rounded-3xl p-8 shadow-2xl animate-in zoom-in duration-200">
                            <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2">Edit Data Pinjaman</h3>
                            <p class="text-xs font-bold text-slate-400 mb-6 uppercase tracking-widest">Peminjam: <span class="text-indigo-600" x-text="selectedLoan.user_name"></span></p>

                            <form :action="'/loans/' + selectedLoan.id" method="POST" class="space-y-5">
                                @csrf @method('PUT')
                                
                                <template x-if="selectedLoan.borrower_name !== null">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Peminjam</label>
                                            <input type="text" name="borrower_name" x-model="selectedLoan.borrower_name" required 
                                                class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nomor WhatsApp</label>
                                            <input type="text" name="borrower_whatsapp" x-model="selectedLoan.borrower_whatsapp" required 
                                                class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700">
                                        </div>
                                    </div>
                                </template>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tgl Pinjam</label>
                                        <input type="date" name="tgl_pinjam" x-model="selectedLoan.tgl_pinjam" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Estimasi Kembali</label>
                                        <input type="date" name="tgl_kembali_rencana" x-model="selectedLoan.tgl_kembali_rencana" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jumlah</label>
                                    <input type="number" name="jumlah" :value="selectedLoan.jumlah" min="1" required class="w-full rounded-2xl border-slate-100 bg-slate-50 text-sm font-bold text-slate-700">
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" @click="editModal = false" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold text-xs rounded-2xl">Batal</button>
                                    <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white font-black text-xs rounded-2xl shadow-lg shadow-indigo-100 uppercase tracking-widest">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>
            </main>
        </div>
    </div>

    <script>
        function offlineLoanData() {
            return {
                offlineModal: false,
                returnModal: false,
                editModal: false,
                selectedLoan: {id: null, user_name: '', borrower_name: '', borrower_whatsapp: '', alat_name: '', tgl_pinjam: '', tgl_kembali_rencana: '', jumlah: 1},
                otpMessage: '',
                otpStatus: '',
                borrowerWhatsapp: '',
                async sendOfflineOtp() {
                    console.log('Mengirim OTP ke:', this.borrowerWhatsapp);
                    this.otpMessage = 'Sedang mengirim...';
                    this.otpStatus = 'info';

                    if (!this.borrowerWhatsapp) {
                        this.otpMessage = 'Isi nomor WhatsApp terlebih dahulu.';
                        this.otpStatus = 'error';
                        return;
                    }

                    try {
                        const response = await fetch('{{ route('loans.sendOfflineOtp') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ borrower_whatsapp: this.borrowerWhatsapp }),
                        });

                        const data = await response.json();
                        console.log('Response dari server:', data);

                        if (!response.ok) throw new Error(data.message || 'Gagal mengirim OTP.');

                        this.otpMessage = data.message;
                        this.otpStatus = 'success';
                    } catch (error) {
                        console.error('Error OTP:', error);
                        this.otpMessage = error.message;
                        this.otpStatus = 'error';
                    }
                },
            };
        }
    </script>
</body>

</html>
