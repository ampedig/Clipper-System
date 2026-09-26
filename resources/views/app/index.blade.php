@include('app.partials.head', [
    'title' => 'Beranda',
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
        class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-3xl p-4 text-white shadow-xl shadow-indigo-600/20 relative overflow-hidden">
        <div class="absolute -left-6 -top-6 w-24 h-24 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex justify-between items-center mb-3">
            <div>
                <span class="text-xs font-medium text-indigo-100/80">Saldo Tersedia</span>
                <p class="text-2xl font-extrabold tracking-tight mt-0.5">Rp{{ number_format($user->balance ?? 0, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('app.withdrawals.create') }}"
                class="w-10 h-10 bg-white text-indigo-700 hover:bg-indigo-50 flex items-center justify-center rounded-full transition-colors shadow-sm shrink-0 cursor-pointer active:scale-95"
                aria-label="Tarik Saldo">
                <i class="fa-solid fa-arrow-down-to-bracket"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/15">
            <div>
                <span class="text-[11px] text-indigo-200">Total Views Klip</span>
                <p class="text-base font-bold">{{ ($totalViews ?? 0) >= 1000 ? number_format(floor(($totalViews ?? 0) / 1000), 0, ',', '.') . ' K' : number_format($totalViews ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="text-[11px] text-indigo-200">Klip Disetujui</span>
                <p class="text-base font-bold">{{ number_format($approvedClipsCount ?? 0, 0, ',', '.') }} Video</p>
            </div>
        </div>
    </section>

    <!-- Quick Actions Panel -->
    <section class="bg-white rounded-3xl p-5 border border-slate-200/80">
        <div class="grid grid-cols-4 gap-2">
            <a href="{{ route('app.submissions.index') }}"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform cursor-pointer">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up text-[22px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Klip Saya</span>
            </a>
            <a href="{{ route('app.withdrawals.create') }}"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform cursor-pointer">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-money-bill-transfer text-[20px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Withdraw</span>
            </a>
            <a href="{{ route('app.campaigns') }}"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform cursor-pointer">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-fire-flame-curved text-[22px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Campaign</span>
            </a>
            <a href="{{ route('app.wallet.index') }}"
                class="flex flex-col items-center justify-center gap-2.5 group active:scale-95 transition-transform cursor-pointer">
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-wallet text-[20px]"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-700">Saldo</span>
            </a>
        </div>
    </section>

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
