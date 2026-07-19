@extends('layouts.app')

@section('title', 'Katalog Alat ' . ucfirst(auth()->user()->role))

@section('content')
    <style>
        @media (min-width: 1024px) {
            .cart-open-padding {
                padding-right: 28rem !important;
                /* 448px, exact width of max-w-md */
            }
        }
    </style>
    <div x-data="catalogCart()" class="transition-[padding] duration-500 ease-in-out"
        :class="{'cart-open-padding': isCartOpen}">
        <!-- Search Section -->
        <div class="bg-white rounded-2xl p-4 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 mb-8">
            <form action="{{ route('mahasiswa.catalog.index') }}" method="GET"
                class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama atau kode alat..."
                        class="block w-full pl-11 pr-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors sm:text-sm">
                </div>

                <div class="w-full sm:w-auto flex items-center gap-3">
                    <select name="kategori"
                        class="bg-slate-50 border-slate-200 text-slate-700 rounded-xl py-2.5 px-4 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 cursor-pointer w-full sm:w-auto sm:text-sm font-medium transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kat)
                            <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="bg-blue-600 text-white hover:bg-blue-700 px-6 py-2.5 rounded-xl font-semibold transition-all duration-300 shadow-sm shrink-0 sm:text-sm">
                        Cari Alat
                    </button>
                </div>
            </form>

            @if(request()->anyFilled(['search', 'kategori']))
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-sm text-slate-500">Hasil pencarian filter aktif</span>
                    <a href="{{ route('mahasiswa.catalog.index') }}"
                        class="text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1.5">
                        <i data-lucide="x" class="w-3 h-3"></i> Reset Filter
                    </a>
                </div>
            @endif
        </div>

        <!-- Tools Grid -->
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Alat Tersedia</h3>
            <button type="button" @click="isCartOpen = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Keranjang
                <span x-cloak x-show="totalItems > 0"
                    class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"
                    x-text="totalItems"></span>
            </button>
        </div>

        <div class="grid gap-6 transition-all duration-500"
            :class="isCartOpen ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5'">
            @forelse($tools as $tool)
                <div
                    class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                    <div class="h-52 relative flex items-center justify-center overflow-hidden bg-white p-2">
                        @if($tool->foto_alat)
                            <img src="{{ Storage::url($tool->foto_alat) }}" alt="{{ $tool->nama_alat }}"
                                class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">
                        @else
                            <i data-lucide="cpu"
                                class="w-16 h-16 text-slate-300 group-hover:text-blue-300 transition-all duration-500 relative z-10 group-hover:scale-110"></i>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex justify-between items-start gap-2 mb-2">
                            <p class="text-[11px] font-mono font-semibold text-slate-400 truncate">{{ $tool->kode_alat }}</p>
                            <span
                                class="inline-flex items-center rounded bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 border border-slate-200 shrink-0 whitespace-nowrap">{{ $tool->kategori }}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg mb-2 line-clamp-1 group-hover:text-blue-600 transition-colors"
                            title="{{ $tool->nama_alat }}">{{ $tool->nama_alat }}</h4>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-5 flex-1">
                            {{ $tool->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                        <div class="mt-auto pt-4 border-t border-slate-100 border-dashed mb-3 space-y-2.5">
                            <div class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i> Lokasi: <span
                                    class="text-slate-700">{{ $tool->lokasi ?? '-' }}</span>
                            </div>
                            <div
                                class="flex flex-wrap items-center justify-between gap-x-2 gap-y-1 bg-green-50 px-3 py-2 rounded-xl border border-green-100/50">
                                <div class="flex items-center gap-1.5 whitespace-nowrap">
                                    <i data-lucide="box" class="w-4 h-4 text-green-500 shrink-0"></i>
                                    <span
                                        class="text-green-600/80 text-[10px] sm:text-[11px] font-bold uppercase tracking-wider">Tersedia</span>
                                </div>
                                <span class="font-black text-green-700 text-sm whitespace-nowrap">{{ $tool->stok_tersedia }}
                                    <span class="text-[10px] font-semibold text-green-600/60 uppercase">Unit</span></span>
                            </div>
                        </div>

                        <button type="button"
                            @click="addToCart({{ $tool->id }}, '{{ $tool->kode_alat }}', '{{ addslashes($tool->nama_alat) }}', {{ $tool->stok_tersedia }})"
                            class="w-full bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2">
                            <i data-lucide="plus" class="w-4 h-4"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-slate-200 border-dashed shadow-sm">
                    <div
                        class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                        <i data-lucide="search-x" class="w-10 h-10 text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2 tracking-tight">Alat tidak ditemukan</h3>
                    <p class="text-slate-500 font-medium max-w-md mx-auto">Coba gunakan kata kunci pencarian lain atau pilih
                        kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        @if($tools->hasPages())
            <div class="mt-10 bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-100 w-full">
                {{ $tools->links() }}
            </div>
        @endif

        <!-- Cart Slide-over Panel -->
        <div x-cloak x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-50 w-full max-w-md shadow-2xl border-l border-slate-200 bg-white">

            <form action="{{ route('mahasiswa.borrowings.store') }}" method="POST" class="flex h-full flex-col bg-white">
                @csrf

                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-white">
                    <h2 class="text-lg font-extrabold text-slate-800 flex items-center gap-2" id="slide-over-title">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-blue-600"></i> Keranjang Peminjaman
                    </h2>
                    <button type="button" @click="isCartOpen = false"
                        class="relative rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 p-2 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6 bg-slate-50/50">

                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl text-sm">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Selected Items -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 mb-3 flex items-center justify-between">
                            Daftar Alat
                            <span class="text-xs font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full"
                                x-text="items.length + ' Item'"></span>
                        </h3>

                        <div x-show="items.length > 0" class="space-y-3">
                            <template x-for="(item, index) in items" :key="item.id">
                                <div
                                    class="flex items-center gap-4 bg-white border border-slate-200 rounded-xl p-3 shadow-sm hover:border-blue-300 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-mono font-bold text-slate-400 mb-0.5" x-text="item.kode">
                                        </p>
                                        <p class="text-sm font-bold text-slate-800 truncate" x-text="item.name"></p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <input type="hidden" :name="'items['+index+'][tool_id]'" :value="item.id">
                                            <div
                                                class="flex items-center bg-slate-50 border border-slate-200 rounded-lg overflow-hidden">
                                                <button type="button" @click="item.qty > 1 ? item.qty-- : null"
                                                    class="px-2 py-1 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">-</button>
                                                <input type="number" :name="'items['+index+'][jumlah_unit]'"
                                                    x-model.number="item.qty"
                                                    @input="if(item.qty > item.max) item.qty = item.max"
                                                    @blur="if(!item.qty || item.qty < 1) item.qty = 1" min="1"
                                                    :max="item.max"
                                                    class="w-10 text-center border-0 bg-transparent text-xs py-1 focus:ring-0 font-bold p-0">
                                                <button type="button" @click="item.qty < item.max ? item.qty++ : null"
                                                    class="px-2 py-1 text-slate-500 hover:bg-slate-200 hover:text-slate-700 transition-colors">+</button>
                                            </div>
                                            <span class="text-xs font-medium text-slate-400">Maks: <span
                                                    x-text="item.max"></span></span>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeFromCart(index)"
                                        class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 p-2.5 rounded-xl transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div x-show="items.length === 0"
                            class="text-center py-10 bg-white border border-dashed border-slate-200 rounded-2xl">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="shopping-cart" class="w-5 h-5 text-slate-300"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">Keranjang masih kosong.</p>
                            <p class="text-xs text-slate-400 mt-1">Silakan pilih alat dari katalog.</p>
                        </div>
                    </div>

                    <!-- Form Inputs -->
                    <div class="space-y-4 pt-6 border-t border-slate-200">
                        <h3 class="text-sm font-bold text-slate-700 mb-1">Informasi Peminjaman</h3>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Tgl Pinjam
                                <span class="text-rose-500">*</span></label>
                            <input type="date" name="tgl_rencana_pinjam" x-model="tglPinjam"
                                min="{{ date('Y-m-d') }}" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white shadow-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Tgl
                                Kembali <span class="text-rose-500">*</span></label>
                            <input type="date" name="tgl_rencana_kembali" x-model="tglKembali"
                                :min="tglPinjam || '{{ date('Y-m-d') }}'" required
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white shadow-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Keperluan
                                <span class="text-rose-500">*</span></label>
                            <textarea name="keperluan" rows="3" required placeholder="Jelaskan tujuan peminjaman..."
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white shadow-sm">{{ old('keperluan') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 px-6 py-5 bg-white">
                    <button type="submit" x-bind:disabled="items.length === 0"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed hover:-translate-y-0.5">
                        <i data-lucide="send" class="w-4 h-4"></i> Ajukan Peminjaman
                    </button>
                    <p class="text-[10px] text-center text-slate-400 mt-3">Pastikan data sudah benar sebelum submit.</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('catalogCart', () => ({
                isCartOpen: localStorage.getItem('cart_open_{{ auth()->id() }}') === 'true' || {{ $errors->any() ? 'true' : 'false' }},
                items: JSON.parse(localStorage.getItem('cart_items_{{ auth()->id() }}') || '[]'),
                tglPinjam: '{{ old('tgl_rencana_pinjam') }}',
                tglKembali: '{{ old('tgl_rencana_kembali') }}',

                init() {
                    this.$watch('tglPinjam', val => {
                        if (val && this.tglKembali < val) {
                            this.tglKembali = val;
                        }
                    });
                    // Watch items and save to localStorage scoped by user
                    this.$watch('items', val => localStorage.setItem('cart_items_{{ auth()->id() }}', JSON.stringify(val)), { deep: true });
                    // Watch isCartOpen to save its state
                    this.$watch('isCartOpen', val => localStorage.setItem('cart_open_{{ auth()->id() }}', val));
                },

                addToCart(id, kode, nama, maxStok) {
                    // Check if already in cart
                    const existingIndex = this.items.findIndex(item => item.id === id.toString());

                    if (existingIndex !== -1) {
                        // Increase quantity if below max
                        if (this.items[existingIndex].qty < maxStok) {
                            this.items[existingIndex].qty++;
                        }
                    } else {
                        // Add new item
                        this.items.push({
                            id: id.toString(),
                            kode: kode,
                            name: nama,
                            max: parseInt(maxStok),
                            qty: 1
                        });

                        // Re-init icons for the new elements in the DOM
                        this.$nextTick(() => {
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        });
                    }

                    // Open cart sidebar
                    this.isCartOpen = true;
                },

                removeFromCart(index) {
                    this.items.splice(index, 1);
                },

                get totalItems() {
                    return this.items.reduce((total, item) => total + parseInt(item.qty || 0), 0);
                }
            }));
        });
    </script>
@endsection