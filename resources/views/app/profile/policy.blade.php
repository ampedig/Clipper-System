@include('app.partials.head', [
    'title' => 'Kebijakan Layanan',
])

<div class="min-h-[100dvh] bg-slate-50 relative pb-32">

    <!-- Top App Bar (Modern Glassmorphic) -->
    <header
        class="flex items-center justify-between px-5 py-2.5 bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/50">
        <div class="flex items-center gap-3">
            <button type="button"
                onclick="window.history.length > 1 ? window.history.back() : window.location.href = '{{ route('app.profile') }}'"
                class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0 cursor-pointer"
                aria-label="Kembali">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Kebijakan Layanan</h1>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="p-4 space-y-4">

        <!-- Hero Card: Document Header -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/80 flex items-center justify-center text-base shrink-0">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-extrabold text-slate-900 tracking-tight leading-snug">Kebijakan Layanan</h2>
                        <p class="text-[10px] font-semibold text-slate-400">AZCLIP Creator Guidelines</p>
                    </div>
                </div>
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold border border-slate-200/60">
                    v1.2 • 2026
                </span>
            </div>

            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                Panduan resmi mengenai hak dan kewajiban kreator, kepatuhan brief campaign, serta ketentuan komisi dan penarikan saldo di platform AZCLIP.
            </p>

            <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    Terakhir diperbarui
                </span>
                <span class="font-bold text-slate-700">19 September 2026</span>
            </div>
        </div>

        <!-- Real-time Search Filter -->
        <div class="relative">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
            <input type="text" id="policy-search" placeholder="Cari topik kebijakan (misal: akun, komisi, sanksi)..."
                class="w-full pl-9 pr-9 py-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:border-indigo-500 transition-colors" />
            <button type="button" id="btn-clear-search"
                class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs cursor-pointer p-1"
                aria-label="Hapus pencarian">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
        </div>

        <!-- Section Bar: Title & Toggle All Button -->
        <div class="flex items-center justify-between px-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider" id="section-title">Daftar Ketentuan (5 Poin)</p>
            <button type="button" id="btn-toggle-all"
                class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors flex items-center gap-1.5 cursor-pointer">
                <i class="fa-solid fa-arrows-up-down text-[10px]"></i>
                <span id="toggle-all-text">Buka Semua</span>
            </button>
        </div>

        <!-- Empty Search State -->
        <div id="search-empty" class="hidden bg-white rounded-2xl border border-slate-200 p-8 text-center space-y-2">
            <div
                class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-lg mx-auto mb-2">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
            <h3 class="text-xs font-bold text-slate-800">Topik Tidak Ditemukan</h3>
            <p class="text-[11px] text-slate-400 font-medium max-w-[240px] mx-auto">Coba cari dengan kata kunci lain seperti akun, brief, komisi, atau sanksi.</p>
        </div>

        <!-- Unified Policy Accordion Panel -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100"
            id="policy-accordion">

            <!-- 1. Ketentuan Akun & Pengguna -->
            <div class="policy-item transition-colors"
                data-keywords="ketentuan akun pengguna profil data pendaftaran sah benar tanggung jawab keamanan pembaruan kebijakan azclip">
                <button type="button"
                    class="policy-toggle w-full px-4 py-3.5 flex items-center justify-between text-left hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                            1
                        </span>
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Ketentuan Akun & Pengguna</h3>
                    </div>
                    <div
                        class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="policy-chevron fa-solid fa-chevron-down text-[10px] transition-transform duration-200 rotate-180"></i>
                    </div>
                </button>
                <div
                    class="policy-content px-4 pb-4 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100/80 bg-slate-50/30 space-y-2.5">
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Pengguna wajib memastikan seluruh data profil yang didaftarkan pada akun adalah benar, sah, dan dapat dipertanggungjawabkan.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Setiap pengguna bertanggung jawab penuh atas keamanan kredensial akun dan seluruh aktivitas yang dilakukan melalui akun masing-masing.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Ketentuan layanan ini dapat diperbarui sewaktu-waktu demi keamanan ekosistem layanan, dengan pemberitahuan melalui aplikasi.</p>
                    </div>
                </div>
            </div>

            <!-- 2. Ketentuan Konten & Campaign -->
            <div class="policy-item transition-colors"
                data-keywords="ketentuan konten campaign brief aturan video klip instruksi norma hukum hak cipta audio lisensi sara pornografi judi">
                <button type="button"
                    class="policy-toggle w-full px-4 py-3.5 flex items-center justify-between text-left hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                            2
                        </span>
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Ketentuan Konten & Campaign</h3>
                    </div>
                    <div
                        class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="policy-chevron fa-solid fa-chevron-down text-[10px] transition-transform duration-200"></i>
                    </div>
                </button>
                <div
                    class="policy-content hidden px-4 pb-4 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100/80 bg-slate-50/30 space-y-2.5">
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Pembuatan dan pengunggahan video klip wajib mengikuti instruksi dan ketentuan yang tercantum pada <span class="font-bold text-slate-800">brief masing-masing campaign</span>.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Konten dilarang keras memuat materi yang melanggar hukum, norma kesusilaan, pornografi, perjudian, ujaran kebencian, atau diskriminasi SARA.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Pengguna bertanggung jawab atas orisinalitas editing dan kepatuhan terhadap lisensi musik atau materi yang digunakan.</p>
                    </div>
                </div>
            </div>

            <!-- 3. Pengajuan (Submission) Video -->
            <div class="policy-item transition-colors"
                data-keywords="pengajuan submission video link url publik aktif tenggat verifikasi audit tayangan">
                <button type="button"
                    class="policy-toggle w-full px-4 py-3.5 flex items-center justify-between text-left hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                            3
                        </span>
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Pengajuan (Submission) Video</h3>
                    </div>
                    <div
                        class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="policy-chevron fa-solid fa-chevron-down text-[10px] transition-transform duration-200"></i>
                    </div>
                </button>
                <div
                    class="policy-content hidden px-4 pb-4 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100/80 bg-slate-50/30 space-y-2.5">
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Submisi tautan (<span class="font-bold text-slate-800">link URL</span>) video hanya dapat dikirimkan selama periode campaign masih berstatus <span class="font-bold text-emerald-600">Aktif</span>.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Tautan video wajib dapat diakses secara <span class="font-bold text-slate-800">publik</span> dan tidak boleh di-private, diarsipkan, atau dihapus selama masa audit dan verifikasi berlangsung.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Klip yang telah diverifikasi sah sebelum alokasi anggaran campaign ditutup tetap berhak atas komisi penayangan yang diperoleh.</p>
                    </div>
                </div>
            </div>

            <!-- 4. Komisi & Penarikan Saldo -->
            <div class="policy-item transition-colors"
                data-keywords="komisi penarikan saldo reward views penayangan dompet rekening transfer pencairan dana e-wallet bank">
                <button type="button"
                    class="policy-toggle w-full px-4 py-3.5 flex items-center justify-between text-left hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-lg bg-indigo-50 text-indigo-600 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                            4
                        </span>
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Komisi & Penarikan Saldo</h3>
                    </div>
                    <div
                        class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="policy-chevron fa-solid fa-chevron-down text-[10px] transition-transform duration-200"></i>
                    </div>
                </button>
                <div
                    class="policy-content hidden px-4 pb-4 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100/80 bg-slate-50/30 space-y-2.5">
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Komisi dihitung berdasarkan metrik penayangan (<span class="font-bold text-slate-800">views</span>) organik yang sah dan tervalidasi oleh sistem audit platform sesuai skema campaign.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Saldo reward yang telah disetujui akan masuk ke Dompet Akun dan dapat dicairkan melalui metode penarikan yang tersedia di platform.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mt-1.5 shrink-0"></div>
                        <p>Pencairan saldo diproses setelah melalui prosedur verifikasi dan audit keamanan sistem.</p>
                    </div>
                </div>
            </div>

            <!-- 5. Larangan & Sanksi -->
            <div class="policy-item transition-colors"
                data-keywords="larangan sanksi kecurangan bot click farm manipulasi views pelanggaran rejected ditolak pembatalan banned pemblokiran blacklist">
                <button type="button"
                    class="policy-toggle w-full px-4 py-3.5 flex items-center justify-between text-left hover:bg-slate-50/70 transition-colors cursor-pointer select-none">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-lg bg-rose-50 text-rose-600 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                            5
                        </span>
                        <h3 class="text-xs font-bold text-slate-900 tracking-tight">Larangan & Sanksi</h3>
                    </div>
                    <div
                        class="w-6 h-6 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 shrink-0">
                        <i class="policy-chevron fa-solid fa-chevron-down text-[10px] transition-transform duration-200"></i>
                    </div>
                </button>
                <div
                    class="policy-content hidden px-4 pb-4 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100/80 bg-slate-50/30 space-y-2.5">
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1.5 shrink-0"></div>
                        <p>Dilarang keras melakukan manipulasi sistem, penggunaan bot, click-farm, fake views, atau tindakan kecurangan penayangan dalam bentuk apa pun.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1.5 shrink-0"></div>
                        <p>Submisi yang tidak sesuai brief atau terindikasi manipulatif berhak <span class="font-bold text-rose-600">Ditolak (Rejected)</span> tanpa kompensasi.</p>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1.5 shrink-0"></div>
                        <p>Pelanggaran berat berakibat pada pembatalan perolehan komisi hingga <span class="font-bold text-rose-600">pemblokiran akun permanen (Banned)</span> dan pencantuman pada daftar hitam sistem.</p>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- Bottom Action Button to CS (Floating) -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-4 pb-6 pt-2 z-40 pb-safe">
        <a href="{{ route('app.help') }}"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-3.5 rounded-2xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
            <i class="fa-solid fa-headset text-sm"></i>
            <span>Punya Pertanyaan? Hubungi CS</span>
        </a>
    </div>

</div>

@include('app.partials.vendor-script')

<script>
    // Inisialisasi elemen accordion dan filter pencarian
    const toggles = document.querySelectorAll('.policy-toggle');
    const btnToggleAll = document.getElementById('btn-toggle-all');
    const toggleAllText = document.getElementById('toggle-all-text');
    const searchInput = document.getElementById('policy-search');
    const btnClearSearch = document.getElementById('btn-clear-search');
    const emptyState = document.getElementById('search-empty');
    const policyAccordion = document.getElementById('policy-accordion');
    const sectionTitle = document.getElementById('section-title');
    const allItems = document.querySelectorAll('.policy-item');

    let allExpanded = false;

    // Toggle per item
    toggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            const parent = toggle.closest('.policy-item');
            const content = parent.querySelector('.policy-content');
            const chevron = toggle.querySelector('.policy-chevron');
            const isHidden = content.classList.contains('hidden');

            if (isHidden) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });
    });

    // Toggle semua item sekaligus
    btnToggleAll.addEventListener('click', () => {
        allExpanded = !allExpanded;

        allItems.forEach(item => {
            if (item.classList.contains('hidden')) return;
            const content = item.querySelector('.policy-content');
            const chevron = item.querySelector('.policy-chevron');

            if (allExpanded) {
                content.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        });

        toggleAllText.textContent = allExpanded ? 'Tutup Semua' : 'Buka Semua';
    });

    // Filter pencarian real-time
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();

        if (query.length > 0) {
            btnClearSearch.classList.remove('hidden');
        } else {
            btnClearSearch.classList.add('hidden');
        }

        let visibleCount = 0;

        allItems.forEach(item => {
            const keywords = (item.getAttribute('data-keywords') || '').toLowerCase();
            const text = item.textContent.toLowerCase();
            const content = item.querySelector('.policy-content');
            const chevron = item.querySelector('.policy-chevron');

            if (!query || keywords.includes(query) || text.includes(query)) {
                item.classList.remove('hidden');
                visibleCount++;
                if (query.length >= 2) {
                    content.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                }
            } else {
                item.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
            policyAccordion.classList.add('hidden');
            sectionTitle.textContent = 'Daftar Ketentuan (0 Poin)';
        } else {
            emptyState.classList.add('hidden');
            policyAccordion.classList.remove('hidden');
            sectionTitle.textContent = `Daftar Ketentuan (${visibleCount} Poin)`;
        }
    });

    // Bersihkan pencarian
    btnClearSearch.addEventListener('click', () => {
        searchInput.value = '';
        btnClearSearch.classList.add('hidden');
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    });
</script>
