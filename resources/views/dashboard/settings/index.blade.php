@extends('dashboard.layouts.app')

@section('title', 'Pengaturan Sistem - AMPEDIG Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Pengaturan
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Kelola kontak bantuan customer service dan batas nominal penarikan saldo clipper.
                    </p>
                </div>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Pengaturan',
                    'crumb2_url' => '',
                    'crumb3_label' => '',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Form Card Utama -->
            <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-8 transition-colors duration-300">
                    
                    <!-- Card Header -->
                    <div class="pb-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Pengaturan Umum & Bantuan</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Konfigurasi saluran komunikasi clipper dan batasan penarikan dana.</p>
                        </div>
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium">* Kolom wajib diisi</span>
                    </div>

                    <!-- ================= GRUP 1: CUSTOMER SERVICE ================= -->
                    <div class="space-y-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-semibold text-slate-900 dark:text-white">Customer Service</h4>
                            </div>
                        </div>

                        <!-- 2 Input: CS WhatsApp & CS Telegram (Kiri & Kanan) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- CS WhatsApp (Kiri) -->
                            <div class="space-y-1.5">
                                <label for="csWhatsapp" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    CS WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 dark:border-[#2e2e2e] bg-slate-100/70 dark:bg-[#1a1a1a] text-slate-700 dark:text-slate-200 text-sm font-semibold select-none">
                                        62
                                    </span>
                                    <input 
                                        type="text" 
                                        id="csWhatsapp" 
                                        name="cs_whatsapp"
                                        value="{{ old('cs_whatsapp', $settings['cs_whatsapp'] ?? '') }}"
                                        placeholder="81234567890" 
                                        class="flex-1 min-w-0 block w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-none rounded-r-xl text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all font-medium focus:z-10 @error('cs_whatsapp') border-red-500 @enderror"
                                        required
                                    >
                                </div>
                                @error('cs_whatsapp')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- CS Telegram (Kanan) -->
                            <div class="space-y-1.5">
                                <label for="csTelegram" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    CS Telegram <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 dark:border-[#2e2e2e] bg-slate-100/70 dark:bg-[#1a1a1a] text-slate-700 dark:text-slate-200 text-sm font-semibold select-none">
                                        @
                                    </span>
                                    <input 
                                        type="text" 
                                        id="csTelegram" 
                                        name="cs_telegram"
                                        value="{{ old('cs_telegram', $settings['cs_telegram'] ?? '') }}"
                                        placeholder="clipper_support" 
                                        class="flex-1 min-w-0 block w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-none rounded-r-xl text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all font-medium focus:z-10 @error('cs_telegram') border-red-500 @enderror"
                                        required
                                    >
                                </div>
                                @error('cs_telegram')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <hr class="border-slate-100 dark:border-[#2e2e2e]">

                    <!-- ================= GRUP 2: BATAS PENARIKAN (MINIMAL WITHDRAW) ================= -->
                    <div class="space-y-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm shrink-0">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <h4 class="text-base font-semibold text-slate-900 dark:text-white">Batas Penarikan Saldo (Withdrawal)</h4>
                            </div>
                        </div>

                        <!-- Input Minimal Withdraw -->
                        <div class="max-w-md space-y-1.5">
                            <label for="minWithdraw" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Minimal Withdraw <span class="text-rose-500">*</span>
                            </label>
                            <div class="flex">
                                <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 dark:border-[#2e2e2e] bg-slate-100/70 dark:bg-[#1a1a1a] text-slate-700 dark:text-slate-200 text-sm font-semibold select-none">
                                    Rp
                                </span>
                                <input 
                                    type="text" 
                                    id="minWithdraw" 
                                    name="min_withdraw"
                                    value="{{ old('min_withdraw', number_format($settings['minimal_wd'] ?? 0, 0, ',', '.')) }}"
                                    placeholder="50.000" 
                                    class="flex-1 min-w-0 block w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-none rounded-r-xl text-sm text-slate-900 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:bg-white dark:focus:bg-[#161616] focus:border-brand-500 dark:focus:border-brand-500 outline-none transition-all font-semibold focus:z-10 @error('min_withdraw') border-red-500 @enderror"
                                    required
                                >
                            </div>
                            @error('min_withdraw')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex items-center justify-end pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                        <button type="button" id="btnSave" disabled class="btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center gap-2 opacity-50 cursor-not-allowed transition-all">
                            <i class="fa-solid fa-save text-xs"></i>
                            <span>Simpan Pengaturan</span>
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

    <!-- Page Interactive Scripts (Ringkas di tag script HTML sesuai brief FE) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('settingsForm');
            const csWhatsappInput = document.getElementById('csWhatsapp');
            const csTelegramInput = document.getElementById('csTelegram');
            const minWithdrawInput = document.getElementById('minWithdraw');
            const btnCancel = document.getElementById('btnCancel');
            const btnSave = document.getElementById('btnSave');

            // Format input ribuan untuk Minimal Withdraw
            if (minWithdrawInput) {
                minWithdrawInput.addEventListener('input', function (e) {
                    let val = this.value.replace(/\D/g, '');
                    if (val) {
                        this.value = parseInt(val, 10).toLocaleString('id-ID');
                    } else {
                        this.value = '';
                    }
                });
            }

            // Otomatis hapus awalan 0 atau 62 jika user mengetik atau menempelkan nomor pada CS WhatsApp
            if (csWhatsappInput) {
                csWhatsappInput.addEventListener('input', function () {
                    let val = this.value.replace(/\D/g, '');
                    if (val.startsWith('62')) {
                        val = val.substring(2);
                    }
                    if (val.startsWith('0')) {
                        val = val.substring(1);
                    }
                    this.value = val;
                });
            }

            // Otomatis hapus simbol @ jika user mengetik atau menempelkan tanda @ pada CS Telegram
            if (csTelegramInput) {
                csTelegramInput.addEventListener('input', function () {
                    if (this.value.includes('@')) {
                        this.value = this.value.replace(/@+/g, '');
                    }
                });
            }

            // Simpan snapshot nilai awal untuk perbandingan
            const initialValues = {
                csWhatsapp: csWhatsappInput ? csWhatsappInput.value : '',
                csTelegram: csTelegramInput ? csTelegramInput.value : '',
                minWithdraw: minWithdrawInput ? minWithdrawInput.value : ''
            };

            // Fungsi untuk memeriksa apakah ada perubahan
            function checkChanges() {
                if (!btnSave) return;
                
                const currentWa = csWhatsappInput ? csWhatsappInput.value : '';
                const currentTg = csTelegramInput ? csTelegramInput.value : '';
                const currentMin = minWithdrawInput ? minWithdrawInput.value : '';

                if (currentWa !== initialValues.csWhatsapp || currentTg !== initialValues.csTelegram || currentMin !== initialValues.minWithdraw) {
                    btnSave.removeAttribute('disabled');
                    btnSave.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btnSave.setAttribute('disabled', 'true');
                    btnSave.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Tambahkan event listener ke setiap input untuk mengecek perubahan setiap kali user mengetik
            [csWhatsappInput, csTelegramInput, minWithdrawInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', checkChanges);
                }
            });

            // Event handler submit form (tombol save)
            if (btnSave && form) {
                btnSave.addEventListener('click', function (e) {
                    e.preventDefault();

                    const waVal = csWhatsappInput ? csWhatsappInput.value.trim() : '';
                    const tgVal = csTelegramInput ? csTelegramInput.value.trim() : '';
                    const minWdVal = minWithdrawInput ? minWithdrawInput.value.trim() : '';

                    if (!waVal || !tgVal || !minWdVal) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Perhatian',
                                text: 'Semua kolom bertanda bintang wajib diisi lengkap.',
                                icon: 'warning',
                                confirmButtonText: 'Mengerti',
                                customClass: {
                                    popup: 'rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]',
                                    confirmButton: 'btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm'
                                },
                                buttonsStyling: false
                            });
                        }
                        return;
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Konfirmasi Simpan',
                            text: 'Apakah Anda yakin ingin memperbarui konfigurasi pengaturan sistem?',
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
                                // Tampilkan state loading atau submit form
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
