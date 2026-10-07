@include('app.partials.head', [
    'title' => 'Beranda',
    'description' =>
        'Selamat datang di AZCLIP, platform bagi para kreator untuk meraup komisi mudah hanya dari klip TikTok Anda.',
    'bodyStyle' => 'background-color: #0b0f19; color: #f8fafc;',
    'containerStyle' => 'background-color: #0b0f19; box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);',
    'themeColor' => '#0b0f19',
])

@php
    $userName = $user ? $user->name : 'Clipper';
@endphp

<div class="px-4 pt-4 space-y-5 pb-28">
    <!-- 1. Header Profil -->
    <header class="flex items-center justify-between gap-3">
        <div class="min-w-0 flex-1">
            <h1 class="text-xl font-black tracking-tight leading-none text-white">
                AZ<span style="color: #ff0019;">CLIP</span><span
                    style="color: #94a3b8; font-size: 13px; font-weight: 700;">.COM</span>
            </h1>
            <p class="text-sm font-semibold text-slate-300 truncate mt-1">
                Halo, {{ $userName }} 👋</p>
        </div>

        @if (!empty($linkGrup))
            <a href="{{ $linkGrup }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all duration-200 active:scale-95 shrink-0"
                style="background: #000000; border: 1px solid rgba(255, 0, 37, 0.55);"
                aria-label="Gabung Grup Komunitas">
                <i class="fa-solid fa-users text-xs" style="color: #ff0025;"></i>
                <span class="tracking-tight" style="color: #facc15;">Komunitas</span>
            </a>
        @endif
    </header>

    <!-- 2. Wallet & Earning Banner -->
    <section
        class="bg-gradient-to-br from-indigo-600 via-indigo-600 to-indigo-800 rounded-3xl p-4 text-white border border-white/10 relative overflow-hidden">
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
                        <p class="text-base font-bold mt-0.5">
                            {{ number_format($approvedClipsCount ?? 0, 0, ',', '.') }}
                            <span class="text-xs font-normal text-indigo-200">Video</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Klip Campaign Terbaru -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-white tracking-wide">Klip Campaign Terbaru</h2>

            <a href="{{ route('app.campaigns') }}"
                class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-2 gap-3">
            @forelse ($latestCampaigns as $campaign)
                @include('app.campaign.partials.item-grid', ['campaign' => $campaign])
            @empty
                <div class="rounded-3xl p-6 text-center space-y-2"
                    style="background-color: #111827; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <p class="text-xs text-slate-400 font-medium">Belum ada campaign aktif saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

@include('app.partials.bottom-nav', [
    'active' => 'beranda',
])

@include('app.partials.vendor-script')
