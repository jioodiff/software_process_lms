<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Sistem - {{ config('app.name', 'Lab Management System') }}</title>
    
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
        <div class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row border border-slate-100 min-h-[600px]">
            
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

            <!-- Right Login Form Section -->
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-start items-center bg-white relative">
                
                <div class="w-full max-w-sm mt-4 lg:mt-8">
                    <div class="text-center mb-8">
                        <!-- Logo -->
                        <img src="{{ asset('images/logo-ipwija.png') }}" alt="Logo IPWIJA" class="h-20 mx-auto mb-6 object-contain">
                        
                        <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900 mb-2">Masuk ke Sistem</h2>
                        <p class="text-sm text-slate-500">Masukkan akun Anda untuk mengakses sistem manajemen laboratorium.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5 w-full">
                    @csrf
                    
                    <!-- Email/NIM/NUPTK Field -->
                    <div>
                        <label for="login" class="block text-sm font-semibold text-slate-700 mb-2">Email, NIM, atau NUPTK</label>
                        <div class="relative">
                            <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                                class="block w-full rounded-xl border-slate-200 py-3 px-4 text-slate-900 shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm sm:leading-6 transition-all duration-200 bg-slate-50 hover:bg-slate-100 focus:bg-white outline-none border" placeholder="Masukkan Email, NIM, atau NUPTK">
                        </div>
                        @error('login')
                            <p class="mt-2 text-sm text-red-500 flex items-center gap-1 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-xl border-slate-200 py-3 pl-4 pr-12 text-slate-900 shadow-sm placeholder:text-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 sm:text-sm sm:leading-6 transition-all duration-200 bg-slate-50 hover:bg-slate-100 focus:bg-white outline-none border" placeholder="••••••••">
                            
                            <!-- Show/Hide Password Toggle -->
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-blue-600 transition-colors focus:outline-none" aria-label="Toggle password visibility">
                                <svg id="eye-icon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer relative group">
                            <input id="remember" name="remember" type="checkbox" class="peer sr-only">
                            <div class="w-4 h-4 bg-white border border-slate-300 rounded peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-focus:ring-2 peer-focus:ring-blue-600/30 transition-all flex items-center justify-center group-hover:border-blue-400">
                                <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="ml-2 block text-sm font-medium text-slate-600">Ingat saya</span>
                        </label>
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-semibold text-blue-600 hover:text-blue-500">Lupa password?</a>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="flex w-full justify-center items-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-md hover:bg-blue-700 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200 transform hover:-translate-y-0.5">
                            Masuk
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>

                    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                        <p class="text-sm text-slate-500">
                            Belum memiliki akun?
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">Daftar sekarang</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</body>
</html>
