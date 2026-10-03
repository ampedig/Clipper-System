@include('app.partials.head', [
    'title' => 'Akun TikTok Saya',
    'description' => 'Kelola dan verifikasi akun TikTok untuk pengajuan klip campaign di AZCLIP',
])

<style>
    /*
     * Semua style baru ditulis sebagai custom class karena input.css adalah build Tailwind statis;
     * utility class yang belum pernah dipakai tidak akan ter-render.
     */
    .tiktok-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
    }

    /* Hero kuota: gradien primary seperti kartu saldo di Home + aksen warna TikTok */
    .tt-hero {
        position: relative;
        overflow: hidden;
        border-radius: 1.5rem;
        padding: 1.25rem;
        color: #ffffff;
        background: linear-gradient(135deg, #ff0019 0%, #e60017 45%, #b80012 100%);
    }

    .tt-hero-glow {
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 9999px;
        filter: blur(50px);
        pointer-events: none;
    }

    .tt-hero-glow.cyan {
        background: #25F4EE;
        top: -70px;
        right: -60px;
        opacity: 0.45;
    }

    .tt-hero-glow.dark {
        background: #0f172a;
        bottom: -80px;
        left: -50px;
        opacity: 0.35;
    }

    .tt-hero-content {
        position: relative;
        z-index: 1;
    }

    .tt-glass-icon {
        width: 40px;
        height: 40px;
        border-radius: 0.875rem;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.28);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .tt-hero-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.8);
    }

    .tt-hero-title {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: -0.01em;
        line-height: 1.2;
    }

    .tt-hero-subtitle {
        font-size: 11px;
        font-weight: 500;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.85);
        margin-top: 0.875rem;
    }

    .tt-hero-count-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 0.75rem;
        margin-top: 1.125rem;
        margin-bottom: 0.625rem;
    }

    .tt-hero-count {
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.02em;
    }

    .tt-hero-count small {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.7);
    }

    .tt-hero-percent {
        font-size: 11px;
        font-weight: 700;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        white-space: nowrap;
    }

    .tt-progress-track {
        height: 8px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.22);
        overflow: hidden;
    }

    .tt-progress-bar {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #ffffff 0%, #d6fffe 100%);
        transition: width 0.6s ease;
    }

    .tt-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .tt-stat {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0.875rem;
        padding: 0.625rem 0.375rem;
        text-align: center;
    }

    .tt-stat-value {
        font-size: 1rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .tt-stat-label {
        font-size: 10px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 2px;
    }

    /* Form tambah akun */
    .tt-section-icon {
        width: 36px;
        height: 36px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        background: #fff1f2;
        color: #ff0019;
        flex-shrink: 0;
    }

    #usernameInput::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .tiktok-input-group {
        display: flex;
        align-items: stretch;
        border: 1px solid #e2e8f0;
        border-radius: 0.875rem;
        background-color: #f8fafc;
        overflow: hidden;
        transition: border-color 0.15s ease, background-color 0.15s ease;
    }

    .tiktok-input-group:focus-within {
        border-color: #ff0019 !important;
        box-shadow: none !important;
        background-color: #ffffff;
    }

    .tiktok-input-prefix {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        flex-shrink: 0;
        color: #94a3b8;
        border-right: 1px solid #e2e8f0;
        background-color: #f8fafc;
        user-select: none;
        transition: color 0.15s ease, border-color 0.15s ease, background-color 0.15s ease;
    }

    .tiktok-input-group:focus-within .tiktok-input-prefix {
        color: #ff0019;
        border-right-color: #fee2e2;
        background-color: #fff1f2;
    }

    .tiktok-input-group.has-error {
        border-color: #f43f5e !important;
        background-color: #fff1f2 !important;
    }

    .tiktok-input-group.has-error .tiktok-input-prefix {
        color: #f43f5e;
        border-right-color: #fecdd3;
    }

    .tiktok-input-field {
        flex: 1;
        min-width: 0;
        padding: 1rem 1.25rem;
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        box-shadow: none;
    }

    .tt-btn-primary,
    .tt-btn-verify {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
        border-radius: 0.875rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #ffffff;
        border: none;
        cursor: pointer;
        transition: transform 0.15s ease, filter 0.15s ease;
    }

    .tt-btn-primary {
        background: linear-gradient(135deg, #ff0019 0%, #d90015 100%);
    }

    .tt-btn-verify {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .tt-btn-primary:hover,
    .tt-btn-verify:hover {
        filter: brightness(1.05);
    }

    .tt-btn-primary:active,
    .tt-btn-verify:active {
        transform: scale(0.98);
    }

    /* Judul section daftar akun */
    .tt-count-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        background: #fff1f2;
        color: #ff0019;
        font-size: 11px;
        font-weight: 700;
    }

    /* Kartu akun */
    .tt-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .tt-avatar {
        width: 46px;
        height: 46px;
        border-radius: 9999px;
        object-fit: cover;
        border: 2px solid #ffffff;
    }

    .tt-avatar.verified {
        box-shadow: 0 0 0 2px #10b981;
    }

    .tt-avatar.pending {
        box-shadow: 0 0 0 2px #f59e0b;
    }

    .tt-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0f172a;
        color: #ffffff;
        font-size: 1.1rem;
    }

    .tt-status-dot {
        position: absolute;
        right: -2px;
        bottom: -2px;
        width: 17px;
        height: 17px;
        border-radius: 9999px;
        border: 2px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        color: #ffffff;
    }

    .tt-status-dot.verified {
        background: #10b981;
    }

    .tt-status-dot.pending {
        background: #f59e0b;
    }

    .tt-handle {
        display: inline-block;
        max-width: 100%;
        padding: 1px 8px;
        margin-top: 4px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tt-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 10px;
        font-weight: 700;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .tt-badge.verified {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .tt-badge.pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    /* Stepper verifikasi bio */
    .tt-steps {
        background: #fffdf5;
        border: 1px solid #fef3c7;
        border-radius: 1rem;
        padding: 1rem;
    }

    .tt-step {
        position: relative;
        display: flex;
        gap: 0.75rem;
        padding-bottom: 1.125rem;
    }

    .tt-step:last-child {
        padding-bottom: 0;
    }

    .tt-step:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 28px;
        bottom: 4px;
        width: 2px;
        border-radius: 2px;
        background: #fde68a;
    }

    .tt-step-num {
        position: relative;
        z-index: 1;
        width: 24px;
        height: 24px;
        border-radius: 9999px;
        background: #f59e0b;
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .tt-step-body {
        flex: 1;
        min-width: 0;
    }

    .tt-step-title {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        line-height: 24px;
    }

    .tt-step-desc {
        font-size: 11px;
        font-weight: 500;
        line-height: 1.5;
        color: #64748b;
    }

    .tt-step-action {
        margin-top: 0.625rem;
    }

    .tt-inline-link {
        color: #ff0019;
        font-weight: 700;
    }

    /* Tinggi box kode & tombol salin dikunci 44px sesuai kesepakatan sebelumnya */
    .tiktok-code-box {
        background-color: #ffffff;
        border: 1px solid #fde68a;
        border-radius: 0.75rem;
        height: 44px;
    }

    .tiktok-copy-btn {
        height: 44px;
        border: 1px solid #e2e8f0;
    }

    .custom-code-box {
        letter-spacing: 0.15em;
    }

    /* Footer kartu */
    .tt-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f1f5f9;
    }

    .tt-meta {
        font-size: 10px;
        font-weight: 500;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 4px;
        min-width: 0;
    }

    .tt-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .tt-pill-btn:hover {
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .tt-pill-btn.danger:hover {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #e11d48;
    }

    /* Empty state dengan efek logo TikTok (offset cyan & magenta) */
    .tt-empty {
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 1.25rem;
        padding: 2rem 1.5rem;
        text-align: center;
    }

    .tt-empty-icon {
        position: relative;
        width: 60px;
        height: 60px;
        margin: 0 auto 1.125rem;
    }

    .tt-empty-icon::before,
    .tt-empty-icon::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1.125rem;
        opacity: 0.7;
    }

    .tt-empty-icon::before {
        background: #25F4EE;
        transform: translate(-4px, -4px);
    }

    .tt-empty-icon::after {
        background: #FE2C55;
        transform: translate(4px, 4px);
    }

    .tt-empty-icon-inner {
        position: relative;
        z-index: 1;
        width: 60px;
        height: 60px;
        border-radius: 1.125rem;
        background: #0f172a;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    /* Custom SweetAlert width alignment so content width matches actions buttons */
    .swal2-popup.custom-swal-popup {
        padding: 1.5rem !important;
        border-radius: 1.75rem !important;
    }

    .swal2-popup.custom-swal-popup .swal2-icon,
    .swal2-popup.custom-swal-popup .swal2-title {
        display: none !important;
        margin: 0 !important;
        padding: 0 !important;
        height: 0 !important;
    }

    .swal2-popup.custom-swal-popup .swal2-html-container {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    /* Remove outline and box-shadow completely from SweetAlert buttons */
    .swal2-popup.custom-swal-popup button,
    .swal2-popup.custom-swal-popup .swal2-styled,
    .swal2-popup.custom-swal-popup .swal2-confirm,
    .swal2-popup.custom-swal-popup .swal2-cancel {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
        -webkit-tap-highlight-color: transparent !important;
    }

    .swal2-popup.custom-swal-popup button:focus,
    .swal2-popup.custom-swal-popup button:focus-visible,
    .swal2-popup.custom-swal-popup .swal2-styled:focus,
    .swal2-popup.custom-swal-popup .swal2-styled:focus-visible,
    .swal2-popup.custom-swal-popup .swal2-confirm:focus,
    .swal2-popup.custom-swal-popup .swal2-confirm:focus-visible,
    .swal2-popup.custom-swal-popup .swal2-cancel:focus,
    .swal2-popup.custom-swal-popup .swal2-cancel:focus-visible {
        outline: none !important;
        box-shadow: none !important;
    }
</style>

@php
    $pendingAccounts = $totalAccounts - $verifiedAccounts;
    $remainingSlots = max(0, $maxAccounts - $totalAccounts);
    $percentage = $maxAccounts > 0 ? min(100, round(($totalAccounts / $maxAccounts) * 100)) : 0;
@endphp

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
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Akun TikTok Saya</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 space-y-4">

        <!-- Hero Kuota & Info Verifikasi -->
        <section class="tt-hero">
            <div class="tt-hero-glow cyan"></div>
            <div class="tt-hero-glow dark"></div>

            <div class="tt-hero-content">
                <div class="flex items-center gap-3">
                    <div class="tt-glass-icon">
                        <i class="fa-brands fa-tiktok"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="tt-hero-label">Verifikasi Kepemilikan</p>
                        <h2 class="tt-hero-title">Kuota Akun TikTok</h2>
                    </div>
                </div>

                <p class="tt-hero-subtitle">
                    Daftarkan &amp; verifikasi akun TikTok Anda sebelum mengajukan link video tugas campaign.
                </p>

                <div class="tt-hero-count-row">
                    <div class="tt-hero-count">
                        {{ $totalAccounts }} <small>/ {{ $maxAccounts }}</small>
                    </div>
                    <span class="tt-hero-percent">{{ $percentage }}% terpakai</span>
                </div>

                <div class="tt-progress-track">
                    <div class="tt-progress-bar" style="width: {{ $percentage }}%"></div>
                </div>

                <div class="tt-stat-grid">
                    <div class="tt-stat">
                        <div class="tt-stat-value">{{ $verifiedAccounts }}</div>
                        <div class="tt-stat-label">Terverifikasi</div>
                    </div>
                    <div class="tt-stat">
                        <div class="tt-stat-value">{{ $pendingAccounts }}</div>
                        <div class="tt-stat-label">Menunggu</div>
                    </div>
                    <div class="tt-stat">
                        @if ($canAddMore)
                            <div class="tt-stat-value">{{ $remainingSlots }}</div>
                            <div class="tt-stat-label">Sisa Slot</div>
                        @else
                            <div class="tt-stat-value"><i class="fa-solid fa-lock text-sm"></i></div>
                            <div class="tt-stat-label">Kuota Penuh</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Form Tambah Akun Baru (Jika Kuota Tersedia) -->
        @if ($canAddMore)
            <section class="tiktok-card p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="tt-section-icon">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900">Tambah Akun TikTok</h3>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Kode verifikasi bio dibuat otomatis</p>
                    </div>
                </div>

                <form action="{{ route('app.tiktok.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label for="usernameInput"
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Username TikTok
                        </label>

                        <div class="tiktok-input-group @error('username') has-error @enderror">
                            <div class="tiktok-input-prefix" aria-hidden="true">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                                </svg>
                            </div>
                            <input type="text" id="usernameInput" name="username" value="{{ old('username') }}"
                                placeholder="contoh: azclip" required autocomplete="off" spellcheck="false"
                                pattern="[^@]+" title="Username TikTok tidak boleh menyertakan simbol @"
                                oninput="this.value = this.value.replace(/@/g, '')" class="tiktok-input-field">
                        </div>

                        @error('username')
                            <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="tt-btn-primary">
                        <span>Lanjutkan &amp; Dapatkan Kode Bio</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </section>
        @endif

        <!-- List Akun TikTok Terdaftar -->
        <section class="space-y-3">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-sm font-bold text-slate-900">Daftar Akun</h3>
                <span class="tt-count-pill">{{ $totalAccounts }} akun</span>
            </div>

            @forelse ($accounts as $account)
                @php
                    $statusClass = $account->is_verified ? 'verified' : 'pending';
                @endphp
                <article class="tiktok-card p-4 space-y-4">

                    <!-- Header Kartu Akun -->
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="tt-avatar-wrap">
                                @if ($account->avatar_url)
                                    <img src="{{ $account->avatar_url }}" alt="{{ $account->username }}"
                                        class="tt-avatar {{ $statusClass }}">
                                @else
                                    <div class="tt-avatar tt-avatar-fallback {{ $statusClass }}">
                                        <i class="fa-brands fa-tiktok"></i>
                                    </div>
                                @endif
                                <span class="tt-status-dot {{ $statusClass }}">
                                    <i class="fa-solid {{ $account->is_verified ? 'fa-check' : 'fa-clock' }}"></i>
                                </span>
                            </div>

                            <div class="min-w-0 flex flex-col">
                                <h4 class="text-sm font-bold text-slate-900 truncate">
                                    {{ $account->nickname ?? $account->username }}
                                </h4>
                                <span class="tt-handle">&#64;{{ $account->username }}</span>
                            </div>
                        </div>

                        @if ($account->is_verified)
                            <span class="tt-badge verified">
                                <i class="fa-solid fa-circle-check"></i> Terverifikasi
                            </span>
                        @else
                            <span class="tt-badge pending">
                                <i class="fa-solid fa-hourglass-half"></i> Menunggu
                            </span>
                        @endif
                    </div>

                    <!-- Stepper Verifikasi (khusus akun yang belum terverifikasi) -->
                    @if (!$account->is_verified)
                        <div class="tt-steps">
                            <div class="tt-step">
                                <div class="tt-step-num">1</div>
                                <div class="tt-step-body">
                                    <p class="tt-step-title">Salin kode verifikasi</p>
                                    <div class="tt-step-action flex items-center gap-2">
                                        <div class="tiktok-code-box flex-1 flex items-center justify-center">
                                            <span
                                                class="text-xl font-extrabold text-slate-900 font-mono custom-code-box select-all">
                                                {{ $account->verification_code }}
                                            </span>
                                        </div>
                                        <button type="button"
                                            onclick="copyToClipboard('{{ $account->verification_code }}', this)"
                                            class="tiktok-copy-btn px-5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-sm font-bold flex items-center justify-center gap-1.5 transition-all active:scale-95 shrink-0 cursor-pointer">
                                            <i class="fa-regular fa-copy text-sm"></i>
                                            <span>Salin</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="tt-step">
                                <div class="tt-step-num">2</div>
                                <div class="tt-step-body">
                                    <p class="tt-step-title">Tempel di bio TikTok</p>
                                    <p class="tt-step-desc">
                                        Buka profil
                                        <a href="https://www.tiktok.com/{{ '@' . $account->username }}" target="_blank"
                                            rel="noopener noreferrer"
                                            class="tt-inline-link">&#64;{{ $account->username }}</a>,
                                        tempel kode di bio, lalu simpan.
                                    </p>
                                </div>
                            </div>

                            <div class="tt-step">
                                <div class="tt-step-num">3</div>
                                <div class="tt-step-body">
                                    <p class="tt-step-title">Cek verifikasi</p>
                                    <div class="tt-step-action">
                                        <button type="button"
                                            onclick="verifyAccount('{{ route('app.tiktok.verify', $account) }}', '{{ $account->username }}', this)"
                                            class="tt-btn-verify">
                                            <i class="fa-solid fa-circle-check text-xs"></i>
                                            <span>Sudah Pasang Kode, Cek Sekarang</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Footer Kartu: Info Tanggal, Buka Profil & Hapus -->
                    <div class="tt-card-footer">
                        <span class="tt-meta truncate">
                            @if ($account->is_verified && $account->verified_at)
                                <i class="fa-solid fa-shield-halved"></i>
                                Diverifikasi {{ $account->verified_at->translatedFormat('d M Y') }}
                            @else
                                <i class="fa-regular fa-calendar"></i>
                                Ditambahkan {{ $account->created_at?->translatedFormat('d M Y') }}
                            @endif
                        </span>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="https://www.tiktok.com/{{ '@' . $account->username }}" target="_blank"
                                rel="noopener noreferrer" class="tt-pill-btn">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                <span>Profil</span>
                            </a>
                            <button type="button"
                                onclick="deleteAccount('{{ route('app.tiktok.destroy', $account) }}', '{{ $account->username }}')"
                                class="tt-pill-btn danger">
                                <i class="fa-regular fa-trash-can text-[10px]"></i>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>

                </article>
            @empty
                <div class="tt-empty">
                    <div class="tt-empty-icon">
                        <div class="tt-empty-icon-inner">
                            <i class="fa-brands fa-tiktok"></i>
                        </div>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Belum Ada Akun TikTok</h4>
                    <p class="text-xs text-slate-400 font-medium max-w-[260px] mx-auto mt-1 leading-relaxed">
                        Daftarkan akun TikTok Anda untuk diverifikasi sebelum mengajukan video clip campaign.
                    </p>
                </div>
            @endforelse
        </section>

    </main>
</div>

<!-- Hidden Delete Form -->
<form id="deleteAccountForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
    <script>
        // Copy code to clipboard with visual feedback
        function copyToClipboard(text, btnElement) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopiedFeedback(btnElement);
                }).catch(() => {
                    fallbackCopyText(text, btnElement);
                });
            } else {
                fallbackCopyText(text, btnElement);
            }
        }

        function fallbackCopyText(text, btnElement) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showCopiedFeedback(btnElement);
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
        }

        function showCopiedFeedback(btnElement) {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML =
                '<i class="fa-solid fa-check text-xs text-emerald-600"></i> <span class="text-emerald-600 font-bold">Tersalin!</span>';
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
            }, 2000);
        }

        // Trigger Verification via AJAX with modern SweetAlert modal
        function verifyAccount(verifyUrl, username, btnElement) {
            if (typeof Swal === 'undefined') return;

            const originalHtml = btnElement.innerHTML;
            btnElement.disabled = true;
            btnElement.innerHTML =
                '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Mengecek Bio TikTok...</span>';
            btnElement.classList.add('opacity-80', 'cursor-not-allowed');

            fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({
                    status,
                    body
                }) => {
                    btnElement.disabled = false;
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('opacity-80', 'cursor-not-allowed');

                    if (status === 200 && body.success) {
                        Swal.fire({
                            html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #a7f3d0;">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-1.5 tracking-tight">Berhasil Diverifikasi!</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                ${body.message || 'Akun TikTok Anda kini telah terverifikasi dan siap digunakan.'}
                            </p>
                        </div>
                    `,
                            confirmButtonText: 'Selesai',
                            buttonsStyling: false,
                            backdrop: 'rgba(15, 23, 42, 0.65)',
                            customClass: {
                                popup: 'custom-swal-popup !rounded-[2.25rem]',
                                htmlContainer: '!m-0 !p-0 !w-full',
                                actions: 'w-full mt-6 px-0',
                                confirmButton: 'w-full py-4 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fde68a;">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-1.5 tracking-tight">Verifikasi Belum Berhasil</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                ${body.message || 'Kode belum ditemukan pada bio profil TikTok Anda. Pastikan sudah disimpan dan coba lagi.'}
                            </p>
                        </div>
                    `,
                            confirmButtonText: 'Coba Lagi Nanti',
                            buttonsStyling: false,
                            backdrop: 'rgba(15, 23, 42, 0.65)',
                            customClass: {
                                popup: 'custom-swal-popup !rounded-[2.25rem]',
                                htmlContainer: '!m-0 !p-0 !w-full',
                                actions: 'w-full mt-6 px-0',
                                confirmButton: 'w-full py-4 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                            }
                        });
                    }
                })
                .catch(err => {
                    btnElement.disabled = false;
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('opacity-80', 'cursor-not-allowed');

                    Swal.fire({
                        html: `
                    <div class="flex flex-col items-center text-center p-1">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fecdd3;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-1.5 tracking-tight">Gangguan Koneksi</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                            Terjadi kesalahan saat menghubungi server. Silakan coba beberapa saat lagi.
                        </p>
                    </div>
                `,
                        confirmButtonText: 'Tutup',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full mt-6 px-0',
                            confirmButton: 'w-full py-4 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                        }
                    });
                });
        }

        // Delete Account Confirmation Modal (Modern SweetAlert)
        function deleteAccount(deleteUrl, username) {
            if (typeof Swal === 'undefined') {
                if (confirm(`Yakin ingin menghapus akun @${username}?`)) {
                    const form = document.getElementById('deleteAccountForm');
                    form.action = deleteUrl;
                    form.submit();
                }
                return;
            }

            Swal.fire({
                html: `
                <div class="flex flex-col items-center text-center p-1">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fecdd3;">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-1.5 tracking-tight">Hapus Akun TikTok?</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                        Apakah Anda yakin ingin menghapus akun <span class="font-bold text-slate-800">@${username}</span> dari daftar akun Anda?
                    </p>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                backdrop: 'rgba(15, 23, 42, 0.65)',
                customClass: {
                    popup: 'custom-swal-popup !rounded-[2.25rem]',
                    htmlContainer: '!m-0 !p-0 !w-full',
                    actions: 'w-full flex flex-row flex-nowrap gap-3 mt-6 px-0',
                    confirmButton: 'flex-1 py-4 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer',
                    cancelButton: 'flex-1 py-4 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteAccountForm');
                    form.action = deleteUrl;
                    form.submit();
                }
            });
        }

        // Flash Messages handling (Modern SweetAlert)
        @if (session('success'))
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #a7f3d0;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-1.5 tracking-tight">Berhasil!</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                {{ session('success') }}
                            </p>
                        </div>
                    `,
                        timer: 2500,
                        showConfirmButton: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full'
                        }
                    });
                }
            });
        @endif

        @if (session('error'))
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fecdd3;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-1.5 tracking-tight">Perhatian</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                {{ session('error') }}
                            </p>
                        </div>
                    `,
                        confirmButtonText: 'Mengerti',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full mt-6 px-0',
                            confirmButton: 'w-full py-4 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                        }
                    });
                }
            });
        @endif
    </script>
@endpush

@include('app.partials.vendor-script')
