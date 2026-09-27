@extends('dashboard.layouts.app')

@section('title', 'Detail Penarikan #' . $withdrawal->id . ' - ' . config('app.name'))
@section('description', 'Rincian pengajuan penarikan dana clipper dan aksi persetujuan admin.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Detail Penarikan Dana
                    </h2>
                </div>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Withdraw',
                    'crumb2_url' => route('admin.withdrawals.index'),
                    'crumb3_label' => 'Detail',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Card Panel Utama -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-6 transition-colors duration-300">

                @php
                    $badgeClass = match ($withdrawal->status) {
                        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                        'rejected' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                        'processing' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
                        default => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                    };
                    $badgeIcon = match ($withdrawal->status) {
                        'completed' => 'fa-circle-check',
                        'rejected' => 'fa-circle-xmark',
                        'processing' => 'fa-clock',
                        default => 'fa-hourglass-start',
                    };
                    $statusLabel = match ($withdrawal->status) {
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        'processing' => 'Diproses',
                        default => 'Pending',
                    };
                @endphp

                <!-- Header Bar: ID & Status Transaksi -->
                <div
                    class="pb-4 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200">
                            Data Pengajuan
                        </h3>
                        <span
                            class="text-xs font-mono font-semibold px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] text-slate-800 dark:text-slate-200">
                            #{{ $withdrawal->id }}
                        </span>
                    </div>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold {{ $badgeClass }} w-fit">
                        <i class="fa-solid {{ $badgeIcon }} text-[10px]"></i> {{ $statusLabel }}
                    </span>
                </div>

                <!-- Baris 1: Informasi Pengguna -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <!-- Nama Pengguna -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Nama Pengguna
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-user text-xs"></i>
                            </div>
                            <input type="text" value="{{ $withdrawal->user->name }}" readonly
                                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                        </div>
                    </div>

                    <!-- Email Pengguna -->
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Email
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </div>
                            <input type="text" value="{{ $withdrawal->user->email }}" readonly
                                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-medium text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                        </div>
                    </div>

                    <!-- Waktu Pengajuan -->
                    <div class="sm:col-span-2 md:col-span-1">
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Waktu Pengajuan
                        </label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-calendar text-xs"></i>
                            </div>
                            <input type="text" value="{{ $withdrawal->created_at->format('d M Y, H:i') }} WIB" readonly
                                class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-medium text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                        </div>
                    </div>
                </div>

                <!-- Baris 2: Rekening Tujuan -->
                <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-4">
                        Rekening Tujuan
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Bank Tujuan -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Metode / Bank
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-building-columns text-xs"></i>
                                </div>
                                <input type="text" value="{{ $withdrawal->bank_name }}" readonly
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                            </div>
                        </div>

                        <!-- Nomor Rekening -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Nomor Rekening / No. HP
                            </label>
                            <div class="relative flex items-center">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-credit-card text-xs"></i>
                                </div>
                                <input type="text" id="targetAccountNumber" value="{{ $withdrawal->account_number }}"
                                    readonly
                                    class="w-full pl-9 pr-20 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-mono font-semibold text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                                <button type="button"
                                    onclick="copyText('{{ $withdrawal->account_number }}', 'Nomor Rekening')"
                                    class="absolute right-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-200/80 dark:bg-[#282828] hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-950/40 dark:hover:text-brand-400 text-slate-700 dark:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                        </div>

                        <!-- Nama Pemilik Rekening -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Nama Pemilik Rekening
                            </label>
                            <div class="relative flex items-center">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-id-badge text-xs"></i>
                                </div>
                                <input type="text" id="targetAccountName" value="{{ $withdrawal->account_name }}"
                                    readonly
                                    class="w-full pl-9 pr-20 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                                <button type="button"
                                    onclick="copyText('{{ $withdrawal->account_name }}', 'Nama Pemilik')"
                                    class="absolute right-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-slate-200/80 dark:bg-[#282828] hover:bg-brand-50 hover:text-brand-600 dark:hover:bg-brand-950/40 dark:hover:text-brand-400 text-slate-700 dark:text-slate-300 transition-colors inline-flex items-center gap-1.5">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Baris 3: Rincian Nominal -->
                <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                    <h4 class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-4">
                        Rincian Nominal
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <!-- Nominal Bruto -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Nominal Penarikan
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-coins text-xs"></i>
                                </div>
                                <input type="text" value="Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}"
                                    readonly
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-semibold text-slate-800 dark:text-slate-200 focus:outline-none cursor-default">
                            </div>
                        </div>

                        <!-- Biaya Transaksi -->
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Biaya Transaksi (Fee)
                            </label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-receipt text-xs"></i>
                                </div>
                                <input type="text"
                                    value="{{ $withdrawal->fee > 0 ? 'Rp ' . number_format($withdrawal->fee, 0, ',', '.') : 'Gratis' }}"
                                    readonly
                                    class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm font-semibold text-rose-600 dark:text-rose-400 focus:outline-none cursor-default">
                            </div>
                        </div>

                        <!-- Total Bersih Wajib Ditransfer + Tombol Salin -->
                        <div class="sm:col-span-2 md:col-span-1">
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1.5">
                                Total Bersih Ditransfer
                            </label>
                            <div class="relative flex items-center">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600 dark:text-emerald-400">
                                    <i class="fa-solid fa-money-bill-wave text-xs"></i>
                                </div>
                                <input type="text" id="targetTransferAmount"
                                    value="Rp {{ number_format($withdrawal->net_amount, 0, ',', '.') }}" readonly
                                    class="w-full pl-9 pr-20 py-2.5 bg-emerald-50/70 dark:bg-emerald-950/20 border border-emerald-300 dark:border-emerald-800/50 rounded-xl text-sm font-semibold text-emerald-600 dark:text-emerald-400 focus:outline-none cursor-default">
                                <button type="button"
                                    onclick="copyText('{{ $withdrawal->net_amount }}', 'Nominal Transfer')"
                                    class="absolute right-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition-colors inline-flex items-center gap-1.5">
                                    <i class="fa-regular fa-copy"></i>
                                    <span>Salin</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($withdrawal->status === 'rejected' || $withdrawal->notes)
                    <!-- Baris 4: Catatan Penarikan -->
                    <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                            Catatan Penarikan
                        </label>
                        <textarea rows="2" readonly
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-700 dark:text-slate-300 leading-relaxed cursor-default resize-none focus:outline-none">{{ $withdrawal->notes ?: '-' }}</textarea>
                    </div>
                @endif

                <!-- Status Alert Banner -->
                @if (in_array($withdrawal->status, ['pending', 'processing']))
                    <div
                        class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/40 flex items-center justify-between text-xs text-amber-800 dark:text-amber-300 transition-all duration-200">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-info text-amber-600 dark:text-amber-400"></i>
                            <span>Status: <strong>Menunggu Transfer</strong>. Pastikan dana telah ditransfer ke rekening
                                tujuan sebelum konfirmasi selesai.</span>
                        </div>
                    </div>
                @elseif($withdrawal->status === 'completed')
                    <div
                        class="p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/40 flex items-center justify-between text-xs text-emerald-800 dark:text-emerald-300 transition-all duration-200">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400"></i>
                            <span>Status: <strong>Selesai</strong>. Penarikan dana ini telah dikonfirmasi selesai dan
                                ditransfer.</span>
                        </div>
                    </div>
                @elseif($withdrawal->status === 'rejected')
                    <div
                        class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/40 space-y-1 text-xs">
                        <span class="font-semibold text-rose-800 dark:text-rose-300 block">Status: Ditolak</span>
                        <p class="text-rose-700 dark:text-rose-400">Pengajuan penarikan dana ditolak. Saldo telah
                            dikembalikan secara utuh ke dompet Clipper.</p>
                    </div>
                @endif

                <!-- Tombol Aksi Bawah -->
                <div
                    class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e] flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <a href="{{ route('admin.withdrawals.index') }}"
                            class="btn btn-secondary rounded-xl px-6 py-2.5 text-sm font-semibold inline-flex items-center justify-center gap-2 text-center w-full sm:w-auto">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    @if (in_array($withdrawal->status, ['pending', 'processing']))
                        <div id="actionButtonsContainer"
                            class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                            <form id="rejectForm" action="{{ route('admin.withdrawals.update-status', $withdrawal) }}"
                                method="POST" class="w-full sm:w-auto">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <input type="hidden" name="notes" id="rejectNotes">
                                <button type="button" onclick="confirmReject()"
                                    class="btn bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/30 dark:hover:bg-rose-900/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all w-full sm:w-auto">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>Tolak Pengajuan</span>
                                </button>
                            </form>

                            <form id="approveForm" action="{{ route('admin.withdrawals.update-status', $withdrawal) }}"
                                method="POST" class="w-full sm:w-auto">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="button" onclick="confirmApprove()"
                                    class="btn bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2 transition-all shadow-sm w-full sm:w-auto">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>Konfirmasi Telah Ditransfer</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
        });

        // Copy Text Function
        function copyText(text, type) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil Disalin!',
                    text: `${type} berhasil disalin ke clipboard.`,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }).catch(err => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal Menyalin',
                    text: 'Tidak dapat menyalin teks otomatis.',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        }

        @if (in_array($withdrawal->status, ['pending', 'processing']))
            function confirmApprove() {
                Swal.fire({
                    title: 'Konfirmasi Selesai',
                    text: "Pastikan Anda sudah mentransfer dana sebesar Rp {{ number_format($withdrawal->net_amount, 0, ',', '.') }} ke rekening clipper. Lanjutkan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#059669', // emerald-600
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('approveForm').submit();
                    }
                });
            }

            function confirmReject() {
                Swal.fire({
                    title: 'Tolak Pengajuan Penarikan?',
                    text: "Saldo akan dikembalikan utuh (refund) ke dompet Clipper secara otomatis.",
                    input: 'textarea',
                    inputLabel: 'Alasan Penolakan (Opsional)',
                    inputPlaceholder: 'Misal: Nama rekening tidak sesuai dengan profil.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    confirmButtonColor: '#e11d48', // rose-600
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set the reason to hidden input
                        document.getElementById('rejectNotes').value = result.value;
                        document.getElementById('rejectForm').submit();
                    }
                });
            }
        @endif
    </script>
@endpush
