@include('app.partials.head', [
    'title' => 'Beranda'
])

@php
    $userName = auth()->check() ? auth()->user()->name : 'Masum';
@endphp

<div class="px-4 pt-4 space-y-5 pb-28">
    <!-- 1. Header Profil & Notifikasi -->
    <header class="flex items-center justify-between">
        <div class="min-w-0 flex-1 mr-3">
            <h1
                class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-800 tracking-tight leading-none">
                AZCLIP</h1>
            <p class="text-sm font-semibold text-slate-700 truncate mt-1">
                Halo, {{ $userName }} 👋</p>
        </div>

        <a href="#"
            class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-colors relative active:scale-95 shrink-0"
            aria-label="Notifikasi">
            <i class="fa-regular fa-bell text-base"></i>
            <span class="w-2 h-2 rounded-full bg-rose-500 absolute top-2 right-2 ring-2 ring-white"></span>
        </a>
    </header>

    <!-- 2. Wallet & Earning Banner -->
    <section
        class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-3xl p-4 text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden">
        <div class="absolute -left-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex justify-between items-center mb-3">
            <div>
                <span class="text-xs font-medium text-indigo-100/80">Saldo Tersedia</span>
                <p class="text-2xl font-extrabold tracking-tight mt-0.5">Rp 450.000</p>
            </div>
            <a href="#"
                class="w-10 h-10 bg-white text-indigo-700 hover:bg-indigo-50 flex items-center justify-center rounded-full transition-colors shadow-sm shrink-0"
                aria-label="Withdraw">
                <i class="fa-solid fa-arrow-down-to-bracket"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/15">
            <div>
                <span class="text-[11px] text-indigo-200">Total Views Klip</span>
                <p class="text-base font-bold">142.800</p>
            </div>
            <div>
                <span class="text-[11px] text-indigo-200">Klip Disetujui</span>
                <p class="text-base font-bold">18 Video</p>
            </div>
        </div>
    </section>

    <!-- Quick Actions Panel -->
    <section class="bg-white rounded-3xl p-5 border border-slate-200/80">
        <div class="grid grid-cols-4 gap-2">
            <a href="#"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up text-[22px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Klip Saya</span>
            </a>
            <a href="#"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-money-bill-transfer text-[20px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Withdraw</span>
            </a>
            <a href="#"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-fire-flame-curved text-[22px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Campaign</span>
            </a>
            <a href="#"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-clock-rotate-left text-[20px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Riwayat</span>
            </a>
        </div>
    </section>

    <!-- 3. Klip Campaign Terbaru -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Klip Campaign Terbaru</h2>

            <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Lihat
                Semua</a>
        </div>

        <!-- Campaign Item 1 -->
        <a href="#"
            class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
            <!-- Thumbnail -->
            <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=800&auto=format&fit=crop&q=60"
                    alt="Serum Glow & Bright XYZ"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
            </div>
            <div class="p-4 flex flex-col flex-1">
                <h3
                    class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
                    Campaign Promo Serum Glow & Bright XYZ
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                    Buat video TikTok untuk mempromosikan produk Serum Glow & Bright XYZ dengan review jujur dan hasil
                    pemakaian natural.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                        <i class="fa-solid fa-coins text-emerald-500"></i>
                        <span>Rp5.000 / 1K views</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Campaign Item 2 -->
        <a href="#"
            class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
            <!-- Thumbnail -->
            <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=60"
                    alt="Earphone TWS BassPro Max"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
            </div>
            <div class="p-4 flex flex-col flex-1">
                <h3
                    class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
                    Campaign Promo Earphone TWS BassPro Max
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                    Buat video TikTok untuk mempromosikan produk Earphone TWS BassPro Max dengan unboxing dan
                    demonstrasi bass audio.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                        <i class="fa-solid fa-coins text-emerald-500"></i>
                        <span>Rp8.000 / 1K views</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Campaign Item 3 -->
        <a href="#"
            class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
            <!-- Thumbnail -->
            <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&auto=format&fit=crop&q=60"
                    alt="OOTD Kaos Basic"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
            </div>
            <div class="p-4 flex flex-col flex-1">
                <h3
                    class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
                    OOTD Challenge Kaos Basic Polos
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                    Tunjukkan gaya kasualmu menggunakan kaos basic dari brand kami. Padu padankan dengan jeans atau rok
                    favoritmu.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                        <i class="fa-solid fa-coins text-emerald-500"></i>
                        <span>Rp4.500 / 1K views</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Campaign Item 4 -->
        <a href="#"
            class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
            <!-- Thumbnail -->
            <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1599490659213-e2b9527bd087?w=800&auto=format&fit=crop&q=60"
                    alt="Keripik Pedas"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
            </div>
            <div class="p-4 flex flex-col flex-1">
                <h3
                    class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
                    Review Snack Pedas Keripik Setan
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                    Buat reaction video saat makan keripik pedas ini. Ekspresi natural dan tantang temanmu untuk
                    mencoba.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                        <i class="fa-solid fa-coins text-emerald-500"></i>
                        <span>Rp6.000 / 1K views</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Campaign Item 5 -->
        <a href="#"
            class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
            <!-- Thumbnail -->
            <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&auto=format&fit=crop&q=60"
                    alt="Aplikasi Edukasi"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
            </div>
            <div class="p-4 flex flex-col flex-1">
                <h3
                    class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
                    Tutorial Aplikasi Edukasi Anak Pintar
                </h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                    Demokan cara menggunakan fitur belajar membaca di aplikasi Anak Pintar. Target audiens adalah ibu
                    muda.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                        <i class="fa-solid fa-coins text-emerald-500"></i>
                        <span>Rp12.000 / 1K views</span>
                    </div>
                </div>
            </div>
        </a>
    </section>
</div>

@include('app.partials.bottom-nav', [
    'active' => 'beranda'
])

@include('app.partials.vendor-script')
