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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased auth-gradient min-h-screen">
    <div class="min-h-screen flex flex-col justify-center items-center p-4 lg:p-6">
        <div class="w-full max-w-[560px]">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <a href="/" class="group">
                    <div
                        class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-200 group-hover:scale-110 transition-transform duration-300">
                        <svg viewBox="0 0 24 24" fill="none" class="w-8 h-8 text-white" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4h-1a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 011 1v1a2 2 0 11-4 0V4z" />
                        </svg>
                    </div>
                </a>
                <h1 class="mt-6 text-2xl font-black text-slate-800 tracking-tight">Buat Akun Baru</h1>
                <p class="text-slate-500 text-sm font-medium">Bergabunglah untuk mulai meminjam alat</p>
            </div>

            <!-- Card -->
            <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-8 md:p-10"
                x-data="registerForm()">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama
                                Lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="Joko Susilo">
                            @if ($errors->get('name'))
                                <ul class="mt-1 text-xs text-red-600">@foreach ((array) $errors->get('name') as $message)
                                <li>{{ $message }}</li> @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Username</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="jokos123">
                            @if ($errors->get('username'))
                                <ul class="mt-1 text-xs text-red-600">
                                    @foreach ((array) $errors->get('username') as $message) <li>{{ $message }}</li>
                                    @endforeach</ul>
                            @endif
                        </div>

                        <!-- WhatsApp Number -->
                        <div>
                            <label for="phone"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nomor
                                WhatsApp</label>
                            <div class="flex gap-2 relative">
                                <input id="phone" type="tel" name="phone" x-model="phone" value="{{ old('phone') }}"
                                    required
                                    class="flex-1 w-full bg-white border-slate-100 rounded-2xl py-3.5 pl-5 pr-[88px] text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                    placeholder="+628...">
                                <button type="button" @click="sendOtp()" :disabled="otpLoading"
                                    class="absolute right-2 top-2 bottom-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-3 rounded-xl transition-all disabled:opacity-50 text-[10px] uppercase tracking-wider shadow-md">
                                    <span x-show="!otpLoading">Kirim OTP</span>
                                    <span x-show="otpLoading">...</span>
                                </button>
                            </div>
                            <p x-text="otpMessage" class="mt-2 text-xs font-bold"
                                :class="otpStatus === 'error' ? 'text-red-600' : 'text-emerald-600'"></p>
                            @if ($errors->get('phone'))
                                <ul class="mt-1 text-xs text-red-600">@foreach ((array) $errors->get('phone') as $message)
                                <li>{{ $message }}</li> @endforeach
                                </ul>
                            @endif
                        </div>

                        <!-- OTP Code -->
                        <div>
                            <label for="otp_code"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Kode
                                OTP</label>
                            <input id="otp_code" type="text" name="otp_code" required maxlength="6"
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none uppercase"
                                placeholder="XXXXXX">
                            @if ($errors->get('otp_code'))
                                <ul class="mt-1 text-xs text-red-600">
                                    @foreach ((array) $errors->get('otp_code') as $message) <li>{{ $message }}</li>
                                    @endforeach</ul>
                            @endif
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
                            <input id="password" type="password" name="password" required
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="••••••••">
                            @if ($errors->get('password'))
                                <ul class="mt-1 text-xs text-red-600">
                                    @foreach ((array) $errors->get('password') as $message) <li>{{ $message }}</li>
                                    @endforeach</ul>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full bg-white border-slate-100 rounded-2xl py-3.5 px-5 text-slate-700 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="••••••••">
                        </div>

                    </div>

                    <div class="mt-8">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-6 rounded-2xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                            Daftar Sekarang
                        </button>

                        <p class="text-center text-xs font-bold text-slate-400 pt-5 uppercase tracking-wider">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                                class="text-indigo-600 hover:text-indigo-700 pb-0.5 border-b-2 border-indigo-600/30 hover:border-indigo-600 transition-colors">Masuk
                                saja</a>
                        </p>
                    </div>
                </form>
            </div>

            <script>
                function registerForm() {
                    return {
                        phone: '',
                        otpLoading: false,
                        otpMessage: '',
                        otpStatus: '',
                        async sendOtp() {
                            if (!this.phone) {
                                this.otpMessage = 'Isi nomor WhatsApp terlebih dahulu.';
                                this.otpStatus = 'error';
                                return;
                            }

                            this.otpLoading = true;
                            this.otpMessage = 'Mengirim OTP...';
                            this.otpStatus = 'info';

                            try {
                                const response = await fetch('{{ route('register.otp') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ phone: this.phone })
                                });

                                const data = await response.json();

                                if (response.ok) {
                                    this.otpMessage = data.message;
                                    this.otpStatus = 'success';
                                } else {
                                    this.otpMessage = data.message || 'Gagal mengirim OTP.';
                                    this.otpStatus = 'error';
                                }
                            } catch (error) {
                                this.otpMessage = 'Kesalahan jaringan.';
                                this.otpStatus = 'error';
                            } finally {
                                this.otpLoading = false;
                            }
                        }
                    }
                }
            </script>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-slate-400 font-medium">&copy; {{ date('Y') }} PinjamAlat System. All
                rights reserved.</p>
        </div>
    </div>
</body>

</html>