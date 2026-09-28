@include('app.partials.head', [
    'title' => 'Daftar Campaign',
    'description' => 'Eksplorasi berbagai campaign aktif di AZCLIP dan mulai hasilkan uang dari video TikTok Anda hari ini.',
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
            value="{{ $search ?? '' }}"
            class="w-full pl-10 pr-10 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"
        />
        <button 
            id="clearSearchBtn" 
            onclick="clearSearch()" 
            type="button" 
            class="{{ !empty($search) ? '' : 'hidden' }} absolute right-3 w-6 h-6 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center text-[10px] transition-colors cursor-pointer"
            aria-label="Hapus pencarian">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <!-- 2. Campaign List (Gaya Panel & Background Senada Profile) -->
    <div id="campaignList" class="space-y-4 pt-1">
        @foreach ($campaigns as $campaign)
            @include('app.campaign.partials.item', ['campaign' => $campaign])
        @endforeach
    </div>

    <!-- 3. Empty State (Belum ada campaign aktif sama sekali) -->
    <div id="emptyState" class="{{ $campaigns->total() === 0 && empty($search) ? 'flex' : 'hidden' }} py-12 flex-col items-center justify-center text-center">
        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-3">
            <i class="fa-solid fa-bullhorn"></i>
        </div>
        <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Campaign Aktif</h3>
        <p class="text-xs text-slate-500 max-w-[240px] leading-relaxed">Saat ini belum ada campaign yang tersedia untuk diambil. Silakan cek kembali nanti.</p>
    </div>

    <!-- 4. Empty Search State (Pencarian tidak menemukan hasil) -->
    <div id="emptySearchState" class="{{ $campaigns->total() === 0 && !empty($search) ? 'flex' : 'hidden' }} py-12 flex-col items-center justify-center text-center">
        <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl mb-3">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <h3 class="text-sm font-bold text-slate-800 mb-1">Campaign Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-[240px] leading-relaxed">Coba gunakan kata kunci pencarian lain.</p>
        <button type="button" onclick="clearSearch()" class="mt-4 px-4 py-2 bg-indigo-50 text-indigo-600 font-bold rounded-xl text-xs hover:bg-indigo-100 transition-colors cursor-pointer">
            Reset Pencarian
        </button>
    </div>

    <!-- 5. Infinite Scroll Sentinel / Loading Indicator -->
    <div id="infiniteScrollSentinel" class="py-4 text-center {{ $campaigns->hasMorePages() ? '' : 'hidden' }}">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-xs text-xs font-semibold text-slate-500">
            <i class="fa-solid fa-circle-notch fa-spin text-indigo-600"></i>
            <span>Memuat campaign lainnya...</span>
        </div>
    </div>

</div>

@include('app.partials.bottom-nav', [
    'active' => 'campaign',
])

@push('scripts')
<script>
    let currentQuery = "{{ $search ?? '' }}";
    let nextPage = {{ $campaigns->hasMorePages() ? $campaigns->currentPage() + 1 : 'null' }};
    let hasMore = {{ $campaigns->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    let searchTimeout = null;
    let abortController = null;

    const campaignList = document.getElementById('campaignList');
    const sentinel = document.getElementById('infiniteScrollSentinel');
    const emptyState = document.getElementById('emptyState');
    const emptySearchState = document.getElementById('emptySearchState');
    const clearBtn = document.getElementById('clearSearchBtn');
    const searchInput = document.getElementById('searchInput');

    // Setup IntersectionObserver untuk Infinite Scroll
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting && hasMore && !isLoading) {
            loadMore();
        }
    }, {
        rootMargin: '200px'
    });

    if (sentinel) {
        observer.observe(sentinel);
    }

    // Fungsi fetch halaman berikutnya (Infinite Scroll)
    function loadMore() {
        if (isLoading || !hasMore || !nextPage) return;

        isLoading = true;
        sentinel.classList.remove('hidden');

        const params = new URLSearchParams({
            page: nextPage
        });
        if (currentQuery) {
            params.append('q', currentQuery);
        }

        fetch(`{{ route('app.campaigns') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.html) {
                campaignList.insertAdjacentHTML('beforeend', data.html);
            }

            hasMore = data.has_more;
            nextPage = data.next_page;

            if (!hasMore) {
                sentinel.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading campaigns:', error);
        })
        .finally(() => {
            isLoading = false;
        });
    }

    // Handler input pencarian dengan debounce 400ms
    function handleSearch() {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            executeSearch(query);
        }, 400);
    }

    // Reset tombol search
    function clearSearch() {
        searchInput.value = '';
        clearBtn.classList.add('hidden');
        clearTimeout(searchTimeout);
        executeSearch('');
        searchInput.focus();
    }

    // Eksekusi pencarian langsung ke database via AJAX
    function executeSearch(query) {
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        currentQuery = query;
        nextPage = 1;
        hasMore = true;
        isLoading = true;

        campaignList.innerHTML = '';
        emptyState.classList.add('hidden');
        emptyState.classList.remove('flex');
        emptySearchState.classList.add('hidden');
        emptySearchState.classList.remove('flex');
        sentinel.classList.remove('hidden');

        const params = new URLSearchParams({
            page: 1
        });
        if (query) {
            params.append('q', query);
        }

        fetch(`{{ route('app.campaigns') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: abortController.signal
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.total === 0) {
                if (currentQuery === '') {
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                } else {
                    emptySearchState.classList.remove('hidden');
                    emptySearchState.classList.add('flex');
                }
                hasMore = false;
                sentinel.classList.add('hidden');
            } else {
                campaignList.innerHTML = data.html;
                hasMore = data.has_more;
                nextPage = data.next_page;

                if (!hasMore) {
                    sentinel.classList.add('hidden');
                }
            }
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Error searching campaigns:', error);
            }
        })
        .finally(() => {
            isLoading = false;
        });
    }
</script>
@endpush

@include('app.partials.vendor-script')
