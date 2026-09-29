@include('app.partials.head', [
    'title' => 'Klip Saya',
])

<div class="px-5 pt-6 space-y-6 pb-28 min-h-[100dvh]">

    @if (!empty($homeAnnouncement))
        <!-- Teks Pengumuman -->
        <section class="bg-white rounded-3xl p-3 border border-slate-200/80">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <i class="fa-solid fa-bell text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-slate-700 leading-snug font-medium">
                        {{ $homeAnnouncement }}
                    </p>
                </div>
            </div>
        </section>
    @endif

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

    <!-- Submission Container -->
    <section class="space-y-4" id="submissionContainer">
        @foreach ($submissions as $sub)
            @include('app.submissions.partials.item', ['sub' => $sub])
        @endforeach
    </section>

    <!-- Empty State Global (Jika belum pernah submit klip sama sekali) -->
    <div id="emptyState" class="{{ $submissions->total() === 0 ? '' : 'hidden' }} bg-white border border-slate-200 rounded-3xl p-8 text-center space-y-4">
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

    <!-- Filter Empty State (Muncul saat hasil filter tidak ada data) -->
    <div id="filterEmptyState" class="hidden flex-col items-center justify-center py-12 text-center text-slate-400 space-y-2">
        <i class="fa-regular fa-folder-open text-3xl text-slate-300"></i>
        <p class="text-xs font-medium">Tidak ada klip pada kategori ini.</p>
    </div>

    <!-- Infinite Scroll Sentinel / Loading Indicator -->
    <div id="infiniteScrollSentinel" class="py-4 text-center {{ $submissions->hasMorePages() ? '' : 'hidden' }}">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 shadow-xs text-xs font-semibold text-slate-500">
            <i class="fa-solid fa-circle-notch fa-spin text-indigo-600"></i>
            <span>Memuat klip lainnya...</span>
        </div>
    </div>

</div>

@include('app.partials.bottom-nav', [
    'active' => 'submission',
])

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#4f46e5',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl font-bold px-5 py-2.5 text-xs'
                    }
                });
            }
        @endif
    });

    let currentStatus = 'all';
    let nextPage = {{ $submissions->hasMorePages() ? $submissions->currentPage() + 1 : 'null' }};
    let hasMore = {{ $submissions->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    let abortController = null;

    const submissionContainer = document.getElementById('submissionContainer');
    const sentinel = document.getElementById('infiniteScrollSentinel');
    const filterEmptyState = document.getElementById('filterEmptyState');
    const emptyState = document.getElementById('emptyState');

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

    // Fungsi fetch data halaman berikutnya (Infinite Scroll)
    function loadMore() {
        if (isLoading || !hasMore || !nextPage) return;

        isLoading = true;
        sentinel.classList.remove('hidden');

        fetch(`{{ route('app.submissions.index') }}?status=${currentStatus}&page=${nextPage}`, {
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
                submissionContainer.insertAdjacentHTML('beforeend', data.html);
            }

            hasMore = data.has_more;
            nextPage = data.next_page;

            if (!hasMore) {
                sentinel.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading submissions:', error);
        })
        .finally(() => {
            isLoading = false;
        });
    }

    // Fungsi Filter Kategori (Semua, Diproses, Disetujui, Ditolak)
    function filterSubmissions(status, btn) {
        if (currentStatus === status && !isLoading) return;

        // Batalkan request sebelumnya jika ada yang sedang berjalan
        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        // Update styling tombol tab
        const buttons = document.querySelectorAll('.filter-btn');
        buttons.forEach(b => {
            b.className = 'filter-btn flex-1 py-2 px-4 rounded-xl text-slate-500 hover:text-indigo-600 font-semibold text-xs text-center transition-all active:scale-95 hover:bg-slate-50 cursor-pointer whitespace-nowrap';
        });
        btn.className = 'filter-btn flex-1 py-2 px-4 rounded-xl bg-indigo-600 text-white font-bold text-xs text-center transition-all active:scale-95 shadow-sm cursor-pointer whitespace-nowrap';

        currentStatus = status;
        nextPage = 1;
        hasMore = true;
        isLoading = true;

        // Reset container dan status tampilan
        submissionContainer.innerHTML = '';
        filterEmptyState.classList.add('hidden');
        filterEmptyState.classList.remove('flex');
        emptyState.classList.add('hidden');
        sentinel.classList.remove('hidden');

        fetch(`{{ route('app.submissions.index') }}?status=${currentStatus}&page=1`, {
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
                if (currentStatus === 'all') {
                    emptyState.classList.remove('hidden');
                } else {
                    filterEmptyState.classList.remove('hidden');
                    filterEmptyState.classList.add('flex');
                }
                hasMore = false;
                sentinel.classList.add('hidden');
            } else {
                submissionContainer.innerHTML = data.html;
                hasMore = data.has_more;
                nextPage = data.next_page;

                if (!hasMore) {
                    sentinel.classList.add('hidden');
                }
            }
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Error filtering submissions:', error);
            }
        })
        .finally(() => {
            isLoading = false;
        });
    }
</script>
@endpush

@include('app.partials.vendor-script')
