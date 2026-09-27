@extends('dashboard.layouts.app')

@section('title', 'Penarikan Dana (Withdraw) - ' . config('app.name'))
@section('description', 'Daftar riwayat dan status pengajuan penarikan dana komisi clipper')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Penarikan Dana (Withdraw)
                </h2>

                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Komisi',
                    'crumb2_url' => '',
                    'crumb3_label' => 'Withdraw',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Table Card -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">
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
                        <select id="statusFilterSelect" class="select2-filter-status w-40">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>
                </div>

                @if (request('search'))
                    <div
                        class="px-5 py-2.5 bg-brand-50/50 dark:bg-brand-950/20 border-b border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-300">
                            Menampilkan hasil pencarian untuk: <strong
                                class="text-brand-600 dark:text-brand-400 font-semibold">"{{ request('search') }}"</strong>
                        </span>
                        <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}"
                            class="text-rose-500 hover:text-rose-600 dark:text-rose-400 font-medium inline-flex items-center gap-1.5 hover:underline">
                            <i class="fa-solid fa-xmark"></i> Hapus Filter
                        </a>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 dark:bg-[#1c1c1c] text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">#
                                </th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Tanggal</th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Clipper</th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Tujuan</th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Nominal</th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Fee</th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Diterima</th>
                                <th class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] whitespace-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center whitespace-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse ($withdrawals as $withdrawal)
                                @php
                                    $badgeClass = match ($withdrawal->status) {
                                        'completed'
                                            => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400',
                                        'rejected'
                                            => 'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400',
                                        'processing'
                                            => 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
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
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $loop->iteration + ($withdrawals->currentPage() - 1) * $withdrawals->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
                                            {{ $withdrawal->created_at->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $withdrawal->created_at->translatedFormat('H:i') }} WIB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">
                                            {{ $withdrawal->user->name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">{{ $withdrawal->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">
                                            {{ $withdrawal->bank_name }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                            {{ $withdrawal->account_number }} - A.N {{ $withdrawal->account_name }}</div>
                                    </td>
                                    <td
                                        class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 whitespace-nowrap">
                                        {{ $withdrawal->fee > 0 ? 'Rp ' . number_format($withdrawal->fee, 0, ',', '.') : 'Gratis' }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                                        Rp {{ number_format($withdrawal->net_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $badgeClass }}">
                                            <i class="fa-solid {{ $badgeIcon }} text-[10px]"></i> {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <a href="{{ route('admin.withdrawals.show', $withdrawal) }}"
                                            class="btn btn-primary btn-icon" title="Lihat Detail Penarikan">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        @if (request('search'))
                                            Tidak ada riwayat penarikan dana yang cocok dengan pencarian "{{ request('search') }}".
                                        @else
                                            Belum ada riwayat penarikan dana.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $withdrawals->links('dashboard.components.pagination') }}
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined' && $.fn.select2) {
                $('.select2-show-entries').select2({
                    minimumResultsForSearch: Infinity,
                    width: 'style'
                });

                $('.select2-filter-status').select2({
                    minimumResultsForSearch: Infinity,
                    width: 'style'
                });

                $('#entriesSelect').on('change', function() {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', $(this).val());
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });

                $('#statusFilterSelect').on('change', function() {
                    let val = $(this).val();
                    let url = new URL(window.location.href);
                    if (val === 'all') {
                        url.searchParams.delete('status');
                    } else {
                        url.searchParams.set('status', val);
                    }
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });
            }

        });
    </script>
@endpush
