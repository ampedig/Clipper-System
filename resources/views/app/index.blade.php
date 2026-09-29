@include('app.partials.head', [
    'title' => 'Beranda',
    'description' =>
        'Selamat datang di AZCLIP, platform bagi para kreator untuk meraup komisi mudah hanya dari klip TikTok Anda.',
])

@php
    $userName = $user ? $user->name : 'Clipper';
@endphp

<div class="px-4 pt-4 space-y-5 pb-28">
    <!-- 1. Header Profil -->
    <header class="flex items-center justify-between">
        <div class="min-w-0 flex-1">
            <h1
                class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-800 tracking-tight leading-none">
                AZCLIP</h1>
            <p class="text-sm font-semibold text-slate-700 truncate mt-1">
                Halo, {{ $userName }} 👋</p>
        </div>
    </header>

    <!-- 2. Wallet & Earning Banner -->
    <section
        class="bg-gradient-to-br from-indigo-600 via-indigo-600 to-indigo-800 rounded-3xl p-4 text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden">
        <!-- Ambient Glow Corners -->
        <div class="absolute -left-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex justify-between items-center mb-3">
                <div>
                    <span class="text-xs font-medium text-indigo-100/80">Saldo Tersedia</span>
                    <p class="text-2xl font-extrabold tracking-tight mt-0.5">
                        Rp{{ number_format($user->balance ?? 0, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('app.withdrawals.create') }}"
                    class="w-10 h-10 bg-white text-indigo-700 hover:bg-indigo-50 flex items-center justify-center rounded-full transition-colors shadow-sm shrink-0 cursor-pointer active:scale-95"
                    aria-label="Tarik Saldo">
                    <i class="fa-solid fa-arrow-down-to-bracket"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/10">
                <!-- Stat 1: Total Views Klip (dengan background grafik garis tipis) -->
                <div class="relative overflow-hidden pr-2">
                    <svg class="absolute right-0 bottom-0 w-28 h-12 pointer-events-none text-white" viewBox="0 0 120 50"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="viewsChartGrad" x1="0" y1="0" x2="0"
                                y2="1">
                                <stop offset="0%" stop-color="#ffffff" stop-opacity="0.15" />
                                <stop offset="100%" stop-color="#ffffff" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <path d="M0,45 C25,42 45,35 65,22 C85,10 100,18 120,5 L120,50 L0,50 Z"
                            fill="url(#viewsChartGrad)" />
                        <path d="M0,45 C25,42 45,35 65,22 C85,10 100,18 120,5" stroke="#ffffff" stroke-width="1.25"
                            stroke-linecap="round" stroke-opacity="0.3" />
                        <circle cx="120" cy="5" r="2.5" fill="#ffffff" fill-opacity="0.7" />
                        <circle cx="120" cy="5" r="5" fill="#ffffff" fill-opacity="0.15" />
                    </svg>
                    <div class="relative z-10">
                        <span class="text-[11px] font-medium text-indigo-200/90">Total Views Klip</span>
                        <p class="text-base font-bold mt-0.5">
                            {{ ($totalViews ?? 0) >= 1000 ? number_format(floor(($totalViews ?? 0) / 1000), 0, ',', '.') . ' K' : number_format($totalViews ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Stat 2: Klip Disetujui (dengan background grafik batang tipis) -->
                <div class="relative overflow-hidden pr-2">
                    <svg class="absolute right-0 bottom-0 w-24 h-12 pointer-events-none text-white" viewBox="0 0 90 45"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="5" y="28" width="6" height="17" rx="3" fill="#ffffff"
                            fill-opacity="0.1" />
                        <rect x="18" y="22" width="6" height="23" rx="3" fill="#ffffff"
                            fill-opacity="0.14" />
                        <rect x="31" y="25" width="6" height="20" rx="3" fill="#ffffff"
                            fill-opacity="0.12" />
                        <rect x="44" y="15" width="6" height="30" rx="3" fill="#ffffff"
                            fill-opacity="0.2" />
                        <rect x="57" y="10" width="6" height="35" rx="3" fill="#ffffff"
                            fill-opacity="0.25" />
                        <rect x="70" y="4" width="6" height="41" rx="3" fill="#ffffff"
                            fill-opacity="0.35" />
                        <path d="M8,28 C25,23 45,22 73,4" stroke="#ffffff" stroke-width="1" stroke-dasharray="2 2"
                            stroke-linecap="round" stroke-opacity="0.25" />
                    </svg>
                    <div class="relative z-10">
                        <span class="text-[11px] font-medium text-indigo-200/90">Klip Disetujui</span>
                        <p class="text-base font-bold mt-0.5">{{ number_format($approvedClipsCount ?? 0, 0, ',', '.') }}
                            <span class="text-xs font-normal text-indigo-200">Video</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Quick Actions Panel -->
    {{-- <section class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-200/80 shadow-xs">
        <div class="grid grid-cols-4 gap-2 sm:gap-3">
            <!-- 1. Klip Saya -->
            <a href="{{ route('app.submissions.index') }}"
                class="flex flex-col items-center justify-center gap-1.5 group active:scale-95 transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-105"
                    style="background: linear-gradient(135deg, #2563eb, #4f46e5); box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.4);">
                    <i class="fa-solid fa-cloud-arrow-up" style="color: #ffffff; font-size: 21px;"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-800 tracking-tight mt-0.5">Klip Saya</span>
            </a>

            <!-- 2. Withdraw -->
            <a href="{{ route('app.withdrawals.create') }}"
                class="flex flex-col items-center justify-center gap-1.5 group active:scale-95 transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-105"
                    style="background: linear-gradient(135deg, #10b981, #0d9488); box-shadow: 0 8px 16px -4px rgba(16, 185, 129, 0.4);">
                    <i class="fa-solid fa-money-bill-transfer" style="color: #ffffff; font-size: 19px;"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-800 tracking-tight mt-0.5">Withdraw</span>
            </a>

            <!-- 3. Campaign (dengan Badge HOT) -->
            <a href="{{ route('app.campaigns') }}"
                class="flex flex-col items-center justify-center gap-1.5 group active:scale-95 transition-all cursor-pointer">
                <div class="relative">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-105"
                        style="background: linear-gradient(135deg, #f59e0b, #e11d48); box-shadow: 0 8px 16px -4px rgba(225, 29, 72, 0.4);">
                        <i class="fa-solid fa-fire-flame-curved" style="color: #ffffff; font-size: 21px;"></i>
                    </div>
                    <span class="absolute uppercase tracking-wider font-black animate-pulse pointer-events-none"
                        style="top: -5px; right: -7px; font-size: 8px; line-height: 1; padding: 2.5px 5.5px; color: #ffffff; background-color: #e11d48; border-radius: 9999px; box-shadow: 0 2px 6px rgba(225, 29, 72, 0.5); z-index: 10;">HOT</span>
                </div>
                <span class="text-[11px] font-bold text-slate-800 tracking-tight mt-0.5">Campaign</span>
            </a>

            <!-- 4. Saldo -->
            <a href="{{ route('app.wallet.index') }}"
                class="flex flex-col items-center justify-center gap-1.5 group active:scale-95 transition-all cursor-pointer">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-105"
                    style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); box-shadow: 0 8px 16px -4px rgba(124, 58, 237, 0.4);">
                    <i class="fa-solid fa-wallet" style="color: #ffffff; font-size: 19px;"></i>
                </div>
                <span class="text-[11px] font-bold text-slate-800 tracking-tight mt-0.5">Saldo</span>
            </a>
        </div>
    </section> --}}

    <!-- 3. Klip Campaign Terbaru -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Klip Campaign Terbaru</h2>

            <a href="{{ route('app.campaigns') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                Lihat Semua
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($latestCampaigns as $campaign)
                @include('app.campaign.partials.item', ['campaign' => $campaign])
            @empty
                <div class="bg-white border border-slate-200 rounded-3xl p-6 text-center space-y-2">
                    <p class="text-xs text-slate-500 font-medium">Belum ada campaign aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

@include('app.partials.bottom-nav', [
    'active' => 'beranda',
])

@include('app.partials.vendor-script')
