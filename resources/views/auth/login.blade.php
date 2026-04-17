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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased auth-gradient min-h-screen">
        <div class="min-h-screen flex flex-col justify-center items-center p-6">
            <div class="w-full max-w-[440px]">
                <!-- Logo Section -->
                <div class="flex flex-col items-center mb-8">
                    <a href="/" class="group">
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                            <svg viewBox="0 0 24 24" fill="none" class="w-8 h-8 text-white" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                            </svg>
                        </div>
                    </a>
                    <h1 class="mt-6 text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h1>
                    <p class="text-slate-500 text-sm font-medium">Masuk untuk melanjutkan aktivitas Anda</p>
                </div>

                <!-- Card -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-10">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Login Identity -->
                        <div>
                            <label for="login" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email, WhatsApp, atau Username</label>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus 
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="Masukkan email/wa/username">
                            @if ($errors->get('login'))
                                <ul class="mt-2 text-sm text-red-600 space-y-1">
                                    @foreach ((array) $errors->get('login') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="flex justify-between items-center mb-2 ml-1">
                                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-500 transition-colors uppercase tracking-wider">Lupa Password?</a>
                                @endif
                            </div>
                            <input id="password" type="password" name="password" required 
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="••••••••">
                            @if ($errors->get('password'))
                                <ul class="mt-2 text-sm text-red-600 space-y-1">
                                    @foreach ((array) $errors->get('password') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>


                        <!-- Actions -->
                        <div class="flex items-center justify-between py-2">
                            <label class="inline-flex items-center cursor-pointer group">
                                 <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-200 text-indigo-600 focus:ring-indigo-500 bg-white cursor-pointer group-hover:border-indigo-300 transition-colors">
                                 <span class="ms-2 text-xs font-bold text-slate-500 group-hover:text-slate-700 transition-colors">Ingat saya</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                            Masuk Sekarang
                        </button>

                        @if (Route::has('register'))
                            <p class="text-center text-xs font-bold text-slate-400 pt-2 uppercase tracking-wider">
                                Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700">Daftar Gratis</a>
                            </p>
                        @endif
                    </form>
                </div>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-slate-400 font-medium">&copy; {{ date('Y') }} PinjamAlat System. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
