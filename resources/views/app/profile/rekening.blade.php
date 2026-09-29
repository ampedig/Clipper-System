@include('app.partials.head', [
    'title' => 'Atur Rekening',
])

<!-- Select2 CSS (Local Asset) -->
<link href="{{ asset('app/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" />

<style>
    /* Modern Clean Select2 Overrides */
    .select2-container {
        width: 100% !important;
        display: block;
    }

    .select2-container .select2-selection--single {
        height: 48px !important;
        background-color: rgba(248, 250, 252, 0.8) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.75rem !important;
        display: flex !important;
        align-items: center !important;
        padding-left: 0.875rem !important;
        padding-right: 2.5rem !important;
        transition: all 0.2s ease !important;
        position: relative !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #6366f1 !important;
        background-color: #ffffff !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #0f172a !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        line-height: normal !important;
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #94a3b8 !important;
        font-weight: 500 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
        right: 0.875rem !important;
        width: 20px !important;
        position: absolute !important;
        top: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #64748b transparent transparent transparent !important;
        border-width: 5px 4px 0 4px !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        margin-top: -2px !important;
        margin-left: -4px !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #64748b transparent !important;
        border-width: 0 4px 5px 4px !important;
        margin-top: -3px !important;
    }

    .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
        background-color: #ffffff !important;
        overflow: hidden !important;
        margin-top: 4px !important;
        padding: 0.375rem 0 !important;
        z-index: 9999 !important;
    }

    .select2-search--dropdown {
        padding: 0.5rem 0.75rem !important;
    }

    .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8125rem !important;
        font-weight: 500 !important;
        outline: none !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
        width: 100% !important;
    }

    .select2-search--dropdown .select2-search__field:focus {
        border-color: #6366f1 !important;
        background-color: #ffffff !important;
    }

    .select2-results__option {
        padding: 0.625rem 1rem !important;
        font-size: 0.8125rem !important;
        font-weight: 600 !important;
        color: #334155 !important;
        transition: background-color 0.15s ease !important;
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
        background-color: #eef2ff !important;
        color: #4f46e5 !important;
    }

    .select2-container--default .select2-results__option--selected {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        font-weight: 700 !important;
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

    /* Modern Detail Rekening Card (Soft subtle border, replaces harsh black outline) */
    .rekening-detail-box {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 1rem !important;
    }
</style>

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
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Atur Rekening</h1>
        </div>
    </header>

    <!-- Main Content Form -->
    <main class="p-4 space-y-4">

        <!-- Info Note Banner -->
        <div class="bg-indigo-50/70 border border-indigo-100/80 rounded-2xl p-4 flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 mb-0.5">Pencairan Dana</h4>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                    Penarikan saldo komisi campaign akan dikirimkan ke rekening atau e-wallet yang Anda simpan
                    di bawah ini.
                </p>
            </div>
        </div>

        <form id="rekeningForm" action="{{ route('app.rekening.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Form Fields Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-5">

                <!-- Field 1: Pilih Bank / E-Wallet (Select2) -->
                <div class="relative">
                    <label for="bank_select"
                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Pilih Bank / E-Wallet
                    </label>

                    <select id="bank_select" name="withdraw_channel_id" class="w-full">
                        <option value=""></option>
                        @foreach ($channels as $channel)
                            <option value="{{ $channel->id }}"
                                {{ old('withdraw_channel_id', $user->withdraw_channel_id) == $channel->id ? 'selected' : '' }}>
                                {{ $channel->name }} (Fee {{ number_format($channel->fee, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>

                    <div id="bank-error"
                        class="hidden mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Silakan pilih bank atau e-wallet tujuan pencairan.</span>
                    </div>
                    @error('withdraw_channel_id')
                        <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Field 2: Nomor Rekening -->
                <div>
                    <label for="account_number"
                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Nomor Rekening / No. HP E-Wallet
                    </label>
                    <input type="tel" id="account_number" name="account_number"
                        value="{{ old('account_number', $user->account_number) }}" placeholder="Contoh: 1234567890"
                        class="w-full px-4 py-3.5 bg-slate-50/80 border @error('account_number') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-colors" />
                    <div id="number-error"
                        class="hidden mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Nomor rekening tidak valid (minimal 5 digit angka)</span>
                    </div>
                    @error('account_number')
                        <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Field 3: Nama Pemilik Rekening -->
                <div>
                    <label for="account_name"
                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Nama Pemilik Rekening
                    </label>
                    <input type="text" id="account_name" name="account_name"
                        value="{{ old('account_name', $user->account_name) }}"
                        placeholder="Nama sesuai buku tabungan / e-wallet"
                        class="w-full px-4 py-3.5 bg-slate-50/80 border @error('account_name') border-rose-400 @else border-slate-200 @enderror rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 focus:bg-white transition-colors" />
                    <p class="text-[11px] text-slate-400 font-medium mt-1.5">
                        Pastikan nama lengkap sama persis dengan yang terdaftar di bank atau akun e-wallet Anda.
                    </p>
                    <div id="name-error"
                        class="hidden mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>Nama pemilik rekening tidak boleh kosong (minimal 2 karakter)</span>
                    </div>
                    @error('account_name')
                        <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

            </div>
        </form>

    </main>

    <!-- Bottom Fixed Save Button (Floating) -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-4 pb-6 pt-2 z-40 pb-safe">
        <button type="button" id="btn-save-rekening"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-3.5 rounded-2xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
            <i class="fa-regular fa-floppy-disk text-xs"></i>
            <span>Simpan Rekening</span>
        </button>
    </div>

</div>

@push('scripts')
    <!-- jQuery & Select2 JS (Local Assets) -->
    <script src="{{ asset('app/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('app/assets/libs/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            const $bankSelect = $('#bank_select');

            // Inisialisasi Select2 dengan width 100% dan dropdownParent ke parent div
            $bankSelect.select2({
                placeholder: 'Pilih Bank / E-Wallet',
                allowClear: false,
                width: '100%',
                dropdownParent: $bankSelect.parent()
            });

            // Hilangkan pesan error saat bank dipilih
            $bankSelect.on('change', function() {
                $('#bank-error').addClass('hidden');
            });
        });

        // Filter numeric hanya angka pada nomor rekening
        const accNumInput = document.getElementById('account_number');
        if (accNumInput) {
            accNumInput.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        }

        // Handler Simpan Rekening
        const btnSave = document.getElementById('btn-save-rekening');
        const accNameInput = document.getElementById('account_name');
        const bankError = document.getElementById('bank-error');
        const numberError = document.getElementById('number-error');
        const nameError = document.getElementById('name-error');
        const form = document.getElementById('rekeningForm');

        if (btnSave) {
            btnSave.addEventListener('click', () => {
                const bankVal = $('#bank_select').val();
                const bankText = $('#bank_select option:selected').text().trim();
                const numberVal = accNumInput.value.trim();
                const nameVal = accNameInput.value.trim();
                let isValid = true;

                // Validasi Bank
                if (!bankVal) {
                    bankError.classList.remove('hidden');
                    isValid = false;
                } else {
                    bankError.classList.add('hidden');
                }

                // Validasi Nomor Rekening
                if (numberVal.length < 5) {
                    numberError.classList.remove('hidden');
                    accNumInput.classList.add('border-rose-400');
                    isValid = false;
                } else {
                    numberError.classList.add('hidden');
                    accNumInput.classList.remove('border-rose-400');
                }

                // Validasi Nama Pemilik
                if (nameVal.length < 2) {
                    nameError.classList.remove('hidden');
                    accNameInput.classList.add('border-rose-400');
                    isValid = false;
                } else {
                    nameError.classList.add('hidden');
                    accNameInput.classList.remove('border-rose-400');
                }

                if (!isValid) return;

                // Tampilkan SweetAlert Konfirmasi Modern
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center text-center p-1">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4 border border-indigo-100/80">
                                    <i class="fa-solid fa-building-columns"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Konfirmasi Rekening</h3>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[260px] mb-4">
                                    Pastikan data rekening tujuan pencairan dana Anda sudah sesuai.
                                </p>
                                <div class="rekening-detail-box w-full p-4 text-left space-y-2.5 text-xs rounded-2xl" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 1rem;">
                                    <div class="flex justify-between items-center gap-3 text-slate-500">
                                        <span class="shrink-0 font-medium">Bank / E-Wallet:</span>
                                        <span class="font-bold text-slate-800 text-right truncate">${bankText}</span>
                                    </div>
                                    <div class="flex justify-between items-center gap-3 text-slate-500">
                                        <span class="shrink-0 font-medium">No. Rekening:</span>
                                        <span class="font-bold text-slate-800 text-right font-mono">${numberVal}</span>
                                    </div>
                                    <div class="flex justify-between items-center gap-3 text-slate-500">
                                        <span class="shrink-0 font-medium">Nama Pemilik:</span>
                                        <span class="font-bold text-slate-800 text-right truncate">${nameVal}</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full flex flex-row flex-nowrap gap-3 mt-6 px-0',
                            confirmButton: 'flex-1 py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer',
                            cancelButton: 'flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            btnSave.disabled = true;
                            btnSave.innerHTML =
                                '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Menyimpan...</span>';
                            btnSave.classList.add('opacity-80', 'cursor-not-allowed');
                            form.submit();
                        }
                    });
                } else {
                    btnSave.disabled = true;
                    btnSave.innerHTML =
                        '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Menyimpan...</span>';
                    form.submit();
                }
            });
        }

        // Alert Sukses Modern setelah redirect
        @if (session('status') === 'rekening-updated' || session('success'))
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center text-center pt-1 px-1">
                                <h3 class="text-base font-extrabold text-slate-900 mb-1 tracking-tight">Berhasil Disimpan!</h3>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px] mb-4">
                                    Rekening pencairan dana Anda berhasil diperbarui dan siap digunakan.
                                </p>
                                @if ($user->withdrawChannel && $user->account_number)
                                <div class="w-full bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 text-left">
                                    <p class="text-xs font-bold text-slate-800 truncate mb-0.5">{{ $user->withdrawChannel->name }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono tracking-wide">{{ $user->account_number }} • {{ $user->account_name }}</p>
                                </div>
                                @endif
                            </div>
                        `,
                        showConfirmButton: true,
                        confirmButtonText: 'Oke, Mengerti',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full mt-5 px-0',
                            confirmButton: 'w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer outline-none focus:outline-none focus:ring-0 shadow-none'
                        }
                    });
                }
            });
        @endif
    </script>
@endpush

@include('app.partials.vendor-script')
