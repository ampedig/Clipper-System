@include('app.partials.head', [
    'title' => 'Klip Saya',
])

<div class="px-5 pt-6 space-y-6 pb-28 min-h-[100dvh]">

    <!-- Navigation Filter Segmented Control -->
    <nav class="bg-white border border-slate-200 p-1.5 rounded-2xl flex items-center gap-1 overflow-x-auto scrollbar-hide">
        <button type="button" onclick="filterSubmissions('all', this)" class="filter-btn flex-1 py-2 px-4 rounded-xl bg-indigo-600 text-white font-bold text-xs text-center transition-all active:scale-95 shadow-sm cursor-pointer whitespace-nowrap">
            Semua
        </button>
        <button type="button" onclick="filterSubmissions('diproses', this)" class="filter-btn flex-1 py-2 px-4 rounded-xl text-slate-500 hover:text-indigo-600 font-semibold text-xs text-center transition-all active:scale-95 hover:bg-slate-50 cursor-pointer whitespace-nowrap">
            Diproses
        </button>
        <button type="button" onclick="filterSubmissions('disetujui', this)" class="filter-btn flex-1 py-2 px-4 rounded-xl text-slate-500 hover:text-indigo-600 font-semibold text-xs text-center transition-all active:scale-95 hover:bg-slate-50 cursor-pointer whitespace-nowrap">
            Disetujui
        </button>
        <button type="button" onclick="filterSubmissions('ditolak', this)" class="filter-btn flex-1 py-2 px-4 rounded-xl text-slate-500 hover:text-indigo-600 font-semibold text-xs text-center transition-all active:scale-95 hover:bg-slate-50 cursor-pointer whitespace-nowrap">
            Ditolak
        </button>
    </nav>

    <section class="space-y-4" id="submissionContainer">
        @forelse ($submissions as $sub)
            @php
                // Map status internal database ke kategori filter tampilan
                if ($sub->status === 'pending') {
                    $statusGroup = 'diproses';
                } elseif (in_array($sub->status, ['active', 'approved', 'completed'], true)) {
                    $statusGroup = 'disetujui';
                } else {
                    $statusGroup = 'ditolak';
                }
            @endphp

            <a href="{{ route('app.submissions.show', $sub) }}" data-status="{{ $statusGroup }}" class="submission-item block bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99] flex flex-col">
                <div class="p-4">
                    <h3 class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                        {{ $sub->clipCampaign->title ?? 'Campaign' }}
                    </h3>

                    <div class="mt-3.5 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100/70 text-slate-600 text-xs font-medium border border-slate-200/60 hover:bg-slate-200 transition-colors">
                            <i class="fa-brands fa-tiktok text-slate-900 text-[11px]"></i>
                            <span>{{ \Illuminate\Support\Str::limit(str_replace(['https://www.', 'https://', 'http://www.', 'http://'], '', $sub->submitted_url), 32) }}</span>
                        </span>
                    </div>

                    @if($sub->status === 'rejected' && $sub->rejection_reason)
                        <div class="mt-3 p-3 rounded-xl bg-rose-50 border border-rose-100 text-xs text-rose-600 leading-relaxed">
                            <span class="font-bold block text-[11px] uppercase tracking-wider mb-0.5 text-rose-700 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[10px]"></i> Alasan Penolakan:
                            </span>
                            {{ $sub->rejection_reason }}
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-100"></div>

                <div class="py-2.5 px-4 bg-slate-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($statusGroup === 'disetujui')
                            <div class="flex items-center gap-1.5 text-slate-500">
                                <i class="fa-regular fa-eye text-[11px]"></i>
                                <span class="text-[11px] font-medium">{{ number_format($sub->current_views, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-indigo-600">
                                <i class="fa-solid fa-coins text-[11px]"></i>
                                <span class="text-[11px] font-bold">Rp{{ number_format($sub->total_earned, 0, ',', '.') }}</span>
                            </div>
                        @elseif($statusGroup === 'diproses')
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <i class="fa-regular fa-eye text-[11px]"></i>
                                <span class="text-[11px] font-medium">-</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <i class="fa-solid fa-coins text-[11px]"></i>
                                <span class="text-[11px] font-medium">-</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <i class="fa-regular fa-eye text-[11px]"></i>
                                <span class="text-[11px] font-medium">{{ $sub->current_views > 0 ? number_format($sub->current_views, 0, ',', '.') : '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <i class="fa-solid fa-coins text-[11px]"></i>
                                <span class="text-[11px] font-medium">-</span>
                            </div>
                        @endif
                    </div>

                    <!-- Status Badge -->
                    @if($statusGroup === 'disetujui')
                        <div class="flex items-center gap-1 text-white bg-emerald-500 px-2 py-1 rounded-md">
                            <i class="fa-solid fa-check text-[9px]"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Disetujui</span>
                        </div>
                    @elseif($statusGroup === 'diproses')
                        <div class="flex items-center gap-1 text-white bg-amber-500 px-2 py-1 rounded-md">
                            <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Diproses</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1 text-white bg-rose-500 px-2 py-1 rounded-md">
                            <i class="fa-solid fa-xmark text-[9px]"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Ditolak</span>
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="bg-white border border-slate-200 rounded-3xl p-8 text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="fa-solid fa-clapperboard"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="font-bold text-slate-900 text-base">Belum Ada Klip Diajukan</h3>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                        Kamu belum pernah mengajukan video TikTok. Pilih campaign aktif dan submit videomu untuk mulai menghasilkan komisi!
                    </p>
                </div>
                <div>
                    <a href="{{ route('app.campaigns') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs active:scale-95 transition-all hover:bg-indigo-700">
                        <i class="fa-solid fa-fire-flame-curved"></i>
                        <span>Jelajahi Campaign</span>
                    </a>
                </div>
            </div>
        @endforelse

        <!-- Filter Empty State (Muncul saat hasil filter tidak ada data) -->
        <div id="filterEmptyState" class="hidden flex-col items-center justify-center py-12 text-center text-slate-400 space-y-2">
            <i class="fa-regular fa-folder-open text-3xl text-slate-300"></i>
            <p class="text-xs font-medium">Tidak ada klip pada kategori ini.</p>
        </div>
    </section>

</div>

@include('app.partials.bottom-nav', [
    'active' => 'submission',
])

@push('scripts')
<script>
    function filterSubmissions(status, btn) {
        const buttons = document.querySelectorAll('.filter-btn');
        buttons.forEach(b => {
            b.className = 'filter-btn flex-1 py-2 px-4 rounded-xl text-slate-500 hover:text-indigo-600 font-semibold text-xs text-center transition-all active:scale-95 hover:bg-slate-50 cursor-pointer whitespace-nowrap';
        });
        
        btn.className = 'filter-btn flex-1 py-2 px-4 rounded-xl bg-indigo-600 text-white font-bold text-xs text-center transition-all active:scale-95 shadow-sm cursor-pointer whitespace-nowrap';

        const items = document.querySelectorAll('.submission-item');
        let visibleCount = 0;

        items.forEach(item => {
            if (status === 'all' || item.getAttribute('data-status') === status) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const filterEmptyState = document.getElementById('filterEmptyState');
        if (items.length > 0 && filterEmptyState) {
            if (visibleCount === 0) {
                filterEmptyState.classList.remove('hidden');
                filterEmptyState.classList.add('flex');
            } else {
                filterEmptyState.classList.add('hidden');
                filterEmptyState.classList.remove('flex');
            }
        }
    }
</script>
@endpush

@include('app.partials.vendor-script')
