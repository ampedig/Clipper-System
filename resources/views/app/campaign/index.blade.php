@include('app.partials.head', [
    'title' => 'Daftar Campaign',
])

<div class="px-5 pt-6 space-y-4 pb-28 min-h-[100dvh]">

    <!-- 1. Search Bar (Tanpa Header) -->
    <div class="relative flex items-center">
        <i class="fa-solid fa-magnifying-glass absolute left-4 text-slate-400 text-xs pointer-events-none"></i>
        <input 
            type="text" 
            id="searchInput" 
            oninput="handleSearch()" 
            placeholder="Cari campaign atau produk..." 
            class="w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
        />
        <button 
            id="clearSearchBtn" 
            onclick="clearSearch()" 
            type="button" 
            class="hidden absolute right-3 w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] transition-colors cursor-pointer"
            aria-label="Hapus pencarian">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- 2. Campaign List (Gaya Panel & Background Senada Profile) -->
    <div id="campaignList" class="space-y-4 pt-1">
        @forelse ($campaigns as $campaign)
            <a href="{{ route('app.campaigns.show', $campaign) }}" data-title="{{ strtolower($campaign->title) }}" data-desc="{{ strtolower($campaign->description) }}" class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
                <!-- Thumbnail -->
                <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
                    <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" loading="lazy">
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <h3 class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
                        {{ $campaign->title }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                        {{ $campaign->description }}
                    </p>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                            <i class="fa-solid fa-coins text-emerald-500"></i>
                            <span>Rp{{ number_format($campaign->commission_amount, 0, ',', '.') }} / {{ $campaign->view_threshold >= 1000 ? ($campaign->view_threshold / 1000) . 'K' : $campaign->view_threshold }} views</span>
                        </div>
                        @if ($campaign->end_at)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 text-[11px] font-medium border border-slate-200/60">
                                <i class="fa-solid fa-calendar-days text-slate-400"></i>
                                <span>{{ $campaign->end_at->translatedFormat('d M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="border-t border-slate-100"></div>
                <div class="py-3 px-4 bg-slate-50/50 flex items-center justify-between">
                    <span class="text-[11px] font-bold text-indigo-600 group-hover:text-indigo-700 flex items-center gap-1.5">
                        Lihat Detail <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </span>
                    <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded-md">
                        Sisa {{ $campaign->clipper_limit !== null ? $campaign->clipper_limit . ' slot' : 'Tanpa Batas' }}
                    </span>
                </div>
            </a>
        @empty
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-3">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Campaign Aktif</h3>
                <p class="text-xs text-slate-500 max-w-[240px] leading-relaxed">Saat ini belum ada campaign yang tersedia untuk diambil. Silakan cek kembali nanti.</p>
            </div>
        @endforelse
    </div>

    <!-- 3. Empty Search State -->
    <div id="emptySearchState" class="hidden py-12 flex-col items-center justify-center text-center">
        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-3">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <h3 class="text-sm font-bold text-slate-800 mb-1">Campaign Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-[240px] leading-relaxed">Coba gunakan kata kunci pencarian lain.</p>
        <button type="button" onclick="clearSearch()" class="mt-4 px-4 py-2 bg-indigo-50 text-indigo-600 font-bold rounded-xl text-xs hover:bg-indigo-100 transition-colors cursor-pointer">
            Reset Pencarian
        </button>
    </div>

</div>

<!-- Inline Script: Real-time Search Filtering -->
<script>
    function handleSearch() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const clearBtn = document.getElementById('clearSearchBtn');
        
        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        filterList();
    }

    function clearSearch() {
        const searchInput = document.getElementById('searchInput');
        searchInput.value = '';
        document.getElementById('clearSearchBtn').classList.add('hidden');
        filterList();
        searchInput.focus();
    }

    function filterList() {
        const query = document.getElementById('searchInput').value.trim().toLowerCase();
        const cards = document.querySelectorAll('.campaign-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const title = (card.dataset.title || '').toLowerCase();
            const desc = (card.dataset.desc || '').toLowerCase();
            const matchQuery = (query === '' || title.includes(query) || desc.includes(query));

            if (matchQuery) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        const emptyState = document.getElementById('emptySearchState');
        if (cards.length > 0) {
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }
        }
    }
</script>

@include('app.partials.bottom-nav', [
    'active' => 'campaign',
])

@include('app.partials.vendor-script')
