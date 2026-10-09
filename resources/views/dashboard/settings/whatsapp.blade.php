@extends('dashboard.layouts.app')

@section('title', 'Pengaturan WhatsApp Gateway - AMPEDIG Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    WhatsApp Gateway
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Pengaturan',
                    'crumb2_url' => route('admin.settings.index'),
                    'crumb3_label' => 'WhatsApp',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Card 1: Status Koneksi Device AMBLAST -->
            <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-6 transition-colors duration-300">
                
                <!-- Card Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100 dark:border-[#2e2e2e]">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Status Koneksi Perangkat</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantauan status koneksi socket WhatsApp, nomor aktif, sisa kuota, dan masa aktif.</p>
                        </div>
                    </div>

                    <!-- Tombol Refresh Status -->
                    <button type="button" id="btnRefreshStatus"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 dark:border-[#2e2e2e] bg-slate-50 dark:bg-[#1a1a1a] hover:bg-slate-100 dark:hover:bg-[#262626] text-xs font-semibold text-slate-700 dark:text-slate-200 transition-colors self-start sm:self-auto disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-arrows-rotate text-xs" id="iconRefresh"></i>
                        <span>Segarkan Status</span>
                    </button>
                </div>

                <!-- Loading State (Skeleton) -->
                <div id="statusLoadingState" class="py-8 flex flex-col items-center justify-center gap-3">
                    <div class="w-8 h-8 border-2 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin"></div>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Memeriksa koneksi perangkat ke AMBLAST Gateway...</span>
                </div>

                <!-- Status View Container (Akan diisi via JavaScript) -->
                <div id="statusResultContainer" class="hidden space-y-5">
                    
                    <!-- Banner Status Utama -->
                    <div id="statusBanner" class="p-4 sm:p-5 rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300">
                        <div class="flex items-center gap-3.5">
                            <div id="statusBadgeIcon" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-base shadow-xs"></div>
                            <div>
                                <p id="statusBannerTitle" class="font-semibold text-sm tracking-tight"></p>
                                <p id="statusBannerSubtitle" class="text-xs mt-0.5 opacity-90"></p>
                            </div>
                        </div>
                        <div id="statusBadgeTag" class="self-start sm:self-auto shrink-0"></div>
                    </div>

                    <!-- Grid Detail Info Perangkat -->
                    <div id="deviceDetailsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Nama Perangkat -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-[#2e2e2e] flex items-center justify-between gap-4 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                            <div class="space-y-1 min-w-0">
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">
                                    Perangkat
                                </span>
                                <p id="infoDeviceName" class="text-sm sm:text-base font-bold text-slate-900 dark:text-slate-100 truncate">
                                    -
                                </p>
                            </div>
                            <i class="fa-solid fa-mobile-screen text-2xl sm:text-3xl text-indigo-500 shrink-0"></i>
                        </div>

                        <!-- Nomor WhatsApp -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-[#2e2e2e] flex items-center justify-between gap-4 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                            <div class="space-y-1 min-w-0">
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">
                                    Nomor WhatsApp
                                </span>
                                <p id="infoPhoneNumber" class="text-sm sm:text-base font-bold text-slate-900 dark:text-slate-100 font-mono truncate">
                                    -
                                </p>
                            </div>
                            <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl text-emerald-500 shrink-0"></i>
                        </div>

                        <!-- Kuota Pesan -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-[#2e2e2e] flex items-center justify-between gap-4 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                            <div class="space-y-1 min-w-0">
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">
                                    Sisa Kuota
                                </span>
                                <p id="infoQuota" class="text-sm sm:text-base font-bold text-slate-900 dark:text-slate-100 truncate">
                                    -
                                </p>
                            </div>
                            <i class="fa-solid fa-paper-plane text-2xl sm:text-3xl text-amber-500 shrink-0"></i>
                        </div>

                        <!-- Masa Aktif Paket -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200/80 dark:border-[#2e2e2e] flex items-center justify-between gap-4 hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                            <div class="space-y-1 min-w-0">
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 block">
                                    Masa Aktif
                                </span>
                                <p id="infoExpiredAt" class="text-sm sm:text-base font-bold text-slate-900 dark:text-slate-100 truncate">
                                    -
                                </p>
                            </div>
                            <i class="fa-solid fa-calendar-days text-2xl sm:text-3xl text-sky-500 shrink-0"></i>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Card 2: Form Konfigurasi API Key -->
            <form id="whatsappForm" action="{{ route('admin.settings.whatsapp.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-8 transition-colors duration-300">

                    <!-- Card Header -->
                    <div class="pb-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Kredensial API Key</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Konfigurasikan token API Key untuk menghubungkan aplikasi AZCLIP dengan AMBLAST Gateway.</p>
                            </div>
                        </div>

                        <!-- Status Badge DB -->
                        @if (!empty($apiKey))
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 self-start sm:self-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                API Key Tersimpan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 self-start sm:self-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Belum Diisi
                            </span>
                        @endif
                    </div>

                    <!-- Input API Key WhatsApp -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="apikeyWhatsapp" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                API Key WhatsApp Gateway
                            </label>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">Karakter rahasia (Token / Key)</span>
                        </div>

                        <div class="flex relative">
                            <!-- Prefix Icon -->
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 dark:border-[#2e2e2e] bg-slate-100/70 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 text-sm select-none">
                                <i class="fa-solid fa-key"></i>
                            </span>

                            <!-- Password Input Field -->
                            <input type="password" id="apikeyWhatsapp" name="apikey_whatsapp"
                                value="{{ old('apikey_whatsapp', $apiKey ?? '') }}"
                                placeholder="Tempel API Key WhatsApp Gateway Anda di sini..." autocomplete="off"
                                spellcheck="false"
                                class="flex-1 min-w-0 block w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-none text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all font-mono focus:z-10 @error('apikey_whatsapp') border-red-500 @enderror">

                            <!-- Toggle Visibility Button -->
                            <button type="button" id="toggleApiKey"
                                class="inline-flex items-center px-4 rounded-r-xl border border-l-0 border-slate-200 dark:border-[#2e2e2e] bg-slate-100/70 dark:bg-[#1a1a1a] text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors focus:outline-none"
                                title="Tampilkan / Sembunyikan API Key">
                                <i class="fa-solid fa-eye text-sm" id="eyeIcon"></i>
                            </button>
                        </div>

                        @error('apikey_whatsapp')
                            <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                            Kosongkan kolom ini jika Anda ingin menonaktifkan sementara integrasi WhatsApp Gateway.
                        </p>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex items-center justify-end pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                        <button type="button" id="btnSave" disabled
                            class="btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center gap-2 opacity-50 cursor-not-allowed transition-all">
                            <i class="fa-solid fa-save text-xs"></i>
                            <span>Simpan API Key</span>
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- Page Interactive Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('whatsappForm');
            const apiKeyInput = document.getElementById('apikeyWhatsapp');
            const toggleApiKeyBtn = document.getElementById('toggleApiKey');
            const eyeIcon = document.getElementById('eyeIcon');
            const btnSave = document.getElementById('btnSave');

            // Device Status Elements
            const btnRefreshStatus = document.getElementById('btnRefreshStatus');
            const iconRefresh = document.getElementById('iconRefresh');
            const statusLoadingState = document.getElementById('statusLoadingState');
            const statusResultContainer = document.getElementById('statusResultContainer');
            const statusBanner = document.getElementById('statusBanner');
            const statusBadgeIcon = document.getElementById('statusBadgeIcon');
            const statusBannerTitle = document.getElementById('statusBannerTitle');
            const statusBannerSubtitle = document.getElementById('statusBannerSubtitle');
            const statusBadgeTag = document.getElementById('statusBadgeTag');
            const deviceDetailsGrid = document.getElementById('deviceDetailsGrid');
            const infoDeviceName = document.getElementById('infoDeviceName');
            const infoPhoneNumber = document.getElementById('infoPhoneNumber');
            const infoQuota = document.getElementById('infoQuota');
            const infoExpiredAt = document.getElementById('infoExpiredAt');

            // Format tanggal lokal Indonesia
            function formatIndonesianDate(dateString) {
                if (!dateString) return '-';
                try {
                    const date = new Date(dateString);
                    if (isNaN(date.getTime())) return dateString;
                    return new Intl.DateTimeFormat('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }).format(date);
                } catch (e) {
                    return dateString;
                }
            }

            // Fungsi Ambil Status Koneksi Perangkat
            async function fetchDeviceStatus() {
                if (!statusLoadingState || !statusResultContainer) return;

                // Tampilkan loading skeleton
                statusLoadingState.classList.remove('hidden');
                statusResultContainer.classList.add('hidden');
                if (btnRefreshStatus) {
                    btnRefreshStatus.disabled = true;
                    if (iconRefresh) iconRefresh.classList.add('animate-spin');
                }

                try {
                    const response = await fetch('{{ route('admin.settings.whatsapp.status') }}', {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    renderStatusUI(result);
                } catch (error) {
                    renderStatusUI({
                        success: false,
                        connected: false,
                        status: 'error',
                        message: 'Gagal terhubung ke endpoint status lokal: ' + error.message,
                        data: null
                    });
                } finally {
                    statusLoadingState.classList.add('hidden');
                    statusResultContainer.classList.remove('hidden');
                    if (btnRefreshStatus) {
                        btnRefreshStatus.disabled = false;
                        if (iconRefresh) iconRefresh.classList.remove('animate-spin');
                    }
                }
            }

            // Render UI Berdasarkan Respon Status
            function renderStatusUI(res) {
                const status = (res.status || 'unknown').toLowerCase();
                const data = res.data;

                deviceDetailsGrid.classList.remove('hidden');

                if (status === 'connected') {
                    // Status Terhubung
                    statusBanner.className = 'p-4 sm:p-5 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-500/20 text-slate-900 dark:text-emerald-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300';
                    statusBadgeIcon.className = 'w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 text-base shadow-md shadow-emerald-500/25';
                    statusBadgeIcon.innerHTML = '<i class="fa-solid fa-wifi"></i>';
                    statusBannerTitle.textContent = 'Perangkat Terhubung & Siap Kirim';
                    statusBannerSubtitle.textContent = 'Socket WhatsApp aktif dan siap digunakan untuk pengiriman pesan otomatis.';
                    statusBadgeTag.innerHTML = `
                        <span class="inline-flex items-center gap-2 pl-2 pr-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] shadow-xs">
                                <i class="fa-solid fa-check"></i>
                            </span>
                            <span>Connected</span>
                        </span>
                    `;
                } else if (status === 'disconnected') {
                    // Status Terputus
                    statusBanner.className = 'p-4 sm:p-5 rounded-2xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-500/20 text-slate-900 dark:text-rose-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300';
                    statusBadgeIcon.className = 'w-11 h-11 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 text-base shadow-md shadow-rose-500/25';
                    statusBadgeIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                    statusBannerTitle.textContent = 'Perangkat Terputus';
                    statusBannerSubtitle.textContent = 'Perangkat terdaftar namun sesi WhatsApp terputus. Silakan tautkan ulang di dashboard AMBLAST.';
                    statusBadgeTag.innerHTML = `
                        <span class="inline-flex items-center gap-2 pl-2 pr-3.5 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shadow-xs">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                            <span>Disconnected</span>
                        </span>
                    `;
                } else if (status === 'connecting') {
                    // Status Sedang Menghubungkan
                    statusBanner.className = 'p-4 sm:p-5 rounded-2xl bg-amber-50/70 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-500/20 text-slate-900 dark:text-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300';
                    statusBadgeIcon.className = 'w-11 h-11 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 text-base shadow-md shadow-amber-500/25';
                    statusBadgeIcon.innerHTML = '<i class="fa-solid fa-arrows-rotate animate-spin"></i>';
                    statusBannerTitle.textContent = 'Sedang Menghubungkan';
                    statusBannerSubtitle.textContent = 'Perangkat sedang dalam proses inisialisasi socket ke server WhatsApp.';
                    statusBadgeTag.innerHTML = `
                        <span class="inline-flex items-center gap-2 pl-2 pr-3.5 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] shadow-xs">
                                <i class="fa-solid fa-arrows-rotate animate-spin"></i>
                            </span>
                            <span>Connecting</span>
                        </span>
                    `;
                } else if (status === 'unconfigured') {
                    // Status Belum Dikonfigurasi
                    statusBanner.className = 'p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#2e2e2e] text-slate-800 dark:text-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300';
                    statusBadgeIcon.className = 'w-11 h-11 rounded-xl bg-slate-500 text-white flex items-center justify-center shrink-0 text-base shadow-md shadow-slate-500/20';
                    statusBadgeIcon.innerHTML = '<i class="fa-solid fa-key"></i>';
                    statusBannerTitle.textContent = 'API Key Belum Dikonfigurasi';
                    statusBannerSubtitle.textContent = 'Silakan masukkan API Key AMBLAST Anda pada form di bawah untuk mengaktifkan koneksi.';
                    statusBadgeTag.innerHTML = `
                        <span class="inline-flex items-center gap-2 pl-2 pr-3.5 py-1.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-[#1a1a1a] dark:text-slate-300 border border-slate-200 dark:border-[#2e2e2e]">
                            <span class="w-5 h-5 rounded-full bg-slate-500 text-white flex items-center justify-center text-[10px] shadow-xs">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <span>Belum Dikonfigurasi</span>
                        </span>
                    `;
                    deviceDetailsGrid.classList.add('hidden');
                } else {
                    // Status Error (API Key Salah / Request Gagal)
                    statusBanner.className = 'p-4 sm:p-5 rounded-2xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-500/20 text-slate-900 dark:text-rose-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-all duration-300';
                    statusBadgeIcon.className = 'w-11 h-11 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 text-base shadow-md shadow-rose-500/25';
                    statusBadgeIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                    statusBannerTitle.textContent = 'Gagal Mengambil Status';
                    statusBannerSubtitle.textContent = res.message || 'Terjadi kesalahan saat memeriksa perangkat ke AMBLAST.';
                    statusBadgeTag.innerHTML = `
                        <span class="inline-flex items-center gap-2 pl-2 pr-3.5 py-1.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 shadow-xs">
                            <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] shadow-xs">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                            <span>Error</span>
                        </span>
                    `;
                    deviceDetailsGrid.classList.add('hidden');
                }

                // Update Data Grid
                if (data) {
                    infoDeviceName.textContent = data.name || '-';
                    infoPhoneNumber.textContent = data.phone_number ? '+' + data.phone_number : 'Belum Terhubung';
                    infoQuota.textContent = data.message_quota !== undefined ? Number(data.message_quota).toLocaleString('id-ID') + ' Pesan' : '-';
                    infoExpiredAt.textContent = formatIndonesianDate(data.expired_at);
                } else {
                    infoDeviceName.textContent = '-';
                    infoPhoneNumber.textContent = '-';
                    infoQuota.textContent = '-';
                    infoExpiredAt.textContent = '-';
                }
            }

            // Jalankan cek status saat halaman pertama kali dibuka
            fetchDeviceStatus();

            // Event listener klik tombol segarkan status
            if (btnRefreshStatus) {
                btnRefreshStatus.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchDeviceStatus();
                });
            }

            // Toggle show/hide password visibility
            if (toggleApiKeyBtn && apiKeyInput && eyeIcon) {
                toggleApiKeyBtn.addEventListener('click', function() {
                    const isPassword = apiKeyInput.getAttribute('type') === 'password';
                    apiKeyInput.setAttribute('type', isPassword ? 'text' : 'password');
                    eyeIcon.className = isPassword ? 'fa-solid fa-eye-slash text-sm' : 'fa-solid fa-eye text-sm';
                });
            }

            // Simpan snapshot nilai awal input untuk mendeteksi perubahan (dirty-state)
            const initialValue = apiKeyInput ? apiKeyInput.value : '';

            function checkChanges() {
                if (!btnSave || !apiKeyInput) return;
                const hasChanged = apiKeyInput.value !== initialValue;

                if (hasChanged) {
                    btnSave.removeAttribute('disabled');
                    btnSave.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btnSave.setAttribute('disabled', 'true');
                    btnSave.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            if (apiKeyInput) {
                apiKeyInput.addEventListener('input', checkChanges);
                apiKeyInput.addEventListener('change', checkChanges);
            }

            // Event handler konfirmasi simpan
            if (btnSave && form) {
                btnSave.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Konfirmasi Simpan',
                            text: 'Apakah Anda yakin ingin memperbarui API Key WhatsApp Gateway?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: '<i class="fa-solid fa-check mr-1.5"></i> Ya, Simpan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]',
                                confirmButton: 'btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm ml-2',
                                cancelButton: 'btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    } else {
                        form.submit();
                    }
                });
            }
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Berhasil Disimpan!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]'
                        }
                    });
                }
            });
        </script>
    @endif
@endpush
