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
                <!-- Logo -->
                <div class="flex flex-col items-center mb-8">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-200">
                        <svg viewBox="0 0 24 24" fill="none" class="w-8 h-8 text-white" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h1 class="mt-6 text-2xl font-black text-slate-800 tracking-tight text-center">Konfirmasi Password</h1>
                    <p class="text-slate-500 text-sm font-medium text-center mt-3 leading-relaxed">Ini adalah area aman aplikasi. Silakan konfirmasi password Anda sebelum melanjutkan.</p>
                </div>

                <!-- Card -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-2xl rounded-[2.5rem] p-10">
                    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                        @csrf

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                            <input id="password" type="password" name="password" required autofocus 
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="••••••••">
                            @if ($errors->get('password'))
                                <ul class="mt-2 text-sm text-red-600">@foreach ((array) $errors->get('password') as $message) <li>{{ $message }}</li> @endforeach</ul>
                            @endif
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-2xl shadow-lg transition-all duration-300">
                            Konfirmasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
