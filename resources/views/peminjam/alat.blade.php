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

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
        }
    </style>
</head>

<body class="bg-[#fcfdfe] antialiased" x-data="catalogApp()">
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

                @if(Auth::user()->role === 'peminjam')
                    {!! $sidebarItem(route('alats.index'), request()->routeIs('alats.*'), 'search', 'Cari Alat') !!}
                    {!! $sidebarItem(route('loans.index'), request()->routeIs('loans.index'), 'clock', 'Pinjaman Saya') !!}
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
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 text-rose-500 hover:bg-rose-50 rounded-2xl transition-all duration-200 group">
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="font-bold text-sm">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none p-4 md:p-8">
                <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Cari Alat</h2>
                        <p class="text-slate-400 text-sm mt-1 font-medium">Temukan koleksi alat terbaik kami untuk
                            proyek Anda.</p>
                    </div>

                    {{-- Search & Filter --}}
                    <div class="flex items-center gap-3">
                        <form action="{{ route('alats.index') }}" method="GET" class="relative group">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama alat..."
                                class="w-64 pl-10 pr-4 py-2 bg-white border border-slate-100 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm">
                            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-hover:text-indigo-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </form>
                    </div>
                </div>

                {{-- Alert Success --}}
                @if(session('success'))
                    <div
                        class="mb-8 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 animate-fade-in-down">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                        </svg>
                        <p class="text-sm font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Catalog Grid --}}
                @if($alats->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-slate-300">
                        <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-bold text-lg">Tidak ada alat ditemukan</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach($alats as $alat)
                                        @php $images = $alat->images ?? []; @endphp
                                        <div @click="openDetail({{ $alat->id }}, {{ json_encode([
                                'id' => $alat->id,
                                'name' => $alat->nama_alat,
                                'category' => $alat->kategori->nama_kategori,
                                'specs' => $alat->spesifikasi,
                                'stok' => $alat->stok,
                                'price' => $alat->harga_sewa_per_hari,
                                'fine' => $alat->denda_per_hari,
                                'images' => array_map(fn($img) => asset($img), $images),
                            ]) }})"
                                            class="glass-card overflow-hidden cursor-pointer card-hover transition-all duration-300 group ring-1 ring-slate-100 hover:ring-indigo-500">

                                            {{-- Foto --}}
                                            <div class="relative w-full bg-slate-50 overflow-hidden" style="aspect-ratio:4/3;">
                                                @if(count($images) > 0)
                                                    <img src="{{ asset($images[0]) }}"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-200">
                                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="absolute top-4 left-4">
                                                    <span
                                                        class="px-2.5 py-1 bg-white/90 backdrop-blur-md rounded-xl text-[10px] font-black uppercase tracking-wider text-indigo-600 shadow-sm border border-white/50">
                                                        {{ $alat->kategori->nama_kategori }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Info --}}
                                            <div class="p-6">
                                                <h3 class="font-black text-slate-800 text-sm mb-1 truncate tracking-tight">
                                                    {{ $alat->nama_alat }}</h3>
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-indigo-600 font-extrabold text-lg tracking-tighter">
                                                            Rp{{ number_format($alat->harga_sewa_per_hari, 0, ',', '.') }}
                                                        </p>
                                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Per
                                                            Hari</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <span
                                                            class="text-[10px] font-black {{ $alat->stok > 0 ? 'text-emerald-500' : 'text-rose-500' }} uppercase tracking-wider">
                                                            {{ $alat->stok }} Unit
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        @endforeach
                    </div>
                @endif
            </main>
        </div>
    </div>

    {{-- ===== DETAIL OVERLAY ===== --}}
    <div x-show="detailOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeDetail()" x-show="detailOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        {{-- Modal --}}
        <div class="relative w-full max-w-xl bg-white rounded-3xl overflow-hidden shadow-2xl grid grid-cols-2"
            style="height:450px; max-height:85vh;" x-show="detailOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            {{-- Close button --}}
            <button @click="closeDetail()"
                class="absolute top-4 right-4 z-20 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-slate-500 hover:text-slate-800 shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- LEFT: Carousel --}}
            <div class="relative bg-slate-50 flex flex-col h-full border-r border-slate-50">
                <div class="flex-1 relative overflow-hidden">
                    <template x-if="detail.images && detail.images.length > 0">
                        <img :src="detail.images[carousel].startsWith('http') ? detail.images[carousel] : baseUrl + detail.images[carousel]" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!detail.images || detail.images.length === 0">
                        <div class="w-full h-full flex items-center justify-center text-slate-200 absolute inset-0">
                            <svg class="w-16 h-16 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </template>

                    {{-- Nav arrows --}}
                    <template x-if="detail.images && detail.images.length > 1">
                        <div>
                            <button @click="carousel = carousel > 0 ? carousel - 1 : detail.images.length - 1"
                                class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 rounded-full shadow-sm flex items-center justify-center hover:bg-white transition text-slate-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button @click="carousel = carousel < detail.images.length - 1 ? carousel + 1 : 0"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 rounded-full shadow-sm flex items-center justify-center hover:bg-white transition text-slate-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Thumbnail strip --}}
                <template x-if="detail.images && detail.images.length > 1">
                    <div class="absolute bottom-4 left-0 right-0 flex gap-1.5 px-4 overflow-x-auto justify-center">
                        <template x-for="(img, i) in detail.images" :key="i">
                            <button @click="carousel = i"
                                :class="i === carousel ? 'ring-2 ring-indigo-500 scale-110' : 'opacity-70 blur-[0.5px] hover:opacity-100'"
                                class="w-10 h-10 flex-shrink-0 rounded-xl overflow-hidden border-2 border-white shadow-lg transition-all">
                                <img :src="img.startsWith('http') ? img : baseUrl + img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </template>
            </div>

            {{-- RIGHT: Detail + Form --}}
            <div class="p-6 overflow-y-auto custom-scrollbar flex flex-col bg-white">
                <span
                    class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-700 text-[8px] font-black uppercase rounded-lg tracking-wider mb-1 w-fit"
                    x-text="detail.category"></span>
                <h2 class="text-lg font-black text-slate-800 leading-tight mb-0.5" x-text="detail.name"></h2>
                <div class="flex items-baseline gap-1 mb-3">
                    <span class="text-2xl font-black text-indigo-600 tracking-tighter"
                        x-text="'Rp' + Number(detail.price).toLocaleString('id-ID')"></span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">/ hari</span>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <div class="px-2 py-1 bg-rose-50 text-rose-600 rounded-lg text-[9px] font-black uppercase flex items-center gap-1.5 ring-1 ring-rose-100">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Denda: <span x-text="'Rp' + Number(detail.fine).toLocaleString('id-ID')"></span> / hari
                    </div>
                    <div :class="detail.stok > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500'"
                        class="px-2 py-1 rounded-lg text-[9px] font-black uppercase flex items-center gap-1.5">
                        <span class="w-1 h-1 rounded-full inline-block"
                            :class="detail.stok > 0 ? 'bg-emerald-500' : 'bg-rose-400'"></span>
                        <span x-text="detail.stok > 0 ? detail.stok + ' Unit' : 'Habis'"></span>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Spesifikasi Alat
                    </p>
                    <p class="text-xs text-slate-600 leading-relaxed font-medium" x-text="detail.specs"></p>
                </div>



                <div class="mt-auto pt-6 border-t border-slate-50">
                    <template x-if="detail.stok > 0">
                        <form action="{{ route('loans.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="alat_id" :value="detail.id">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5 px-1">Tgl
                                        Pinjam</label>
                                    <input type="date" name="tgl_pinjam" :min="today" :value="today"
                                        class="w-full rounded-2xl border-slate-100 text-[11px] font-bold py-2 focus:ring-indigo-500 transition-all"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-1.5 px-1">Tgl
                                        Kembali</label>
                                    <input type="date" name="tgl_kembali_rencana" :min="today" :value="tomorrow"
                                        class="w-full rounded-2xl border-slate-100 text-[11px] font-bold py-2 focus:ring-indigo-500 transition-all"
                                        required>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-20">
                                    <input type="number" name="jumlah" min="1" :max="detail.stok" value="1"
                                        class="w-full rounded-2xl border-slate-100 text-xs font-bold py-2 focus:ring-indigo-500 transition-all"
                                        required>
                                </div>
                                <button type="submit"
                                    class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs rounded-2xl shadow-lg shadow-indigo-100 transition-all uppercase tracking-wider">
                                    Pinjam Alat
                                </button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        function catalogApp() {
            const today = new Date().toISOString().split('T')[0];
            const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
            return {
                detailOpen: false,
                carousel: 0,
                today,
                tomorrow,
                baseUrl: '{{ asset('') }}',
                detail: { id: null, name: '', category: '', specs: '', stok: 0, price: 0, fine: 0, images: [] },
                openDetail(id, data) {
                    this.detail = data;
                    this.carousel = 0;
                    this.detailOpen = true;
                    document.body.style.overflow = 'hidden';
                },
                closeDetail() {
                    this.detailOpen = false;
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
</body>

</html>