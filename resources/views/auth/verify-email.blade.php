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
            <div class="w-full max-w-[480px]">
                <!-- Logo -->
                <div class="flex flex-col items-center mb-8 text-center">
                    <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-200">
                        <svg viewBox="0 0 24 24" fill="none" class="w-8 h-8 text-white" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h1 class="mt-6 text-2xl font-black text-slate-800 tracking-tight">Verifikasi Email Anda</h1>
                    <p class="text-slate-500 text-sm font-medium mt-3 px-4 leading-relaxed">Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerimanya, kami akan mengirimkan yang baru.</p>
                </div>

                <!-- Card -->
                <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-2xl rounded-[2.5rem] p-10">
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-6 font-bold text-sm text-green-600 bg-green-50 p-4 rounded-xl border border-green-100 italic">
                            {{ __('Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat pendaftaran.') }}
                        </div>
                    @endif

                    <div class="flex flex-col gap-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-2xl shadow-lg transition-all duration-300">
                                Kirim Ulang Email Verifikasi
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3.5 rounded-2xl transition-all">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
