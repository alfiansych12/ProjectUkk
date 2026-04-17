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
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden" x-data="{ addModal: false, editModal: false, editData: {id: null, name: '', username: '', email: '', role: ''} }">
                <!-- Mobile Top Nav -->
                <header class="lg:hidden bg-white border-b border-slate-100 p-4 flex justify-between items-center">
                   <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5 text-white" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                            </svg>
                        </div>
                        <span class="font-bold text-slate-800">PinjamAlat</span>
                   </div>
                   <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </header>

                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none p-4 md:p-8">
                    <div class="mb-10 flex items-end justify-between">
                        <div>
                            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Pengguna</h2>
                            <p class="text-slate-400 text-sm mt-1 font-medium">Kelola akun pengguna dan hak akses sistem.</p>
                        </div>
                        <button @click="addModal = true" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah User
                        </button>
                    </div>

                    <div class="glass-card overflow-hidden">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama / Email</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Username</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Role</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($users as $user)
                                    <tr class="group hover:bg-slate-50/30 transition-colors">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-black text-slate-400 text-xs border-2 border-white shadow-sm group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-all">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-black text-slate-800 text-sm tracking-tight">{{ $user->name }}</p>
                                                    <p class="text-[10px] font-bold text-slate-400">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-sm font-bold text-slate-600">{{ $user->username }}</td>
                                        <td class="px-8 py-6">
                                            <span class="inline-flex px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
                                                {{ $user->role == 'admin' ? 'bg-indigo-50 text-indigo-700' : '' }}
                                                {{ $user->role == 'petugas' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ $user->role == 'peminjam' ? 'bg-slate-100 text-slate-600' : '' }}
                                            ">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="editData = {id: {{ $user->id }}, name: '{{ $user->name }}', username: '{{ $user->username }}', email: '{{ $user->email }}', role: '{{ $user->role }}'}; editModal = true" class="w-9 h-9 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-indigo-500 hover:text-white transition-all shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                @if($user->id !== Auth::id())
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" class="inline">
                                                        @csrf @method('DELETE')
                                                        <button class="w-9 h-9 flex items-center justify-center text-rose-300 hover:text-rose-600 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($users->hasPages())
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    @endif
                </main>

                {{-- MODAL ADD --}}
                <template x-if="addModal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="addModal = false"></div>
                        <div class="relative w-full max-w-lg bg-white rounded-lg p-8 shadow-2xl animate-in zoom-in duration-200">
                            <h3 class="text-xl font-bold text-slate-800 mb-6">Tambah User Baru</h3>
                            <form action="{{ route('admin.users.store') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                                        <input type="text" name="username" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                                        <input type="email" name="email" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                                        <select name="role" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                            <option value="peminjam">Peminjam</option>
                                            <option value="petugas">Petugas</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                                        <input type="password" name="password" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-8">
                                    <button type="button" @click="addModal = false" class="flex-1 py-3 px-4 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                                    <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                {{-- MODAL EDIT --}}
                <template x-if="editModal">
                    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>
                        <div class="relative w-full max-w-lg bg-white rounded-lg p-8 shadow-2xl animate-in zoom-in duration-200">
                            <h3 class="text-xl font-bold text-slate-800 mb-6">Edit User</h3>
                            <form :action="'/admin/users/' + editData.id" method="POST">
                                @csrf @method('PUT')
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" :value="editData.name" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                                        <input type="text" name="username" :value="editData.username" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                                        <input type="email" name="email" :value="editData.email" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                                        <select name="role" :value="editData.role" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                            <option value="peminjam">Peminjam</option>
                                            <option value="petugas">Petugas</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Password (Kosongkan jika tidak diubah)</label>
                                        <input type="password" name="password" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div class="flex gap-3 mt-8">
                                    <button type="button" @click="editModal = false" class="flex-1 py-3 px-4 rounded-xl bg-slate-100 text-slate-600 font-bold text-sm hover:bg-slate-200 transition">Batal</button>
                                    <button type="submit" class="flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white font-bold text-sm hover:bg-indigo-700 transition">Simpan Perubahan</button>
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
