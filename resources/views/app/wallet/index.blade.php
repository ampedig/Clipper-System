@include('app.partials.head', [
    'title' => 'Riwayat Saldo',
])

<div class="px-5 pt-6 space-y-5 pb-28 min-h-[100dvh]">

    <!-- Navigation Filter Segmented Control (Warna Primary) -->
    <nav class="bg-white border border-slate-200 p-1.5 rounded-2xl flex items-center gap-1">
        <button type="button" onclick="filterTransactions('all', this)"
            class="tx-filter-btn flex-1 py-2 rounded-xl {{ ($currentType ?? 'all') === 'all' ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-500 hover:text-indigo-600 font-semibold hover:bg-slate-50' }} text-xs text-center transition-all active:scale-95 cursor-pointer">
            Semua
        </button>
        <button type="button" onclick="filterTransactions('income', this)"
            class="tx-filter-btn flex-1 py-2 rounded-xl {{ ($currentType ?? 'all') === 'income' ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-500 hover:text-indigo-600 font-semibold hover:bg-slate-50' }} text-xs text-center transition-all active:scale-95 cursor-pointer">
            Pemasukan
        </button>
        <button type="button" onclick="filterTransactions('outcome', this)"
            class="tx-filter-btn flex-1 py-2 rounded-xl {{ ($currentType ?? 'all') === 'outcome' ? 'bg-indigo-600 text-white font-bold shadow-sm' : 'text-slate-500 hover:text-indigo-600 font-semibold hover:bg-slate-50' }} text-xs text-center transition-all active:scale-95 cursor-pointer">
            Penarikan
        </button>
    </nav>

    <!-- Riwayat Transaksi -->
    <section>
        <!-- Container Grup Transaksi -->
        <div class="space-y-6" id="txGroupsContainer">
            @if ($transactions->total() > 0)
                @include('app.wallet.partials.groups', ['groupedTransactions' => $groupedTransactions])
            @endif
        </div>

        <!-- Sentinel untuk Infinite Scroll -->
        <div id="infiniteScrollSentinel"
            class="{{ $transactions->hasMorePages() ? '' : 'hidden' }} py-6 flex justify-center items-center">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                <i class="fa-solid fa-circle-notch fa-spin text-indigo-600 text-sm"></i>
                <span>Memuat transaksi...</span>
            </div>
        </div>

        <!-- Empty Filter State (Ketika tab filter tidak menemukan data) -->
        <div id="filterEmptyState"
            class="hidden bg-white border border-slate-200 rounded-3xl p-8 text-center space-y-3">
            <div
                class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-900 text-sm">Tidak Ada Transaksi</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                    Belum ada riwayat transaksi untuk kategori yang dipilih.
                </p>
            </div>
        </div>

        <!-- Global Empty State (Jika belum ada transaksi sama sekali) -->
        <div id="emptyState"
            class="{{ $transactions->total() === 0 ? '' : 'hidden' }} bg-white border border-slate-200 rounded-3xl p-8 text-center space-y-4">
            <div
                class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-2xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-slate-900 text-base">Belum Ada Riwayat Saldo</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto leading-relaxed">
                    Semua pemasukan dari hasil komisi klip dan penarikan saldo Anda akan tercatat rapi di sini.
                </p>
            </div>
            <div>
                <a href="{{ route('app.campaigns') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs active:scale-95 transition-all hover:bg-indigo-700 shadow-sm">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                    <span>Mulai Ikut Campaign</span>
                </a>
            </div>
        </div>
    </section>
</div>

<!-- Mobile Native Bottom Sheet Modal -->
<div id="txDetailModal"
    class="fixed inset-0 z-[60] flex items-end justify-center invisible pointer-events-none transition-all duration-300"
    aria-modal="true" role="dialog">
    <!-- Backdrop: klik area gelap untuk menutup modal -->
    <div id="txBackdrop" onclick="closeTxDetail()"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-[2px] opacity-0 transition-opacity duration-300 pointer-events-auto cursor-pointer">
    </div>

    <!-- Sheet Container -->
    <div id="txSheet"
        class="relative w-full max-w-[480px] bg-white rounded-t-[28px] border-t border-slate-100 p-6 pb-8 transition-transform duration-300 ease-out transform translate-y-full z-10 select-none touch-pan-y pointer-events-auto shadow-2xl">

        <!-- Drag Handle Indicator (bisa digeser ke bawah) -->
        <div id="txDragHandle"
            class="w-12 h-1.5 bg-slate-300/80 hover:bg-slate-400 rounded-full mx-auto mb-5 cursor-grab active:cursor-grabbing transition-colors">
        </div>

        <!-- Modal Content -->
        <div class="flex flex-col items-center text-center">
            <div id="txIconContainer"
                class="w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-4 bg-emerald-50 text-emerald-500">
                <i id="txIcon" class="fa-solid fa-arrow-down"></i>
            </div>
            <h2 id="txTitle" class="text-lg font-bold text-slate-900 mb-1">Detail Transaksi</h2>
            <p id="txAmount" class="text-3xl font-extrabold text-emerald-600 tracking-tight mb-6">+Rp 0</p>

            <div class="w-full bg-slate-50 rounded-2xl p-4 space-y-3 text-left border border-slate-100 mb-6">
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Status</span>
                    <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check"></i> Berhasil
                    </span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Tipe Transaksi</span>
                    <span id="txType" class="text-xs font-bold text-slate-800">Pemasukan</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Waktu</span>
                    <span id="txDate" class="text-xs font-bold text-slate-800">-</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-semibold text-slate-500">Sisa Saldo</span>
                    <span id="txBalanceAfter" class="text-xs font-bold text-slate-800">-</span>
                </div>
                <div class="flex justify-between items-center border-b border-slate-200/80 pb-3">
                    <span class="text-xs font-semibold text-slate-500">No. Referensi</span>
                    <span id="txRefId" class="text-[11px] font-mono font-bold text-slate-800">-</span>
                </div>
                <div class="flex flex-col gap-1.5 pt-1">
                    <span class="text-xs font-semibold text-slate-500">Catatan</span>
                    <div class="p-3 bg-white rounded-xl border border-slate-200/80 text-left">
                        <p id="txNotes" class="text-xs text-slate-700 leading-relaxed font-medium">-</p>
                    </div>
                </div>
            </div>

            <!-- Action Button -->
            <button type="button" onclick="closeTxDetail()"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

@include('app.partials.bottom-nav', [
    'active' => 'saldo',
])

@include('app.partials.vendor-script')

<!-- Script for Transaction Details Bottom Sheet & Swipe Gesture -->
<script>
    let currentType = '{{ $currentType ?? 'all' }}';
    let nextPage = {{ $transactions->hasMorePages() ? $transactions->currentPage() + 1 : 'null' }};
    let hasMore = {{ $transactions->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;
    let abortController = null;

    const txGroupsContainer = document.getElementById('txGroupsContainer');
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

    // Fungsi penggabungan grup tanggal secara seamless (mencegah duplikasi judul tanggal)
    function appendGroups(html) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        const newGroups = tempDiv.querySelectorAll('.tx-group');

        newGroups.forEach(newGroup => {
            const groupKey = newGroup.dataset.dateGroup;
            const existingGroup = txGroupsContainer.querySelector(`.tx-group[data-date-group="${groupKey}"]`);
            if (existingGroup) {
                const existingItemsContainer = existingGroup.querySelector('.tx-group-items');
                const newItems = newGroup.querySelectorAll('.tx-item');
                newItems.forEach(item => existingItemsContainer.appendChild(item));
            } else {
                txGroupsContainer.appendChild(newGroup);
            }
        });
    }

    // Fungsi load data halaman berikutnya saat scroll ke bawah
    function loadMore() {
        if (isLoading || !hasMore || !nextPage) return;

        isLoading = true;
        sentinel.classList.remove('hidden');

        fetch(`{{ route('app.wallet.index') }}?type=${currentType}&page=${nextPage}`, {
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
                appendGroups(data.html);
            }

            hasMore = data.has_more;
            nextPage = data.next_page;

            if (!hasMore) {
                sentinel.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading wallet transactions:', error);
        })
        .finally(() => {
            isLoading = false;
        });
    }

    // Fungsi filter segmented control (Semua, Pemasukan, Penarikan)
    function filterTransactions(type, btn) {
        if (currentType === type && !isLoading) return;

        if (abortController) {
            abortController.abort();
        }
        abortController = new AbortController();

        const buttons = document.querySelectorAll('.tx-filter-btn');
        buttons.forEach(b => {
            b.className =
                'tx-filter-btn flex-1 py-2 rounded-xl text-slate-500 hover:text-indigo-600 font-semibold text-xs text-center transition-all active:scale-95 hover:bg-slate-50 cursor-pointer';
        });
        btn.className =
            'tx-filter-btn flex-1 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs text-center transition-all active:scale-95 shadow-sm cursor-pointer';

        currentType = type;
        nextPage = 1;
        hasMore = true;
        isLoading = true;

        txGroupsContainer.innerHTML = '';
        filterEmptyState.classList.add('hidden');
        emptyState.classList.add('hidden');
        sentinel.classList.remove('hidden');

        fetch(`{{ route('app.wallet.index') }}?type=${currentType}&page=1`, {
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
                if (currentType === 'all') {
                    emptyState.classList.remove('hidden');
                } else {
                    filterEmptyState.classList.remove('hidden');
                }
                hasMore = false;
                sentinel.classList.add('hidden');
            } else {
                appendGroups(data.html);
                hasMore = data.has_more;
                nextPage = data.next_page;
                if (!hasMore) {
                    sentinel.classList.add('hidden');
                }
            }
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Error filtering transactions:', error);
            }
        })
        .finally(() => {
            isLoading = false;
        });
    }

    function openTxDetail(el) {
        showTxDetail(
            el.dataset.txType,
            el.dataset.txTitle,
            el.dataset.txAmount,
            el.dataset.txDate,
            el.dataset.txRef,
            el.dataset.txNotes,
            el.dataset.txBalanceAfter
        );
    }

    let startY = 0;
    let currentY = 0;
    let isDragging = false;

    const modal = document.getElementById('txDetailModal');
    const backdrop = document.getElementById('txBackdrop');
    const sheet = document.getElementById('txSheet');

    function showTxDetail(type, title, amount, date, refId, notes, balanceAfter) {
        const isIncome = type === 'income';
        const colorClass = isIncome ? 'text-emerald-600' : 'text-rose-600';
        const bgIconClass = isIncome ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500';
        const iconName = isIncome ? 'fa-arrow-down' : 'fa-arrow-up';
        const typeLabel = isIncome ? 'Penambahan Saldo' : 'Pengurangan Saldo';
        const prefix = isIncome ? '+' : '-';
        const defaultNotes = isIncome ?
            'Penambahan saldo telah berhasil diproses.' :
            'Pengurangan saldo telah berhasil diproses.';

        // Populate data
        document.getElementById('txIconContainer').className =
            `w-16 h-16 rounded-full flex items-center justify-center text-3xl mb-4 ${bgIconClass}`;
        document.getElementById('txIcon').className = `fa-solid ${iconName}`;
        document.getElementById('txTitle').textContent = title;
        document.getElementById('txAmount').className = `text-3xl font-extrabold ${colorClass} tracking-tight mb-6`;
        document.getElementById('txAmount').textContent = `${prefix}${amount}`;
        document.getElementById('txType').textContent = typeLabel;
        document.getElementById('txDate').textContent = date;
        document.getElementById('txBalanceAfter').textContent = balanceAfter || '-';
        document.getElementById('txRefId').textContent = refId;
        document.getElementById('txNotes').textContent = notes || defaultNotes;

        // Reset styles
        sheet.style.transform = '';
        sheet.style.transition = 'transform 0.3s cubic-bezier(0.16, 1, 0.3, 1)';
        backdrop.style.opacity = '';

        // Show modal container
        modal.classList.remove('invisible', 'pointer-events-none');

        // Trigger smooth transition
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            sheet.classList.remove('translate-y-full');
            sheet.classList.add('translate-y-0');
        });

        document.body.style.overflow = 'hidden';
    }

    function closeTxDetail() {
        sheet.style.transition = 'transform 0.25s cubic-bezier(0.16, 1, 0.3, 1)';
        sheet.style.transform = 'translateY(100%)';
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');

        setTimeout(() => {
            modal.classList.add('invisible', 'pointer-events-none');
            sheet.classList.remove('translate-y-0');
            sheet.classList.add('translate-y-full');
            sheet.style.transform = '';
            backdrop.style.opacity = '';
            document.body.style.overflow = '';
        }, 260);
    }

    // Gesture: Touch & Mouse Drag to Dismiss
    function onDragStart(clientY) {
        startY = clientY;
        currentY = clientY;
        isDragging = true;
        sheet.style.transition = 'none';
    }

    function onDragMove(clientY) {
        if (!isDragging) return;
        const deltaY = clientY - startY;
        if (deltaY > 0) {
            currentY = clientY;
            sheet.style.transform = `translateY(${deltaY}px)`;
            const progress = Math.min(deltaY / 250, 1);
            backdrop.style.opacity = `${Math.max(0.2, 1 - progress)}`;
        }
    }

    function onDragEnd() {
        if (!isDragging) return;
        isDragging = false;
        const deltaY = currentY - startY;

        if (deltaY > 75) {
            closeTxDetail();
        } else {
            sheet.style.transition = 'transform 0.25s cubic-bezier(0.16, 1, 0.3, 1)';
            backdrop.style.transition = 'opacity 0.25s ease';
            sheet.style.transform = 'translateY(0)';
            backdrop.style.opacity = '1';
        }
    }

    // Touch event listeners
    sheet.addEventListener('touchstart', (e) => {
        onDragStart(e.touches[0].clientY);
    }, {
        passive: true
    });

    sheet.addEventListener('touchmove', (e) => {
        onDragMove(e.touches[0].clientY);
    }, {
        passive: true
    });

    sheet.addEventListener('touchend', () => {
        onDragEnd();
    });

    // Mouse drag support for desktop preview
    let isMouseDragging = false;
    sheet.addEventListener('mousedown', (e) => {
        if (e.target.closest('button') || e.target.closest('a')) return;
        isMouseDragging = true;
        onDragStart(e.clientY);
    });

    window.addEventListener('mousemove', (e) => {
        if (!isMouseDragging) return;
        onDragMove(e.clientY);
    });

    window.addEventListener('mouseup', () => {
        if (!isMouseDragging) return;
        isMouseDragging = false;
        onDragEnd();
    });
</script>
