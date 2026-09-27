@extends('dashboard.layouts.app')

@section('title', 'Detail Clipper - ' . $clipper->name)

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
                        Detail Clipper
                    </h2>
                </div>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Clipper',
                    'crumb2_url' => route('admin.clippers.index'),
                    'crumb3_label' => 'Detail',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Panel: Informasi Akun -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 transition-colors">
                <div
                    class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-[#2e2e2e] pb-3 flex-wrap gap-3">
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-brand-600 dark:text-brand-400"></i>
                            Informasi Akun
                        </h3>
                        <span
                            class="px-2 py-0.5 rounded-lg text-xs font-mono font-medium bg-slate-100 dark:bg-[#1c1c1c] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-[#2e2e2e]">
                            #{{ $clipper->id }}
                        </span>
                    </div>
                    <a href="{{ route('admin.clippers.edit', $clipper->id) }}"
                        class="btn btn-primary rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Clipper</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 text-sm">
                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Nama
                            Lengkap</label>
                        <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $clipper->name }}</div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Alamat
                            Email</label>
                        <div class="font-semibold text-slate-800 dark:text-slate-200 break-all">{{ $clipper->email }}</div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Nomor
                            WhatsApp</label>
                        @if ($clipper->whatsapp)
                            @php
                                $waClean = preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $clipper->whatsapp));
                            @endphp
                            <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer"
                                class="font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp"></i> {{ $clipper->whatsapp }}
                            </a>
                        @else
                            <div class="font-semibold text-slate-800 dark:text-slate-200">-</div>
                        @endif
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Peran
                            / Role</label>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400">
                            {{ ucfirst($clipper->role) }}
                        </span>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Status
                            Akun</label>
                        @if ($clipper->is_active)
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xl text-xs font-semibold bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xl text-xs font-semibold bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400">
                                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Nonaktif
                            </span>
                        @endif
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Saldo
                            Dompet</label>
                        <div class="font-semibold text-slate-800 dark:text-slate-200">Rp
                            {{ number_format($clipper->balance, 0, ',', '.') }}</div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Tanggal
                            Terdaftar</label>
                        <div class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ $clipper->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Terakhir
                            Diperbarui</label>
                        <div class="font-semibold text-slate-800 dark:text-slate-200">
                            {{ $clipper->updated_at ? $clipper->updated_at->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3 Top Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Card 1: Saldo -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Saldo
                            Dompet</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($clipper->balance, 0, ',', '.') }}
                    </div>
                    <div
                        class="mt-2 pt-2 border-t border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between">
                        <span class="text-xs text-slate-400">Saldo Tersedia</span>
                        <a href="{{ route('admin.riwayat-saldo.index', ['search' => $clipper->email]) }}"
                            class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1">
                            <span>Mutasi Saldo</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Pengajuan Clip Disetujui -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total
                            Clip Disetujui</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-film"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span
                            class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">{{ $approvedSubmissionsCount }}</span>
                        <span class="text-sm font-medium text-slate-400 dark:text-slate-500">/ {{ $totalSubmissionsCount }}
                            Diajukan</span>
                    </div>
                    <div
                        class="mt-2 pt-2 border-t border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between">
                        <span class="text-xs text-slate-400">{{ $approvalRate }}% Tingkat Kelolosan</span>
                        <a href="{{ route('admin.clip-submissions.index', ['search' => $clipper->email]) }}"
                            class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1">
                            <span>Daftar Video</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Rekening Pencairan -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rekening
                            Pencairan</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-slate-900 dark:text-white tracking-tight truncate">
                        {{ $clipper->withdrawChannel->name ?? 'Belum Diatur' }}
                    </div>
                    <div
                        class="mt-2 pt-2 border-t border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between">
                        <span
                            class="text-xs font-mono font-medium text-slate-600 dark:text-slate-300">{{ $clipper->account_number ?? '-' }}</span>
                        @if ($clipper->account_number)
                            <button type="button" onclick="copyAccountText('{{ $clipper->account_number }}')"
                                class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1 cursor-pointer"
                                title="Salin Rekening">
                                <i class="fa-regular fa-copy"></i>
                                <span>Salin</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 1 Kolom Utama ke Bawah -->
            <div class="space-y-6">



                <!-- Panel 2: Rekening Pencairan Dana -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 transition-colors">
                    <h3
                        class="text-base font-semibold text-slate-800 dark:text-slate-200 mb-5 flex items-center gap-2 border-b border-slate-100 dark:border-[#2e2e2e] pb-3">
                        <i class="fa-solid fa-building-columns text-brand-600 dark:text-brand-400"></i>
                        Informasi Rekening Pencairan Dana
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Bank
                                / Saluran Pencairan</label>
                            <div class="flex items-center gap-2 pt-0.5">
                                @if ($clipper->withdrawChannel)
                                    <span
                                        class="font-semibold text-slate-800 dark:text-slate-200">{{ $clipper->withdrawChannel->name }}</span>
                                @else
                                    <span class="text-slate-400">Belum diatur</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Nomor
                                Rekening</label>
                            <div class="flex items-center gap-2 pt-1">
                                <span
                                    class="font-mono font-semibold text-slate-800 dark:text-slate-200 text-base">{{ $clipper->account_number ?: '-' }}</span>
                                @if ($clipper->account_number)
                                    <button type="button" onclick="copyAccountText('{{ $clipper->account_number }}')"
                                        class="text-xs text-slate-400 hover:text-brand-600 dark:hover:text-brand-400 transition cursor-pointer"
                                        title="Salin">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Nama
                                Pemilik Rekening</label>
                            <div class="flex items-center gap-2 pt-1">
                                <span
                                    class="font-semibold text-slate-800 dark:text-slate-200">{{ $clipper->account_name ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 3: Riwayat Mutasi Saldo Dompet Terbaru -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors">
                    <div
                        class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-brand-600 dark:text-brand-400"></i>
                                Mutasi Saldo Terkini
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Aktivitas pendapatan komisi dan penarikan saldo oleh clipper ini
                            </p>
                        </div>
                        <a href="{{ route('admin.riwayat-saldo.index', ['search' => $clipper->email]) }}"
                            class="btn btn-secondary rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-2 shrink-0">
                            <span>Lihat Semua Mutasi</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
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
                                        Keterangan Transaksi</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-right font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Saldo Awal</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-right font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Nominal</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-right font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Saldo Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                                @forelse ($clipper->walletTransactions as $tx)
                                    @php
                                        $isCredit = $tx->type === 'credit';
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 td-nowrap">
                                            <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
                                                {{ $tx->created_at ? $tx->created_at->translatedFormat('d M Y') : '-' }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">
                                                {{ $tx->created_at ? $tx->created_at->translatedFormat('H:i') . ' WIB' : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                                            {{ $tx->notes ?: 'Tidak ada keterangan transaksi.' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 text-right font-medium text-slate-600 dark:text-slate-300 td-nowrap">
                                            Rp {{ number_format($tx->balance_before, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right td-nowrap">
                                            <span
                                                class="inline-flex items-center gap-1.5 font-semibold {{ $isCredit ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                                <i
                                                    class="fa-solid {{ $isCredit ? 'fa-circle-plus' : 'fa-circle-minus' }} text-xs"></i>
                                                <span>{{ $isCredit ? '+' : '-' }}Rp
                                                    {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 text-right font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                            Rp {{ number_format($tx->balance_after, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                            Belum ada riwayat transaksi mutasi saldo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Panel 4: Riwayat Submisi Clip Terbaru -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors">
                    <div
                        class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                <i class="fa-solid fa-video text-brand-600 dark:text-brand-400"></i>
                                Riwayat Pengajuan Clip
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Video clip yang diajukan oleh {{ $clipper->name }} untuk berbagai campaign aktif
                            </p>
                        </div>
                        <a href="{{ route('admin.clip-submissions.index', ['search' => $clipper->email]) }}"
                            class="btn btn-secondary rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-2 shrink-0">
                            <span>Lihat Semua Submisi ({{ $totalSubmissionsCount }})</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
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
                                        Campaign</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Link Video TikTok</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-right font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Views Saat Ini</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-right font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Views Valid</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-right font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Komisi</th>
                                    <th
                                        class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                                @forelse ($clipper->clipSubmissions as $sub)
                                    @php
                                        $statusClass = '';
                                        $statusIcon = '';
                                        $statusText = ucfirst($sub->status);

                                        switch ($sub->status) {
                                            case 'pending':
                                                $statusClass =
                                                    'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400';
                                                $statusIcon = 'fa-regular fa-clock';
                                                break;
                                            case 'approved':
                                            case 'active':
                                            case 'completed':
                                                $statusClass =
                                                    'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400';
                                                $statusIcon = 'fa-solid fa-check';
                                                break;
                                            case 'rejected':
                                                $statusClass =
                                                    'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400';
                                                $statusIcon = 'fa-solid fa-xmark';
                                                break;
                                            default:
                                                $statusClass =
                                                    'bg-slate-100 dark:bg-slate-500/10 text-slate-700 dark:text-slate-400';
                                                $statusIcon = 'fa-solid fa-circle-info';
                                                break;
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 td-nowrap">
                                            <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
                                                {{ $sub->created_at ? $sub->created_at->translatedFormat('d M Y') : '-' }}
                                            </div>
                                            <div class="text-[11px] text-slate-400 mt-0.5">
                                                {{ $sub->created_at ? $sub->created_at->translatedFormat('H:i') . ' WIB' : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">
                                                    {{ $sub->clipCampaign->title ?? '-' }}
                                                </div>
                                                @if ($sub->clipCampaign && $sub->clipCampaign->status)
                                                    <div class="text-xs text-slate-400 font-normal">
                                                        {{ $sub->clipCampaign->status->label() }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 td-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                @if ($sub->submitted_url)
                                                    <a href="{{ $sub->submitted_url }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-[#161616] dark:hover:bg-[#252525] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#2e2e2e] transition">
                                                        <i class="fa-brands fa-tiktok text-slate-900 dark:text-white"></i> Tonton
                                                    </a>
                                                    <button type="button" onclick="copyLink('{{ $sub->submitted_url }}')"
                                                        class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition cursor-pointer"
                                                        title="Salin Link">
                                                        <i class="fa-regular fa-copy text-xs"></i>
                                                    </button>
                                                @else
                                                    <span class="text-slate-400">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right td-nowrap">
                                            <span
                                                class="inline-flex items-center gap-1.5 font-semibold text-slate-900 dark:text-white">
                                                <i class="fa-regular fa-eye text-xs text-slate-400"></i>
                                                <span>{{ number_format($sub->current_views, 0, ',', '.') }}</span>
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 text-right td-nowrap font-medium text-slate-600 dark:text-slate-300">
                                            <span>{{ number_format($sub->credited_views, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right td-nowrap">
                                            <span
                                                class="font-semibold {{ $sub->total_earned > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                                {{ $sub->total_earned > 0 ? 'Rp ' . number_format($sub->total_earned, 0, ',', '.') : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center td-nowrap">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $statusClass }}">
                                                <i class="{{ $statusIcon }} text-[10px]"></i> {{ $statusText }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                            Belum ada riwayat pengajuan video clip.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Bottom Action Buttons -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-slate-200 dark:border-[#2e2e2e]">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.withdrawals.index', ['search' => $clipper->email]) }}"
                        class="w-full sm:w-auto btn btn-secondary rounded-xl px-5 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Riwayat Withdraw</span>
                    </a>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.clippers.index') }}"
                        class="w-full sm:w-auto btn btn-secondary rounded-xl px-6 py-2.5 text-sm font-semibold text-center">
                        Kembali
                    </a>
                    <a href="{{ route('admin.clippers.edit', $clipper->id) }}"
                        class="w-full sm:w-auto btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Data Clipper</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        function copyAccountText(text) {
            copyTextToClipboard(text, 'Nomor rekening berhasil disalin!');
        }

        function copyLink(text) {
            copyTextToClipboard(text, 'Link video berhasil disalin!');
        }

        function copyTextToClipboard(text, successMessage) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showToastAlert(successMessage);
                }).catch(() => {
                    fallbackCopyText(text, successMessage);
                });
            } else {
                fallbackCopyText(text, successMessage);
            }
        }

        function fallbackCopyText(text, successMessage) {
            const temp = document.createElement('input');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showToastAlert(successMessage);
        }

        function showToastAlert(msg) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: msg,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                alert(msg);
            }
        }
    </script>
@endpush
