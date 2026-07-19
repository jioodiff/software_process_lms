<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - {{ config('app.name', 'Lab Management System') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex items-center justify-center relative bg-slate-50">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0" style="background-image: url('{{ asset('images/login-bg.png') }}'); background-size: cover; background-position: center; opacity: 0.8;"></div>

    <!-- Login Card Wrapper -->
    <div class="w-full max-w-5xl p-4 sm:p-6 lg:p-8 relative z-10">
        
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition.opacity.duration.500ms class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-2 max-w-md mx-auto transition-all duration-300" role="alert">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity.duration.500ms class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl shadow-sm text-sm flex items-center gap-2 max-w-md mx-auto transition-all duration-300" role="alert">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row border border-slate-100 min-h-[500px]">
            
            <!-- Left Banner Section -->
            <div class="hidden md:block md:w-1/2 relative bg-slate-900">
                <img src="{{ asset('images/login-banner.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="Lab Banner">
                
                <!-- Dark Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent"></div>
                
                <!-- Banner Text -->
                <div class="absolute bottom-0 left-0 p-10 md:p-12 w-full">
                    <h1 class="text-3xl lg:text-4xl font-bold text-white tracking-tight leading-tight mb-2 shadow-sm drop-shadow-md">
                        LAB MANAGEMENT SYSTEM
                    </h1>
                    <p class="text-lg lg:text-xl text-blue-100 font-medium tracking-wide drop-shadow-sm">
                        Universitas IPWIJA
                    </p>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-start items-center bg-white relative" x-data="{ step: {{ session('otp_sent_to') ? 2 : 1 }} }">
                
                <div class="w-full max-w-sm mt-4 lg:mt-8">
                    <div class="text-center mb-8">
                        <!-- Logo -->
                        <img src="{{ asset('images/logo-ipwija.png') }}" alt="Logo IPWIJA" class="h-20 mx-auto mb-6 object-contain">
                        
                        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900 mb-2">Lupa Password?</h2>
                        <p class="text-sm text-slate-500" x-show="step === 1">Masukkan email terdaftar Anda. Kami akan mengirimkan kode OTP untuk reset password.</p>
                        <p class="text-sm text-slate-500" x-show="step === 2" x-cloak>Masukkan 6 digit kode OTP yang telah dikirimkan ke email Anda.</p>
                    </div>

                    <!-- STEP 1: Enter Email -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5 w-full" x-show="step === 1">
                        @csrf
                        
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                                class="block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-900 shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm transition-all duration-200 bg-slate-50 hover:bg-slate-100 focus:bg-white outline-none border" placeholder="email@ipwija.ac.id">
                            @error('email')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="flex w-full justify-center items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 transform hover:-translate-y-0.5">
                                Kirim Kode OTP
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>

                    <!-- STEP 2: Enter OTP -->
                    <form method="POST" action="{{ route('password.verify') }}" class="space-y-5 w-full" x-show="step === 2" x-cloak>
                        @csrf
                        
                        <input type="hidden" name="email" value="{{ session('otp_sent_to') }}">

                        <div>
                            <label for="otp" class="block text-sm font-semibold text-slate-700 mb-2">Kode OTP</label>
                            <input id="otp" name="otp" type="text" required maxlength="6"
                                class="block w-full text-center tracking-widest text-xl font-mono rounded-xl border-slate-200 py-3 px-4 text-slate-900 shadow-sm placeholder:text-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 bg-slate-50 hover:bg-slate-100 focus:bg-white outline-none border" placeholder="••••••">
                            @error('otp')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="pt-4 space-y-3">
                            <button type="submit" class="flex w-full justify-center items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 transform hover:-translate-y-0.5">
                                Lanjutkan
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                            <button type="button" @click="step = 1" class="flex w-full justify-center items-center gap-2 rounded-xl bg-white border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200">
                                Kembali
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                        <p class="text-sm text-slate-500">
                            Ingat password Anda?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">Masuk di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>
