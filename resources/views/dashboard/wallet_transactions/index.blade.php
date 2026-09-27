@extends('dashboard.layouts.app')

@section('title', 'Riwayat Transaksi Komisi')
@section('description', 'Log mutasi saldo masuk dan penarikan komisi para clipper')

@push('styles')
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Riwayat Transaksi Komisi
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Komisi',
                    'crumb2_url' => '',
                    'crumb3_label' => 'Riwayat Transaksi',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Table Container Card -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">

                <!-- Header Table Controls -->
                <div
                    class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Show</span>
                        <select id="entriesSelect" class="select2-show-entries w-24">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Filter Tipe Transaksi -->
                        <select id="typeFilterSelect" class="select2-filter-type">
                            <option value="all" {{ !request('type') || request('type') == 'all' ? 'selected' : '' }}>
                                Semua Tipe</option>
                            <option value="tambah"
                                {{ request('type') == 'tambah' || request('type') == 'credit' ? 'selected' : '' }}>Tambah
                                (+)</option>
                            <option value="kurang"
                                {{ request('type') == 'kurang' || request('type') == 'debit' ? 'selected' : '' }}>Kurang (-)
                            </option>
                        </select>

                        <!-- Atur Kolom Dropdown -->
                        <div class="relative shrink-0">
                            <button id="btnColumns"
                                class="px-4 py-2 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-slate-600 dark:text-slate-400 font-medium text-sm hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200 transition flex items-center justify-center gap-2 whitespace-nowrap">
                                <i class="fa-solid fa-table-columns"></i> <span class="whitespace-nowrap">Atur Kolom</span>
                                <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Column Dropdown Menu -->
                            <div id="columnMenu"
                                class="hidden absolute right-0 top-full mt-2 w-56 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl shadow-lg z-50 p-2">
                                <div class="text-xs font-semibold text-slate-400 uppercase px-3 py-2">
                                    Tampilkan Kolom
                                </div>
                                <div id="columnListContainer" class="space-y-1 max-h-60 overflow-y-auto custom-scrollbar">
                                    <div class="px-3 py-2 text-xs text-slate-400">Loading kolom...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Data Table -->
                <div class="overflow-x-auto">
                    <table id="walletTable" class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 dark:bg-[#1c1c1c] text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    #</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Nama</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Type</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Saldo Awal</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Nominal</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Saldo Akhir</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse ($transactions as $tx)
                                @php
                                    $isCredit = $tx->type === 'credit';
                                    $badgeClass = $isCredit
                                        ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400'
                                        : 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400';
                                    $badgeHtml = $isCredit
                                        ? '<i class="fa-solid fa-plus text-[10px]"></i> Tambah'
                                        : '<i class="fa-solid fa-minus text-[10px]"></i> Kurang';
                                    $clipperName = $tx->user->name ?? 'Pengguna Tidak Dikenal';
                                    $clipperEmail = $tx->user->email ?? '-';

                                    $detailPayload = [
                                        'trxId' => (string) $tx->id,
                                        'userName' => $clipperName,
                                        'type' => $isCredit ? 'tambah' : 'kurang',
                                        'badgeClass' => $badgeClass,
                                        'badgeHtml' => $badgeHtml,
                                        'saldoAwal' => 'Rp ' . number_format($tx->balance_before, 0, ',', '.'),
                                        'nominal' =>
                                            ($isCredit ? '+ ' : '- ') . 'Rp ' . number_format($tx->amount, 0, ',', '.'),
                                        'saldoAkhir' => 'Rp ' . number_format($tx->balance_after, 0, ',', '.'),
                                        'description' => $tx->notes ?? 'Tidak ada keterangan mutasi.',
                                        'date' => $tx->created_at
                                            ? $tx->created_at->translatedFormat('d M Y, H:i') . ' WIB'
                                            : '-',
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors"
                                    data-type="{{ $isCredit ? 'tambah' : 'kurang' }}">
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 td-nowrap">
                                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
                                            {{ $tx->created_at ? $tx->created_at->translatedFormat('d M Y') : '-' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $tx->created_at ? $tx->created_at->translatedFormat('H:i') . ' WIB' : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        <div>
                                            <div class="font-semibold text-slate-800 dark:text-slate-200">
                                                {{ $clipperName }}</div>
                                            <div class="text-xs text-slate-400 font-normal">{{ $clipperEmail }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 td-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $badgeClass }}">
                                            {!! $badgeHtml !!}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 td-nowrap">
                                        Rp {{ number_format($tx->balance_before, 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 font-semibold {{ $isCredit ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} td-nowrap">
                                        {{ $isCredit ? '+ ' : '- ' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white td-nowrap">
                                        Rp {{ number_format($tx->balance_after, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <button type="button" class="btn btn-primary btn-icon"
                                            title="Lihat Detail Transaksi"
                                            onclick="openDetailModal({{ json_encode($detailPayload) }})">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada riwayat transaksi saldo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Card -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $transactions->links('dashboard.components.pagination') }}
                </div>

            </div>

        </div>
    </div>

    <!-- Modal Detail Transaksi Komisi -->
    <div id="modalDetailTransaction"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div
            class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl max-w-lg w-full p-6 space-y-5 transition-all">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#2e2e2e]">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Detail Transaksi Komisi</h3>
                </div>
                <button type="button" onclick="closeDetailModal()"
                    class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Info User & ID Transaksi -->
                <div
                    class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">Nama Clipper / Pengguna
                        </p>
                        <p id="modalUserName" class="font-semibold text-slate-900 dark:text-white text-sm mt-0.5">-</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wider">ID Riwayat</p>
                        <p id="modalTrxId"
                            class="text-xs font-mono font-semibold text-slate-800 dark:text-slate-200 mt-0.5">-</p>
                    </div>
                </div>

                <!-- Financial Movement Breakdown -->
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                        <span class="text-slate-400 block mb-1">Saldo Awal</span>
                        <p id="modalSaldoAwal" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Rp 0</p>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                        <span class="text-slate-400 block mb-1">Nominal Mutasi</span>
                        <p id="modalNominal" class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Rp 0</p>
                    </div>

                    <div
                        class="p-3 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                        <span class="text-slate-400 block mb-1">Saldo Akhir</span>
                        <p id="modalSaldoAkhir" class="text-sm font-semibold text-slate-900 dark:text-white">Rp 0</p>
                    </div>
                </div>

                <!-- Deskripsi / Keterangan Mutasi -->
                <div>
                    <label
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Keterangan
                        Transaksi</label>
                    <p id="modalDescription"
                        class="text-xs font-medium text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-[#161616] p-3.5 rounded-xl border border-slate-100 dark:border-[#2e2e2e]">
                        -
                    </p>
                </div>

                <!-- Audit Meta (Waktu & Status) -->
                <div
                    class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e] space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                            <i class="fa-regular fa-clock text-slate-400"></i> Waktu Transaksi
                        </span>
                        <span id="modalDate" class="font-semibold text-slate-700 dark:text-slate-300">-</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-[#242424] pt-2">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                            <i class="fa-regular fa-circle-check text-emerald-500"></i> Status Mutasi
                        </span>
                        <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 dark:text-emerald-400">
                            Berhasil
                        </span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="pt-4 border-t border-slate-100 dark:border-[#2e2e2e] flex justify-end">
                <button type="button" onclick="closeDetailModal()"
                    class="btn btn-secondary rounded-xl px-5 py-2.5 text-sm font-semibold">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Select2 JS -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <!-- Wallet Transactions Page Script -->
    <script src="{{ asset('assets/js/wallet-transactions.page.js') }}"></script>

    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('#entriesSelect').on('change', function() {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', $(this).val());
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });

                $('#typeFilterSelect').on('change', function() {
                    let val = $(this).val();
                    let url = new URL(window.location.href);
                    if (val === 'all') {
                        url.searchParams.delete('type');
                    } else {
                        url.searchParams.set('type', val);
                    }
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });
            }
        });
    </script>
@endpush
