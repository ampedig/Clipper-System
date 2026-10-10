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

    /* Custom SweetAlert modern styling & balanced proportions */
    .swal2-popup.custom-swal-popup {
        padding: 1.35rem 1.25rem !important;
        border-radius: 1.25rem !important;
        max-width: 360px !important;
        width: 90% !important;
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

    /* Modern OTP Input styling inside SweetAlert (Flat clean border, tanpa shadow & tanpa outline hitam) */
    #swal_otp_code,
    .swal2-popup.custom-swal-popup input[type="tel"] {
        outline: none !important;
        border: 1.5px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        color: #0f172a !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        -webkit-tap-highlight-color: transparent !important;
        box-shadow: none !important;
    }

    #swal_otp_code:hover,
    .swal2-popup.custom-swal-popup input[type="tel"]:hover {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        box-shadow: none !important;
    }

    #swal_otp_code:focus,
    #swal_otp_code:focus-visible,
    .swal2-popup.custom-swal-popup input[type="tel"]:focus,
    .swal2-popup.custom-swal-popup input[type="tel"]:focus-visible {
        outline: none !important;
        outline-offset: 0 !important;
        border-color: #10b981 !important;
        background-color: #ffffff !important;
        box-shadow: none !important;
    }

    #swal_otp_code.border-rose-400,
    #swal_otp_code.border-rose-400:focus,
    #swal_otp_code.border-rose-400:focus-visible {
        border-color: #f43f5e !important;
        box-shadow: none !important;
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
            <input type="hidden" name="otp" id="form_otp_input" value="">

            @error('otp')
                <div class="mb-4 p-4 bg-rose-50 border border-rose-200/80 rounded-2xl flex items-center gap-2.5 text-xs font-semibold text-rose-600">
                    <i class="fa-solid fa-circle-exclamation text-sm shrink-0"></i>
                    <span>{{ $message }}</span>
                </div>
            @enderror

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

                const payload = {
                    withdraw_channel_id: bankVal,
                    account_number: numberVal,
                    account_name: nameVal
                };

                let resendTimerInterval = null;

                // Fungsi kirim permintaan OTP ke server
                function requestOtp(dataPayload, onSuccess) {
                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center justify-center py-5">
                                <div class="w-10 h-10 border-3 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin mb-3"></div>
                                <h4 class="text-sm font-bold text-slate-800 mb-0.5">Mengirim Kode OTP...</h4>
                                <p class="text-xs text-slate-500 font-medium">Menghubungi WhatsApp Gateway AZCLIP</p>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full'
                        }
                    });

                    fetch('{{ route('app.rekening.send-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(dataPayload)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            throw new Error(data.message || 'Gagal mengirim kode OTP.');
                        }
                        return data;
                    })
                    .then(data => {
                        if (typeof onSuccess === 'function') {
                            onSuccess(data);
                        } else {
                            showOtpModal(dataPayload, data.masked_wa, data.cooldown || 60);
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            html: `
                                <div class="flex flex-col items-center text-center p-1">
                                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-3 border border-rose-100">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                    </div>
                                    <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Gagal Mengirim OTP</h3>
                                    <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                        ${err.message}
                                    </p>
                                </div>
                            `,
                            showConfirmButton: true,
                            confirmButtonText: 'Tutup',
                            buttonsStyling: false,
                            backdrop: 'rgba(15, 23, 42, 0.65)',
                            customClass: {
                                popup: 'custom-swal-popup !rounded-2xl',
                                actions: 'w-full mt-4 px-0',
                                confirmButton: 'w-full h-10 py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition-all cursor-pointer flex items-center justify-center'
                            }
                        });
                    });
                }

                // Modal Verifikasi OTP WhatsApp
                function showOtpModal(dataPayload, maskedWa, cooldownSeconds) {
                    if (resendTimerInterval) clearInterval(resendTimerInterval);

                    Swal.fire({
                        html: `
                            <div class="flex flex-col items-center text-center p-0.5">
                                <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-3 border border-emerald-100/80 shadow-sm shadow-emerald-500/10">
                                    <i class="fa-brands fa-whatsapp text-2xl text-emerald-600"></i>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 mb-1 tracking-tight">Verifikasi OTP WhatsApp</h3>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                    Masukkan 6 digit kode OTP yang telah dikirim ke nomor WhatsApp Anda:
                                </p>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 mt-2 rounded-full bg-slate-100/90 border border-slate-200/80 text-slate-700 text-xs font-semibold">
                                    <i class="fa-brands fa-whatsapp text-xs text-emerald-600"></i>
                                    <span class="font-mono tracking-wide">${maskedWa || ''}</span>
                                </div>

                                <div class="w-full mt-4 mb-3">
                                    <input type="tel" id="swal_otp_code" maxlength="6" inputmode="numeric" placeholder="• • • • • •" autocomplete="one-time-code"
                                        class="w-full text-center tracking-[0.35em] font-mono text-xl sm:text-2xl font-bold py-2 px-3 rounded-xl transition-all text-slate-900 placeholder:text-slate-300 placeholder:tracking-[0.25em]"
                                        style="outline: none !important; box-shadow: none !important;" />
                                    <div id="swal_otp_error" class="hidden mt-2.5 px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-100 text-[11px] font-semibold text-rose-600 flex items-center justify-center gap-1.5 transition-all">
                                        <i class="fa-solid fa-circle-exclamation text-xs shrink-0"></i>
                                        <span id="swal_otp_error_text"></span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-center gap-1 text-xs text-slate-500">
                                    <span>Belum menerima kode?</span>
                                    <button type="button" id="swal_btn_resend" class="font-semibold text-emerald-600 hover:text-emerald-700 disabled:text-slate-400 disabled:hover:text-slate-400 disabled:cursor-not-allowed cursor-pointer transition-colors inline-flex items-center gap-1" disabled>
                                        Kirim ulang (<span id="swal_resend_timer" class="font-mono font-bold">${cooldownSeconds}</span>s)
                                    </button>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Verifikasi & Simpan',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        allowOutsideClick: false,
                        customClass: {
                            popup: 'custom-swal-popup !rounded-2xl',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full flex flex-row flex-nowrap gap-2.5 mt-4 px-0',
                            confirmButton: 'flex-1 h-10 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold rounded-xl text-xs sm:text-sm whitespace-nowrap text-center transition-all cursor-pointer flex items-center justify-center shadow-sm shadow-emerald-600/20',
                            cancelButton: 'flex-1 h-10 py-2 px-3 bg-slate-100 hover:bg-slate-200 active:scale-[0.98] text-slate-600 hover:text-slate-800 font-semibold rounded-xl text-xs sm:text-sm whitespace-nowrap text-center transition-all cursor-pointer flex items-center justify-center'
                        },
                        didOpen: () => {
                            const otpInput = document.getElementById('swal_otp_code');
                            const resendBtn = document.getElementById('swal_btn_resend');
                            const resendTimerText = document.getElementById('swal_resend_timer');
                            const errorDiv = document.getElementById('swal_otp_error');

                            if (otpInput) {
                                otpInput.focus();
                                otpInput.addEventListener('input', (e) => {
                                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                                    if (errorDiv) errorDiv.classList.add('hidden');
                                    otpInput.classList.remove('border-rose-400');
                                });
                                otpInput.addEventListener('keypress', (e) => {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                        Swal.getConfirmButton().click();
                                    }
                                });
                            }

                            // Start Cooldown Timer
                            let remaining = cooldownSeconds;
                            resendTimerInterval = setInterval(() => {
                                remaining--;
                                if (resendTimerText) resendTimerText.textContent = remaining;
                                if (remaining <= 0) {
                                    clearInterval(resendTimerInterval);
                                    resendTimerInterval = null;
                                    if (resendBtn) {
                                        resendBtn.removeAttribute('disabled');
                                        resendBtn.innerHTML = 'Kirim ulang';
                                    }
                                }
                            }, 1000);

                            if (resendBtn) {
                                resendBtn.addEventListener('click', () => {
                                    resendBtn.setAttribute('disabled', 'true');
                                    resendBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> Mengirim...';
                                    requestOtp(dataPayload, (newRes) => {
                                        showOtpModal(dataPayload, newRes.masked_wa || maskedWa, newRes.cooldown || 60);
                                    });
                                });
                            }
                        },
                        preConfirm: () => {
                            const otpInput = document.getElementById('swal_otp_code');
                            const errorDiv = document.getElementById('swal_otp_error');
                            const errorText = document.getElementById('swal_otp_error_text');
                            const otpVal = otpInput ? otpInput.value.trim() : '';

                            if (otpVal.length !== 6) {
                                if (errorDiv && errorText) {
                                    errorText.textContent = 'Silakan masukkan 6 digit kode OTP.';
                                    errorDiv.classList.remove('hidden');
                                }
                                if (otpInput) {
                                    otpInput.classList.add('border-rose-400');
                                    otpInput.focus();
                                }
                                return false;
                            }

                            Swal.showLoading();

                            return fetch('{{ route('app.rekening.update') }}', {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    ...dataPayload,
                                    otp: otpVal
                                })
                            })
                            .then(async res => {
                                const data = await res.json();
                                if (!res.ok) {
                                    throw new Error(data.message || 'Kode OTP tidak valid atau telah kedaluwarsa.');
                                }
                                return data;
                            })
                            .catch(err => {
                                Swal.hideLoading();
                                if (errorDiv && errorText) {
                                    errorText.textContent = err.message;
                                    errorDiv.classList.remove('hidden');
                                }
                                if (otpInput) {
                                    otpInput.classList.add('border-rose-400');
                                    otpInput.focus();
                                }
                                return false;
                            });
                        }
                    }).then((result) => {
                        if (resendTimerInterval) {
                            clearInterval(resendTimerInterval);
                            resendTimerInterval = null;
                        }

                        if (result.isConfirmed && result.value) {
                            Swal.fire({
                                html: `
                                    <div class="flex flex-col items-center text-center pt-2 px-1">
                                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-3 border border-emerald-100/80">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </div>
                                        <h3 class="text-base font-extrabold text-slate-900 mb-1 tracking-tight">Berhasil Disimpan!</h3>
                                        <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                            Rekening pencairan dana Anda telah diverifikasi dan siap digunakan.
                                        </p>
                                    </div>
                                `,
                                showConfirmButton: true,
                                confirmButtonText: 'Oke, Mengerti',
                                buttonsStyling: false,
                                backdrop: 'rgba(15, 23, 42, 0.65)',
                                customClass: {
                                    popup: 'custom-swal-popup !rounded-2xl',
                                    actions: 'w-full mt-4 px-0',
                                    confirmButton: 'w-full h-10 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs sm:text-sm whitespace-nowrap text-center transition-all cursor-pointer flex items-center justify-center shadow-sm shadow-emerald-600/20'
                                }
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    });
                }

                // Tampilkan SweetAlert Konfirmasi Awal
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
                        confirmButtonText: 'Kirim OTP',
                        cancelButtonText: 'Batal',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-2xl',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full flex flex-row flex-nowrap gap-2.5 mt-4 px-0',
                            confirmButton: 'flex-1 h-10 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs sm:text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer flex items-center justify-center shadow-sm shadow-indigo-600/20',
                            cancelButton: 'flex-1 h-10 py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 font-semibold rounded-xl text-xs sm:text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer flex items-center justify-center'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            requestOtp(payload);
                        }
                    });
                } else {
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
                            popup: 'custom-swal-popup !rounded-2xl',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full mt-4 px-0',
                            confirmButton: 'w-full h-10 py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs sm:text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer flex items-center justify-center shadow-sm shadow-emerald-600/20'
                        }
                    });
                }
            });
        @endif
    </script>
@endpush

@include('app.partials.vendor-script')
