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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                        </svg>
                    </div>
                    <h1 class="mt-6 text-2xl font-black text-slate-800 tracking-tight">Atur Ulang Password</h1>
                </div>

                <!-- Card -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-2xl rounded-[2.5rem] p-10">
                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus 
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all" />
                            @if ($errors->get('email'))
                                <p class="mt-1 text-xs text-red-600">@foreach ((array) $errors->get('email') as $message) {{ $message }} @endforeach</p>
                            @endif
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Password Baru</label>
                            <input id="password" type="password" name="password" required 
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all" />
                            @if ($errors->get('password'))
                                <p class="mt-1 text-xs text-red-600">@foreach ((array) $errors->get('password') as $message) {{ $message }} @endforeach</p>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required 
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all" />
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 rounded-2xl shadow-lg transition-all">
                            Atur Ulang Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
