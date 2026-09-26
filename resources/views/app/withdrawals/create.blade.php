@include('app.partials.head', [
    'title' => 'Tarik Saldo',
])

<style>
    /* Custom SweetAlert padding & container alignment */
    .swal2-popup.custom-swal-popup {
        padding: 1.5rem !important;
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
</style>

<div class="min-h-[100dvh] bg-slate-50 pb-32 relative">

    <!-- Top App Bar (Modern Premium) -->
    <header class="flex items-center justify-between px-5 py-2.5 bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/50">
        <div class="flex items-center gap-3">
            <button type="button" onclick="window.history.back()" class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0 cursor-pointer">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Tarik Saldo</h1>
        </div>
        <a href="{{ route('app.withdrawals.index') }}" class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0 cursor-pointer" aria-label="Riwayat Penarikan" title="Riwayat Penarikan">
            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
        </a>
    </header>

    <div class="p-4 space-y-4">

        <!-- Available Balance -->
        <section class="bg-white rounded-[1.5rem] p-4 border border-slate-200 text-center">
            <p class="text-xs font-semibold text-slate-500 tracking-wide mb-1">Saldo tersedia</p>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rp{{ number_format($user->balance, 0, ',', '.') }}</h2>
        </section>

        <!-- Destination Bank -->
        @if ($hasRekening)
            <a href="{{ route('app.rekening') }}" class="bg-white rounded-[1.5rem] p-4 border border-slate-200 flex items-center justify-between group cursor-pointer hover:border-indigo-300 transition-colors block">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Rekening Tujuan</p>
                        <p class="text-sm font-bold text-slate-900 leading-tight truncate">
                            {{ $user->withdrawChannel->name }}
                        </p>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            {{ $user->account_number }}
                        </p>
                    </div>
                </div>
                <div class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full group-hover:bg-indigo-100 transition-colors flex items-center gap-1 shrink-0 ml-2">
                    Ubah <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </div>
            </a>
        @else
            <a href="{{ route('app.rekening') }}" class="bg-amber-50/70 rounded-[1.5rem] p-4 border border-amber-200/80 flex items-center justify-between group cursor-pointer hover:border-amber-300 transition-colors block">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-0.5">Rekening Belum Diatur</p>
                        <p class="text-xs font-semibold text-amber-900 leading-tight">Tambahkan rekening bank atau e-wallet Anda</p>
                    </div>
                </div>
                <div class="text-[10px] font-bold text-white bg-amber-600 px-3 py-1.5 rounded-full group-hover:bg-amber-700 transition-colors flex items-center gap-1 shrink-0 ml-2">
                    Atur <i class="fa-solid fa-chevron-right text-[8px]"></i>
                </div>
            </a>
        @endif

        <form id="withdrawForm" action="{{ route('app.withdrawals.store') }}" method="POST">
            @csrf

            <!-- Withdrawal Amount -->
            <section class="bg-white rounded-[1.5rem] p-4 border border-slate-200">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Jumlah Penarikan</h3>
                
                <div id="amount-container" class="relative flex items-center transition-colors">
                    <span class="text-xl font-bold text-slate-400 mr-2">Rp</span>
                    <input 
                        type="number" 
                        name="amount" 
                        id="amount" 
                        value="{{ old('amount', min($user->balance, max($minimalWd, 50000))) }}" 
                        class="w-full text-3xl font-extrabold text-slate-900 bg-transparent focus:outline-none"
                        {{ !$hasRekening ? 'disabled' : '' }}
                    />
                </div>
                
                <!-- Validation Error -->
                <div id="error-message" class="hidden mt-3 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span id="error-text">Minimal penarikan Rp{{ number_format($minimalWd, 0, ',', '.') }}</span>
                </div>

                @error('amount')
                    <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </section>

            <!-- Withdrawal Details -->
            <section class="bg-white rounded-[1.5rem] p-4 border border-slate-200 mt-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Rincian Penarikan</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Jumlah</span>
                        <span class="font-bold text-slate-900" id="detail-amount">Rp0</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Biaya Admin</span>
                        <span class="font-bold {{ $adminFee > 0 ? 'text-rose-500' : 'text-slate-900' }}">Rp{{ number_format($adminFee, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="border-t border-slate-100 pt-3 mt-1">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-slate-700">Diterima</span>
                            <span class="text-lg font-extrabold text-indigo-600" id="detail-received">Rp0</span>
                        </div>
                    </div>
                </div>
            </section>
        </form>

    </div>

    <!-- Bottom Fixed Button (Floating) -->
    <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-4 pb-6 pt-2 z-40 pb-safe">
        <button 
            type="button" 
            id="btn-withdraw" 
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-3.5 rounded-2xl transition-all active:scale-[0.98] shadow-[0_8px_30px_rgb(0,0,0,0.12)] cursor-pointer"
            {{ !$hasRekening ? 'disabled' : '' }}
        >
            Tarik Saldo
        </button>
    </div>

</div>

@push('scripts')
<script>
    const amountInput = document.getElementById('amount');
    const detailAmount = document.getElementById('detail-amount');
    const detailReceived = document.getElementById('detail-received');
    const btnWithdraw = document.getElementById('btn-withdraw');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    const withdrawForm = document.getElementById('withdrawForm');

    const adminFee = {{ $adminFee }};
    const minimalWd = {{ $minimalWd }};
    const userBalance = {{ $user->balance }};
    const hasRekening = {{ $hasRekening ? 'true' : 'false' }};

    function formatRupiah(number) {
        return 'Rp' + number.toLocaleString('id-ID');
    }

    function validateAmount() {
        if (!amountInput) return;

        const val = parseInt(amountInput.value) || 0;
        detailAmount.textContent = formatRupiah(val);
        const received = Math.max(0, val - adminFee);
        detailReceived.textContent = formatRupiah(received);

        if (!hasRekening) {
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Silakan atur rekening tujuan terlebih dahulu.';
            btnWithdraw.disabled = true;
            btnWithdraw.classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }

        if (val < minimalWd) {
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Minimal penarikan ' + formatRupiah(minimalWd);
            btnWithdraw.disabled = true;
            btnWithdraw.classList.add('opacity-50', 'cursor-not-allowed');
        } else if (val > userBalance) {
            errorMessage.classList.remove('hidden');
            errorText.textContent = 'Saldo tidak mencukupi (Maksimal: ' + formatRupiah(userBalance) + ')';
            btnWithdraw.disabled = true;
            btnWithdraw.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            errorMessage.classList.add('hidden');
            btnWithdraw.disabled = false;
            btnWithdraw.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    if (amountInput) {
        amountInput.addEventListener('input', validateAmount);
        validateAmount();
    }

    if (btnWithdraw) {
        btnWithdraw.addEventListener('click', () => {
            const val = parseInt(amountInput.value) || 0;

            if (val < minimalWd || val > userBalance || !hasRekening) {
                validateAmount();
                return;
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center text-center">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4 border border-indigo-100/80">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Konfirmasi Penarikan</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[260px]">
                                Apakah Anda yakin ingin menarik saldo sebesar <span class="font-extrabold text-slate-900">${formatRupiah(val)}</span>?
                            </p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    backdrop: 'rgba(15, 23, 42, 0.65)',
                    customClass: {
                        popup: 'custom-swal-popup !rounded-[2.25rem]',
                        actions: 'w-full flex flex-row flex-nowrap gap-3 mt-6 px-0',
                        confirmButton: 'flex-1 py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer',
                        cancelButton: 'flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        withdrawForm.submit();
                    }
                });
            } else {
                withdrawForm.submit();
            }
        });
    }

    // Flash message SweetAlert notifications
    @if (session('success'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                html: `
                    <div class="flex flex-col items-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 border border-emerald-100/80">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Penarikan Berhasil!</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[260px]">
                            {{ session('success') }}
                        </p>
                    </div>
                `,
                showConfirmButton: false,
                timer: 2000,
                backdrop: 'rgba(15, 23, 42, 0.65)',
                customClass: {
                    popup: 'custom-swal-popup !rounded-[2.25rem]'
                }
            });
        }
    @endif

    @if (session('error'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Perhatian',
                text: "{{ session('error') }}",
                icon: 'warning',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'custom-swal-popup !rounded-[2.25rem]',
                    confirmButton: 'py-3 px-5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm'
                },
                buttonsStyling: false
            });
        }
    @endif
</script>
@endpush

@include('app.partials.vendor-script')
