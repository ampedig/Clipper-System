@include('app.partials.head', [
    'title' => 'Riwayat Penarikan',
])

<div class="min-h-[100dvh] bg-slate-50 relative pb-20">

    <!-- Top App Bar (Modern Minimal) -->
    <header
        class="flex items-center justify-between px-5 py-2.5 bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/50">
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.history.back()"
                class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0 cursor-pointer"
                aria-label="Kembali">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Riwayat Penarikan</h1>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="p-4 space-y-2.5">
        @forelse ($withdrawals as $wd)
            @php
                $isPending = in_array($wd->status, ['pending', 'processing']);
                $isCompleted = $wd->status === 'completed';
                $isRejected = $wd->status === 'rejected';

                if ($isPending) {
                    $statusKey = 'diproses';
                    $statusLabel = 'Diproses';
                    $badgeBg = 'bg-amber-50 text-amber-600 border border-amber-200/50';
                    $badgeIcon = 'fa-clock-rotate-left';
                    $defaultNote = 'Sedang diverifikasi oleh sistem antrean pencairan dana.';
                } elseif ($isCompleted) {
                    $statusKey = 'berhasil';
                    $statusLabel = 'Berhasil';
                    $badgeBg = 'bg-emerald-50 text-emerald-600 border border-emerald-200/50';
                    $badgeIcon = 'fa-check';
                    $defaultNote = 'Penarikan saldo telah berhasil ditransfer ke rekening tujuan.';
                } else {
                    $statusKey = 'gagal';
                    $statusLabel = 'Gagal';
                    $badgeBg = 'bg-rose-50 text-rose-600 border border-rose-200/50';
                    $badgeIcon = 'fa-xmark';
                    $defaultNote = $wd->notes ?: 'Permohonan penarikan ditolak atau rekening tujuan tidak valid.';
                }

                $formattedAmount = 'Rp' . number_format($wd->amount, 0, ',', '.');
                $dateStr = $wd->created_at->translatedFormat('d M Y, H:i');
                $noteText = $wd->notes ?: $defaultNote;
            @endphp

            <!-- Card Item Penarikan (Compact) -->
            <div onclick="openDetailModal('{{ $statusKey }}', '{{ addslashes($wd->bank_name) }}', '{{ addslashes($wd->account_name) }}', '{{ $wd->account_number }}', '{{ $formattedAmount }}', '{{ $dateStr }}', '{{ addslashes($noteText) }}')"
                class="bg-white border border-slate-200 rounded-2xl p-3 hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99] flex items-center gap-3 shadow-xs">
                <div
                    class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/60">
                    <i class="fa-solid fa-money-bill-transfer text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h3
                            class="font-bold text-sm text-slate-900 group-hover:text-indigo-600 transition-colors leading-tight truncate">
                            {{ $wd->bank_name }}
                        </h3>
                        <span
                            class="text-sm font-extrabold text-slate-900 tracking-tight shrink-0">{{ $formattedAmount }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2 mt-1">
                        <p class="text-[11px] text-slate-400 font-medium truncate">
                            {{ $dateStr }}
                        </p>
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badgeBg }} shrink-0">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-3xl p-8 text-center space-y-4 my-6">
                <div
                    class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-slate-900 text-base">Belum Ada Riwayat Penarikan</h3>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                        Pengajuan penarikan dana dan status pencairannya akan tercatat rapi di sini.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('app.withdrawals.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs active:scale-95 transition-all hover:bg-indigo-700 shadow-sm cursor-pointer">
                        <i class="fa-solid fa-arrow-down-to-bracket"></i>
                        <span>Tarik Saldo Sekarang</span>
                    </a>
                </div>
            </div>
        @endforelse

        @if ($withdrawals->hasPages())
            <div class="pt-3">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </main>

</div>

<!-- Mobile Native Bottom Sheet Modal (Detail Penarikan) -->
<div id="wdDetailModal"
    class="fixed inset-0 z-[60] flex items-end justify-center invisible pointer-events-none transition-all duration-300"
    aria-modal="true" role="dialog">
    <!-- Backdrop -->
    <div id="wdBackdrop" onclick="closeDetailModal()"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] opacity-0 transition-opacity duration-300 pointer-events-auto cursor-pointer">
    </div>

    <!-- Sheet Container -->
    <div id="wdSheet"
        class="relative w-full max-w-[480px] bg-white rounded-t-[28px] border-t border-slate-100 p-6 pb-8 transition-transform duration-300 ease-out transform translate-y-full z-10 select-none touch-pan-y pointer-events-auto">

        <!-- Drag Handle Indicator -->
        <div id="wdDragHandle"
            class="w-12 h-1.5 bg-slate-200 hover:bg-slate-300 rounded-full mx-auto mb-5 cursor-grab active:cursor-grabbing transition-colors">
        </div>

        <!-- Modal Content -->
        <div class="flex flex-col items-center text-center">
            <h2 class="text-base font-bold text-slate-900 mb-0.5">Detail Penarikan</h2>
            <p id="wdAmount" class="text-2xl font-extrabold text-slate-900 tracking-tight mb-5">Rp0</p>

            <div class="w-full bg-slate-50 rounded-2xl p-4 space-y-3 text-left border border-slate-100 mb-5">
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-2.5">
                    <span class="text-xs font-semibold text-slate-500">Status</span>
                    <span id="wdStatusBadge"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200/50">
                        Diproses
                    </span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-2.5">
                    <span class="text-xs font-semibold text-slate-500">Bank Tujuan</span>
                    <span id="wdBank" class="text-xs font-bold text-slate-800">Bank BCA</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-2.5">
                    <span class="text-xs font-semibold text-slate-500">Nama Penerima</span>
                    <span id="wdAccountName" class="text-xs font-bold text-slate-800">Moh Ma'sum</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-2.5">
                    <span class="text-xs font-semibold text-slate-500">No. Rekening</span>
                    <span id="wdAccountNumber" class="text-xs font-mono font-bold text-slate-800">1234567890</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-2.5">
                    <span class="text-xs font-semibold text-slate-500">Waktu Pengajuan</span>
                    <span id="wdDate" class="text-xs font-bold text-slate-800">-</span>
                </div>
                <div class="flex flex-col gap-1.5 pt-0.5">
                    <span class="text-xs font-semibold text-slate-500">Keterangan</span>
                    <div class="p-3 bg-white rounded-xl border border-slate-200/80 text-left">
                        <p id="wdNotes" class="text-xs text-slate-600 leading-relaxed font-medium">-</p>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <button type="button" onclick="closeDetailModal()"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all cursor-pointer shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

@include('app.partials.vendor-script')

<!-- Interactive Script for Native Bottom Sheet -->
<script>
    const modal = document.getElementById('wdDetailModal');
    const backdrop = document.getElementById('wdBackdrop');
    const sheet = document.getElementById('wdSheet');

    function openDetailModal(status, bank, accountName, accountNumber, amount, date, notes) {
        const badge = document.getElementById('wdStatusBadge');

        document.getElementById('wdBank').textContent = bank;
        document.getElementById('wdAccountName').textContent = accountName;
        document.getElementById('wdAccountNumber').textContent = accountNumber;
        document.getElementById('wdAmount').textContent = amount.replace('-', '');
        document.getElementById('wdDate').textContent = date;
        document.getElementById('wdNotes').textContent = notes;

        if (status === 'diproses') {
            badge.className =
                'inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200/50';
            badge.innerHTML = '<i class="fa-solid fa-clock-rotate-left text-[9px]"></i><span>Diproses</span>';
        } else if (status === 'berhasil') {
            badge.className =
                'inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200/50';
            badge.innerHTML = '<i class="fa-solid fa-check text-[9px]"></i><span>Berhasil</span>';
        } else {
            badge.className =
                'inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-200/50';
            badge.innerHTML = '<i class="fa-solid fa-xmark text-[9px]"></i><span>Gagal</span>';
        }

        modal.classList.remove('invisible', 'pointer-events-none');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            sheet.classList.remove('translate-y-full');
        });
    }

    function closeDetailModal() {
        backdrop.classList.add('opacity-0');
        sheet.classList.add('translate-y-full');
        setTimeout(() => {
            modal.classList.add('invisible', 'pointer-events-none');
        }, 300);
    }
</script>
