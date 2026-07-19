<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-slate-50 text-slate-900 antialiased h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - LMS Universitas IPWIJA</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex overflow-hidden">
    
    <!-- Sidebar -->
    <div class="w-72 bg-white flex flex-col hidden md:flex shrink-0 border-r border-slate-200 relative z-20">
        <div class="h-24 flex items-center px-8 border-b border-slate-100 bg-white">
            <img src="{{ asset('images/logo-ipwija.png') }}" alt="Logo IPWIJA" class="w-10 h-10 object-contain mr-3">
            <div class="flex flex-col">
                <span class="font-extrabold tracking-tight text-lg text-slate-900 leading-tight">Lab Management</span>
                <span class="font-semibold text-[11px] text-blue-600 uppercase tracking-wider leading-tight">Universitas IPWIJA</span>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
            @if(auth()->user()->isAdmin())
                <p class="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 mt-2">Dashboard</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Overview
                </a>
                
                <p class="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Master Data</p>
                <a href="{{ route('admin.tools.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.tools.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="wrench" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.tools.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Katalog Alat
                </a>
                <a href="{{ route('admin.items.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.items.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="boxes" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.items.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Inventaris Barang
                </a>
                
                <p class="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Operasional</p>
                <a href="{{ route('admin.borrowings.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.borrowings.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="clipboard-list" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.borrowings.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Peminjaman Alat
                </a>
                
                <p class="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Sistem</p>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.reports.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Laporan
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="users" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Manajemen User
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="activity" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.audit-logs.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Audit Trail
                </a>
            @else
                <p class="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 mt-2">{{ ucfirst(auth()->user()->role) }}</p>
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 {{ request()->routeIs('mahasiswa.dashboard') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Dashboard
                </a>
                <a href="{{ route('mahasiswa.catalog.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('mahasiswa.catalog.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="search" class="w-5 h-5 mr-3 {{ request()->routeIs('mahasiswa.catalog.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Katalog Alat
                </a>
                <a href="{{ route('mahasiswa.borrowings.index') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('mahasiswa.borrowings.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="book-open" class="w-5 h-5 mr-3 {{ request()->routeIs('mahasiswa.borrowings.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Peminjaman Saya
                </a>
                
                <p class="px-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 mt-6">Akun</p>
                <a href="{{ route('mahasiswa.profile.edit') }}" class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('mahasiswa.profile.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <i data-lucide="user" class="w-5 h-5 mr-3 {{ request()->routeIs('mahasiswa.profile.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    Profil
                </a>
            @endif
        </div>
        
        <div class="p-4 border-t border-slate-100 mt-auto">
            <div class="flex items-center px-2">
                <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->nama_lengkap }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-4" onsubmit="Object.keys(localStorage).forEach(k => k.startsWith('cart_items_') && localStorage.removeItem(k));">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50 relative overflow-hidden">
        <!-- Top header -->
        <header class="h-24 px-10 lg:px-16 flex items-center justify-between shrink-0 relative z-10">
            <div class="max-w-7xl mx-auto w-full flex items-center justify-between">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight">@yield('title')</h1>
                </div>
            </div>
        </header>

        <!-- Main scrollable area -->
        <main class="flex-1 overflow-y-auto px-10 lg:px-16 pb-12 relative">
            <div class="max-w-7xl mx-auto w-full">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition.opacity.duration.500ms class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-xl shadow-sm flex items-center gap-3">
                        <div class="p-1 bg-green-100 rounded-full">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity.duration.500ms class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl shadow-sm flex items-center gap-3">
                        <div class="p-1 bg-red-100 rounded-full">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    
    <style>
        .bg-grid-slate-100 {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='%23f1f5f9'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
        }
    </style>
    @stack('scripts')
</body>
</html>
