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
        @if($errors->any())
            <div
                class="fixed top-24 right-8 z-[100] bg-rose-50 border border-rose-200 p-4 rounded-2xl shadow-xl animate-in slide-in-from-right duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center text-white font-bold">!
                    </div>
                    <div>
                        <p class="text-sm font-bold text-rose-800">Ups! Ada masalah</p>
                        <p class="text-[10px] text-rose-600 uppercase font-black tracking-widest">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden" x-data="{
                addModal: false,
                editModal: false,
                addPreviews: [],
                editPreviews: [],
                addFiles: new DataTransfer(),
                editFiles: new DataTransfer(),
                editData: {id: null, name: '', specs: '', stok: 0, price: 0, fine: 5000, categoryId: null, images: []},
                baseUrl: '{{ asset('') }}',
                
                handleAddFiles(event) {
                    const dt = this.addFiles;
                    Array.from(event.target.files).forEach(f => {
                        dt.items.add(f);
                        const reader = new FileReader();
                        reader.onload = (e) => { this.addPreviews.push(e.target.result); };
                        reader.readAsDataURL(f);
                    });
                    document.getElementById('addFilesInput').files = dt.files;
                },
                removeAddPreview(i) {
                    const dt = new DataTransfer();
                    const files = Array.from(this.addFiles.files);
                    files.splice(i, 1);
                    files.forEach(f => dt.items.add(f));
                    this.addFiles = dt;
                    this.addPreviews.splice(i, 1);
                    document.getElementById('addFilesInput').files = dt.files;
                },
                handleEditFiles(event) {
                    const dt = this.editFiles;
                    Array.from(event.target.files).forEach(f => {
                        dt.items.add(f);
                        const reader = new FileReader();
                        reader.onload = (e) => { this.editPreviews.push(e.target.result); };
                        reader.readAsDataURL(f);
                    });
                    document.getElementById('editFilesInput').files = dt.files;
                },
                removeEditPreview(i) {
                    const dt = new DataTransfer();
                    const files = Array.from(this.editFiles.files);
                    files.splice(i, 1);
                    files.forEach(f => dt.items.add(f));
                    this.editFiles = dt;
                    this.editPreviews.splice(i, 1);
                    document.getElementById('editFilesInput').files = dt.files;
                },
                removeExistingImage(i) { this.editData.images.splice(i, 1); },
                
                submitAddForm() {
                    const form = document.getElementById('addAlatForm');
                    const formData = new FormData(form);
                    // Add files from the DataTransfer
                    const fileInput = document.getElementById('addFilesInput');
                    if (fileInput && fileInput.files.length > 0) {
                        Array.from(fileInput.files).forEach(f => formData.append('images[]', f));
                    }
                    
                    fetch('{{ route('admin.alats.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert('Error: ' + Object.values(data.errors).flat().join(', '));
                        } else {
                            this.addModal = false;
                            this.addPreviews = [];
                            this.addFiles = new DataTransfer();
                            setTimeout(() => location.reload(), 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan alat');
                    });
                },
                
                submitEditForm() {
                    const form = document.getElementById('editAlatForm');
                    const formData = new FormData(form);
                    // Add new files
                    const fileInput = document.getElementById('editFilesInput');
                    if (fileInput && fileInput.files.length > 0) {
                        Array.from(fileInput.files).forEach(f => formData.append('images[]', f));
                    }
                    // Add existing images
                    formData.append('existing_images', JSON.stringify(this.editData.images));
                    
                    fetch('/admin/alats/' + this.editData.id, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.errors) {
                            alert('Error: ' + Object.values(data.errors).flat().join(', '));
                        } else {
                            this.editModal = false;
                            setTimeout(() => location.reload(), 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memperbarui alat');
                    });
                }
            }">
            <!-- Mobile Top Nav -->
            <header class="lg:hidden bg-white border-b border-slate-100 p-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-white" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                        </svg>
                    </div>
                    <span class="font-bold text-slate-800">PinjamAlat</span>
                </div>
                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </header>

            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none p-4 md:p-8">
                <div class="mb-10 flex items-end justify-between">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Alat</h2>
                        <p class="text-slate-400 text-sm mt-1 font-medium">Kelola inventaris alat, stok, dan harga sewa.</p>
                    </div>
                    <button @click="addModal = true" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Alat
                    </button>
                </div>

                <div class="glass-card overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Alat</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kategori</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Stok</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Hrg Sewa</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Denda / Hari</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($alats as $alat)
                                <tr class="group hover:bg-slate-50/30 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            @if($alat->images && count($alat->images) > 0)
                                                <img src="{{ asset($alat->images[0]) }}" class="w-10 h-10 rounded-xl object-cover bg-slate-100 shadow-sm">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-black text-slate-800 text-sm tracking-tight">{{ $alat->nama_alat }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 truncate max-w-[200px]">{{ $alat->spesifikasi }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-slate-600">{{ $alat->kategori->nama_kategori }}</td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl text-[10px] font-black tracking-widest ring-1 ring-indigo-100">
                                            {{ $alat->stok }} Unit
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-slate-800">Rp{{ number_format($alat->harga_sewa_per_hari, 0, ',', '.') }}</td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-bold text-rose-600">Rp{{ number_format($alat->denda_per_hari, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                @click="editData = {id: {{ $alat->id }}, name: '{{ $alat->nama_alat }}', specs: '{{ $alat->spesifikasi }}', stok: {{ $alat->stok }}, price: {{ $alat->harga_sewa_per_hari }}, fine: {{ $alat->denda_per_hari }}, categoryId: {{ $alat->kategori_id }}, images: {{ json_encode($alat->images) }} }; editModal = true"
                                                class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-indigo-500 hover:text-white transition-all shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form action="{{ route('admin.alats.destroy', $alat) }}" method="POST" onsubmit="return confirm('Hapus alat ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button class="w-9 h-9 flex items-center justify-center text-rose-300 hover:text-rose-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Row Filter Section -->
                    <div class="border-t border-slate-100 bg-slate-50/50 px-8 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-slate-600">Tampilkan:</span>
                            @php
                                $currentPerPage = request()->query('per_page', 5);
                            @endphp
                            <select onchange="window.location.href='{{ route('admin.alats.index') }}?per_page=' + this.value + '{{ request()->has('kategori_id') ? '&kategori_id=' . request('kategori_id') : '' }}'" 
                                    class="px-6 py-1.5 pr-10 rounded-lg text-sm font-semibold border border-slate-200 bg-white text-slate-600 focus:border-indigo-500 focus:ring-indigo-500 cursor-pointer appearance-none bg-no-repeat bg-right"
                                    style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22><path d=%22M6 9l6 6 6-6%22></path></svg>'); background-position: right 8px center; background-size: 20px;">
                                <option value="5" {{ $currentPerPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $currentPerPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $currentPerPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $currentPerPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                            <span class="text-sm text-slate-500">Baris</span>
                        </div>
                        @if($alats->hasPages())
                            <div class="flex items-center gap-4 pl-8">
                                {{ $alats->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </main>

            {{-- MODAL ADD --}}
            <template x-if="addModal">
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModal = false"></div>
                    <div
                        class="relative w-full max-w-5xl bg-white rounded-2xl overflow-hidden shadow-2xl animate-in zoom-in duration-200 flex min-h-[500px] max-h-[90vh]">
                        <!-- Left: Form -->
                        <div class="flex-1 min-w-0 p-8 border-r border-slate-100 overflow-y-auto custom-scrollbar">
                            <h3 class="text-xl font-bold text-slate-800 mb-6">Tambah Alat Baru</h3>
                            <form @submit.prevent="submitAddForm" action="{{ route('admin.alats.store') }}" method="POST" enctype="multipart/form-data"
                                id="addAlatForm">
                                @csrf
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Alat</label>
                                            <input type="text" name="nama_alat" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                                            <select name="kategori_id" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                                @foreach($kategoris as $k)
                                                    <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Spesifikasi</label>
                                        <textarea name="spesifikasi" rows="4"
                                            class="w-full rounded-xl border-slate-200 focus:ring-indigo-500"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Stok</label>
                                            <input type="number" name="stok" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Sewa /
                                                Hari</label>
                                            <input type="number" name="harga_sewa_per_hari" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Denda / Hari (Keterlambatan)</label>
                                            <input type="number" name="denda_per_hari" value="5000" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-8">
                                    <button type="button" @click="addModal = false"
                                        class="flex-1 py-3 px-4 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                                    <button type="submit"
                                        class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Simpan
                                        Alat</button>
                                </div>
                        </div>
                        <!-- Right: Images -->
                        <div
                            class="w-64 min-w-[256px] flex-shrink-0 bg-slate-100/30 p-4 overflow-y-auto custom-scrollbar border-l border-slate-100">
                            <h4 class="text-[10px] font-black text-slate-500 mb-3 uppercase tracking-widest">Foto Alat
                                <span class="text-slate-300">(Opsional)</span>
                            </h4>
                            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">
                                <template x-for="(src, i) in addPreviews" :key="i">
                                    <div
                                        style="width:calc(33.33% - 4px);aspect-ratio:1/1;overflow:hidden;border-radius:8px;background:#e2e8f0;flex-shrink:0;position:relative;">
                                        <img :src="src" style="width:100%;height:100%;object-fit:cover;display:block;">
                                        <div @click.stop="removeAddPreview(i)"
                                            style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;cursor:pointer;border-radius:8px;background:rgba(239,68,68,0);transition:background 0.15s;"
                                            @mouseenter="$el.style.background='rgba(239,68,68,0.6)'; $el.querySelector('svg').style.opacity='1'"
                                            @mouseleave="$el.style.background='rgba(239,68,68,0)'; $el.querySelector('svg').style.opacity='0'">
                                            <svg style="width:18px;height:18px;opacity:0;transition:opacity 0.15s;pointer-events:none;"
                                                fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <label
                                class="relative flex flex-col items-center justify-center w-full rounded-2xl border-2 border-dashed border-indigo-200 p-4 text-indigo-400 hover:border-indigo-400 hover:bg-indigo-50 cursor-pointer transition-all">
                                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span class="text-[10px] font-bold uppercase">Tambah Foto</span>
                                <input type="file" multiple class="absolute inset-0 opacity-0 cursor-pointer"
                                    @change="handleAddFiles($event)">
                            </label>
                            {{-- Actual hidden input that carries the files --}}
                            <input type="file" name="images[]" id="addFilesInput" multiple class="hidden">
                        </div>
                        </form>
                    </div>
                </div>
            </template>

            {{-- MODAL EDIT --}}
            <template x-if="editModal">
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>
                    <div
                        class="relative w-full max-w-5xl bg-white rounded-2xl overflow-hidden shadow-2xl animate-in zoom-in duration-200 flex min-h-[500px] max-h-[90vh]">
                        <form @submit.prevent="submitEditForm" :action="'/admin/alats/' + editData.id" method="POST" enctype="multipart/form-data"
                            id="editAlatForm" class="flex w-full">
                            @csrf @method('PUT')
                            <!-- Left: Form -->
                            <div class="flex-1 min-w-0 p-8 border-r border-slate-100 overflow-y-auto custom-scrollbar">
                                @if ($errors->any())
                                    <div class="mb-4 p-4 bg-rose-50 text-rose-600 rounded-xl text-sm font-bold">
                                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif
                                <h3 class="text-xl font-bold text-slate-800 mb-6">Edit Alat</h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Alat</label>
                                            <input type="text" name="nama_alat" :value="editData.name" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                                            <select name="kategori_id" :value="editData.categoryId" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                                @foreach($kategoris as $k)
                                                    <option value="{{ $k->id }}"
                                                        :selected="editData.categoryId == {{ $k->id }}">
                                                        {{ $k->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Spesifikasi</label>
                                        <textarea name="spesifikasi" rows="4" :value="editData.specs"
                                            class="w-full rounded-xl border-slate-200 focus:ring-indigo-500"></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Stok</label>
                                            <input type="number" name="stok" :value="editData.stok" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Sewa /
                                                Hari</label>
                                            <input type="number" name="harga_sewa_per_hari" :value="editData.price"
                                                required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Denda / Hari</label>
                                            <input type="number" name="denda_per_hari" :value="editData.fine" required
                                                class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-8">
                                    <button type="button" @click="editModal = false"
                                        class="flex-1 py-3 px-4 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                                    <button type="submit"
                                        class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Simpan
                                        Perubahan</button>
                                </div>
                            </div>
                            <!-- Right: Images -->
                            <div
                                class="w-64 min-w-[256px] flex-shrink-0 bg-slate-100/30 p-4 overflow-y-auto custom-scrollbar border-l border-slate-100">
                                <h4 class="text-[10px] font-black text-slate-500 mb-3 uppercase tracking-widest">Foto
                                    Alat</h4>
                                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">
                                    <template x-for="(img, i) in editData.images" :key="'e'+i">
                                        <div
                                            style="width:calc(33.33% - 4px);aspect-ratio:1/1;overflow:hidden;border-radius:8px;background:#e2e8f0;flex-shrink:0;position:relative;">
                                            <img :src="'/' + img"
                                                style="width:100%;height:100%;object-fit:cover;display:block;">
                                            <div @click.stop="removeExistingImage(i)"
                                                style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;cursor:pointer;border-radius:8px;background:rgba(239,68,68,0);transition:background 0.15s;"
                                                @mouseenter="$el.style.background='rgba(239,68,68,0.6)'; $el.querySelector('svg').style.opacity='1'"
                                                @mouseleave="$el.style.background='rgba(239,68,68,0)'; $el.querySelector('svg').style.opacity='0'">
                                                <svg style="width:18px;height:18px;opacity:0;transition:opacity 0.15s;pointer-events:none;"
                                                    fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-for="(src, i) in editPreviews" :key="'n'+i">
                                        <div
                                            style="width:calc(33.33% - 4px);aspect-ratio:1/1;overflow:hidden;border-radius:8px;background:#e2e8f0;outline:2px solid #818cf8;flex-shrink:0;position:relative;">
                                            <img :src="src"
                                                style="width:100%;height:100%;object-fit:cover;display:block;">
                                            <div @click.stop="removeEditPreview(i)"
                                                style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;cursor:pointer;border-radius:8px;background:rgba(239,68,68,0);transition:background 0.15s;"
                                                @mouseenter="$el.style.background='rgba(239,68,68,0.6)'; $el.querySelector('svg').style.opacity='1'"
                                                @mouseleave="$el.style.background='rgba(239,68,68,0)'; $el.querySelector('svg').style.opacity='0'">
                                                <svg style="width:18px;height:18px;opacity:0;transition:opacity 0.15s;pointer-events:none;"
                                                    fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <label
                                    class="relative flex flex-col items-center justify-center w-full rounded-2xl border-2 border-dashed border-indigo-200 p-4 text-indigo-400 hover:border-indigo-400 hover:bg-indigo-50 cursor-pointer transition-all">
                                    <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase">Tambah Foto</span>
                                    <input type="file" multiple class="absolute inset-0 opacity-0 cursor-pointer"
                                        @change="handleEditFiles($event)">
                                </label>
                                {{-- Hidden input for NEW files --}}
                                <input type="file" name="images[]" id="editFilesInput" multiple class="hidden">
                                {{-- Hidden input for REMAINING existing files --}}
                                <input type="hidden" name="existing_images" :value="JSON.stringify(editData.images)">
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>

    </div>
    @stack('scripts')
</body>

</html>