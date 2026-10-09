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

            <!-- Form Card Utama -->
            <form id="whatsappForm" action="{{ route('admin.settings.whatsapp.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-8 transition-colors duration-300">

                    <!-- Card Header -->
                    <div
                        class="pb-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Konfigurasi WhatsApp
                                    Gateway</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Integrasikan sistem dengan
                                    provider WhatsApp Gateway untuk otomasi pengiriman pesan.</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @if (!empty($apiKey))
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20 self-start sm:self-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                API Key Terpasang
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 self-start sm:self-auto">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Belum Dikonfigurasi
                            </span>
                        @endif
                    </div>

                    <!-- Input API Key WhatsApp -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="apikeyWhatsapp"
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                API Key WhatsApp Gateway
                            </label>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500">Karakter rahasia (Token /
                                Key)</span>
                        </div>

                        <div class="flex relative">
                            <!-- Prefix Icon -->
                            <span
                                class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 dark:border-[#2e2e2e] bg-slate-100/70 dark:bg-[#1a1a1a] text-slate-500 dark:text-slate-400 text-sm select-none">
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

            // Toggle show/hide password visibility
            if (toggleApiKeyBtn && apiKeyInput && eyeIcon) {
                toggleApiKeyBtn.addEventListener('click', function() {
                    const isPassword = apiKeyInput.getAttribute('type') === 'password';
                    apiKeyInput.setAttribute('type', isPassword ? 'text' : 'password');
                    eyeIcon.className = isPassword ? 'fa-solid fa-eye-slash text-sm' :
                        'fa-solid fa-eye text-sm';
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
