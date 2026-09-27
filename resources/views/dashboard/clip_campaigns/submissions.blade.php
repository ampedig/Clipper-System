@extends('dashboard.layouts.app')

@section('title', 'Submisi Campaign - ' . $clip_campaign->title . ' - Masum.xyz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Submisi Campaign
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Detail Campaign',
                    'crumb2_url' => route('admin.clip-campaigns.show', $clip_campaign),
                    'crumb3_label' => 'Submisi',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Campaign Context Card (Banner Khusus Campaign Ini) -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white tracking-tight">
                                {{ $clip_campaign->title }}
                            </h3>
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-xl text-xs font-semibold {{ $clip_campaign->status->badgeClasses() }}">
                                <i class="{{ $clip_campaign->status->iconClass() }} text-[10px]"></i>
                                {{ $clip_campaign->status->label() }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-coins text-amber-500"></i>
                                Komisi: <strong class="text-slate-700 dark:text-slate-300 font-semibold">Rp
                                    {{ number_format($clip_campaign->commission_amount, 0, ',', '.') }} /
                                    {{ number_format($clip_campaign->view_threshold, 0, ',', '.') }} Views</strong>
                            </span>
                            <span class="text-slate-300 dark:text-slate-600 hidden sm:inline">&bull;</span>
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-arrow-up-right-dots text-indigo-500"></i>
                                Maks View: <strong
                                    class="text-slate-700 dark:text-slate-300 font-semibold">{{ $clip_campaign->view_max ? number_format($clip_campaign->view_max, 0, ',', '.') . ' Views' : 'Unlimited' }}</strong>
                            </span>
                            <span class="text-slate-300 dark:text-slate-600 hidden sm:inline">&bull;</span>
                            <span class="inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-users text-purple-500"></i>
                                Batas Kuota: <strong
                                    class="text-slate-700 dark:text-slate-300 font-semibold">{{ $clip_campaign->clipper_limit ? number_format($clip_campaign->clipper_limit, 0, ',', '.') . ' Clipper' : 'Unlimited' }}</strong>
                                (Terisi {{ $stats['approved'] }})
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('admin.clip-campaigns.show', $clip_campaign) }}"
                            class="btn btn-secondary rounded-xl px-4 py-2 text-xs font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali ke Detail</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 4 Stat Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Submisi -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total
                            Submisi</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-film"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($stats['total'], 0, ',', '.') }} Video
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Diajukan oleh clipper
                    </p>
                </div>

                <!-- Menunggu Review (Pending) -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pending</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($stats['pending'], 0, ',', '.') }} Video
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Menunggu persetujuan admin
                    </p>
                </div>

                <!-- Disetujui (Approved) -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Approved</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($stats['approved'], 0, ',', '.') }} Video
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Telah di-ACC &amp; dihitung komisi
                    </p>
                </div>

                <!-- Total Komisi -->
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span
                            class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total
                            Komisi</span>
                        <div
                            class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($stats['total_commission'], 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Akumulasi total earned clipper
                    </p>
                </div>
            </div>

            <!-- Data Table Container Card -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors">

                <!-- Header Table Controls -->
                <div
                    class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    <!-- Left: Show Entries (Select2) -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Show</span>
                        <select id="entriesSelect" class="select2-show-entries w-24">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <!-- Right: Status Filter (Select2) -->
                    <div class="flex items-center gap-3">
                        <select id="statusFilter" class="select2-filter-status">
                            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Main Data Table -->
                <div class="overflow-x-auto">
                    <table id="submissionTable" class="w-full text-left border-collapse">
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
                                    Clipper</th>
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
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="submissionTableBody" class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse ($submissions as $submission)
                                @php
                                    $statusClass = '';
                                    $statusIcon = '';
                                    $statusText = ucfirst($submission->status);

                                    switch ($submission->status) {
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

                                    $displayUrl = preg_replace('#^https?://#', '', $submission->submitted_url);
                                    $displayUrl = Str::limit($displayUrl, 25);
                                    $clipperName = $submission->user->name ?? 'Unknown User';
                                    $clipperEmail = $submission->user->email ?? '-';
                                @endphp
                                <tr id="submission-row-{{ $submission->id }}"
                                    class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors submission-row">
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs td-nowrap">
                                        {{ $submission->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        <div>
                                            <div class="font-semibold text-slate-800 dark:text-slate-200 clipper-name">
                                                {{ $clipperName }}</div>
                                            <div class="text-xs text-slate-400 font-normal clipper-handle">
                                                {{ $clipperEmail }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 td-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ $submission->submitted_url }}" target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-[#161616] dark:hover:bg-[#252525] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#2e2e2e] transition">
                                                <i class="fa-brands fa-tiktok text-slate-900 dark:text-white"></i> Tonton
                                            </a>
                                            <button type="button" onclick="copyLink('{{ $submission->submitted_url }}')"
                                                class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition"
                                                title="Salin Link">
                                                <i class="fa-regular fa-copy text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right td-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 font-semibold text-slate-900 dark:text-white">
                                            <i class="fa-regular fa-eye text-xs text-slate-400"></i> <span
                                                class="views-num">{{ number_format($submission->current_views, 0, ',', '.') }}</span>
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 text-right td-nowrap font-medium text-slate-600 dark:text-slate-300">
                                        <span
                                            class="credited-views-val">{{ number_format($submission->credited_views, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right td-nowrap">
                                        <span
                                            class="font-semibold total-earned-val {{ $submission->total_earned > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                            {{ $submission->total_earned > 0 ? 'Rp ' . number_format($submission->total_earned, 0, ',', '.') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $statusClass }}">
                                            <i class="{{ $statusIcon }} text-[10px]"></i> {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @php
                                                $modalData = [
                                                    'clipperName' => $clipperName,
                                                    'clipperHandle' => $clipperEmail,
                                                    'clipTitle' => $clip_campaign->title ?? '-',
                                                    'views' =>
                                                        number_format($submission->current_views, 0, ',', '.') .
                                                        ' Views',
                                                    'pendapatan' =>
                                                        'Rp ' . number_format($submission->total_earned, 0, ',', '.'),
                                                    'submittedAt' => $submission->submitted_at
                                                        ? $submission->submitted_at->translatedFormat('d M Y, H:i') .
                                                            ' WIB'
                                                        : ($submission->created_at
                                                            ? $submission->created_at->translatedFormat('d M Y, H:i') .
                                                                ' WIB'
                                                            : '-'),
                                                    'approvedAt' => $submission->approved_at
                                                        ? $submission->approved_at->translatedFormat('d M Y, H:i') .
                                                            ' WIB'
                                                        : null,
                                                    'rejectedAt' => $submission->rejected_at
                                                        ? $submission->rejected_at->translatedFormat('d M Y, H:i') .
                                                            ' WIB'
                                                        : null,
                                                    'rejectedReason' => $submission->rejection_reason,
                                                    'videoUrl' => $submission->submitted_url,
                                                    'badgeClass' => $statusClass,
                                                    'badgeHtml' =>
                                                        '<i class="' .
                                                        $statusIcon .
                                                        ' text-[10px]"></i> ' .
                                                        $statusText,
                                                    'id' => $submission->id,
                                                    'status' => $submission->status,
                                                    'checkViewsUrl' => route(
                                                        'admin.clip-submissions.check-views',
                                                        $submission,
                                                    ),
                                                ];
                                            @endphp
                                            <button type="button"
                                                class="btn btn-secondary btn-icon btn-detail-submission"
                                                title="Detail Pengajuan" data-submission="{{ json_encode($modalData) }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            @if ($submission->status === 'active')
                                                <button type="button"
                                                    class="btn btn-icon bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 dark:text-indigo-400 btn-check-views"
                                                    title="Cek Views & Hitung Komisi"
                                                    data-url="{{ route('admin.clip-submissions.check-views', $submission) }}"
                                                    data-id="{{ $submission->id }}"
                                                    data-title="{{ $clip_campaign->title ?? '' }}"
                                                    data-clipper="{{ $clipperName }}">
                                                    <i class="fa-solid fa-arrows-rotate"></i>
                                                </button>
                                            @endif
                                            @if (!in_array($submission->status, ['active', 'approved', 'completed']))
                                                <button type="button" class="btn btn-primary btn-icon"
                                                    title="Setujui Pengajuan"
                                                    onclick="confirmApprove('{{ route('admin.clip-submissions.update-status', $submission) }}', '{{ addslashes($clipperName) }}')">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-danger btn-icon"
                                                title="Tolak Pengajuan"
                                                onclick="confirmReject('{{ route('admin.clip-submissions.update-status', $submission) }}', '{{ addslashes($clipperName) }}')">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada submisi untuk campaign ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $submissions->links('dashboard.components.pagination') }}
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Detail Pengajuan -->
    <div id="modalDetailSubmission"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div
            class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl max-w-lg w-full p-6 space-y-6 transition-all">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-[#2e2e2e]">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-xl bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-film"></i>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">Detail Pengajuan Video</h3>
                </div>
                <button type="button" onclick="closeDetailModal()"
                    class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Info Clipper & Status -->
                <div
                    class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                    <div>
                        <p id="modalClipperName" class="font-semibold text-slate-900 dark:text-white text-sm">-</p>
                        <p id="modalClipperHandle" class="text-xs text-slate-400 font-normal">-</p>
                    </div>
                    <span id="modalStatusBadge"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400">
                        <i class="fa-solid fa-clock text-[10px]"></i> Pending
                    </span>
                </div>

                <!-- Detail Metadata -->
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div
                        class="p-3 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                        <span class="text-slate-400 block mb-1">Total Views</span>
                        <p id="modalViewsCount" class="text-sm font-semibold text-slate-800 dark:text-slate-200">0 Views
                        </p>
                    </div>

                    <div
                        class="p-3 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e]">
                        <span class="text-slate-400 block mb-1">Pendapatan</span>
                        <p id="modalPendapatan" class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">Rp 0
                        </p>
                    </div>
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Judul
                        Clip Campaign</label>
                    <p id="modalClipTitle"
                        class="text-sm font-medium text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-[#161616] p-3 rounded-xl border border-slate-100 dark:border-[#2e2e2e]">
                        -
                    </p>
                </div>

                <div>
                    <label
                        class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tautan
                        Video TikTok</label>
                    <a id="modalVideoLink" href="#" target="_blank"
                        class="text-xs font-medium text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-2 truncate bg-slate-50 dark:bg-[#161616] p-3 rounded-xl border border-slate-100 dark:border-[#2e2e2e]">
                        <i class="fa-brands fa-tiktok text-sm"></i> #
                    </a>
                </div>

                <!-- Riwayat Waktu (submitted_at, approved_at, rejected_at) -->
                <div
                    class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e] space-y-2 text-xs">
                    <!-- submitted_at -->
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                            <i class="fa-regular fa-paper-plane text-[11px] text-slate-400"></i> Diajukan (Submitted)
                        </span>
                        <span id="modalSubmittedAt" class="font-semibold text-slate-700 dark:text-slate-300">-</span>
                    </div>

                    <!-- approved_at -->
                    <div id="modalApprovedRow"
                        class="hidden flex items-center justify-between border-t border-slate-100 dark:border-[#242424] pt-2">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                            <i class="fa-regular fa-circle-check text-[11px] text-emerald-500"></i> Disetujui (Approved)
                        </span>
                        <span id="modalApprovedAt" class="font-semibold text-emerald-600 dark:text-emerald-400">-</span>
                    </div>

                    <!-- rejected_at -->
                    <div id="modalRejectedRow"
                        class="hidden flex items-center justify-between border-t border-slate-100 dark:border-[#242424] pt-2">
                        <span class="text-slate-500 dark:text-slate-400 flex items-center gap-1.5 font-medium">
                            <i class="fa-regular fa-circle-xmark text-[11px] text-rose-500"></i> Ditolak (Rejected)
                        </span>
                        <span id="modalRejectedAt" class="font-semibold text-rose-600 dark:text-rose-400">-</span>
                    </div>
                </div>

                <!-- rejected_reason -->
                <div id="modalRejectedReasonBox" class="hidden">
                    <label
                        class="block text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation text-xs"></i> Alasan Penolakan
                    </label>
                    <div
                        class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/40 text-xs text-rose-700 dark:text-rose-300 leading-relaxed font-normal">
                        <p id="modalRejectedReason">-</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between gap-3">
                <button type="button" id="modalBtnCheckViews"
                    class="hidden btn btn-primary rounded-xl px-4 py-2.5 text-xs font-semibold items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate"></i> Cek Views Sekarang
                </button>
                <button type="button" onclick="closeDetailModal()"
                    class="btn btn-secondary rounded-xl px-5 py-2.5 text-sm font-semibold ml-auto">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/clip-submission.page.js') }}"></script>

    <script>
        const csrfToken = "{{ csrf_token() }}";
        let activeModalSubmission = null;

        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2-show-entries, .select2-filter-status').on('change', function() {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', $('#entriesSelect').val());
                    url.searchParams.set('status', $('#statusFilter').val());
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });
            }

            // Override openDetailModal untuk menangani tombol Cek Views di dalam modal
            const nativeOpenDetailModal = window.openDetailModal;
            window.openDetailModal = function(data) {
                activeModalSubmission = data;
                if (typeof nativeOpenDetailModal === 'function') {
                    nativeOpenDetailModal(data);
                }
                const $checkBtn = $('#modalBtnCheckViews');
                if ($checkBtn.length) {
                    if (data && (data.status === 'active' || data.status === 'approved')) {
                        $checkBtn.removeClass('hidden').addClass('inline-flex');
                    } else {
                        $checkBtn.addClass('hidden').removeClass('inline-flex');
                    }
                }
            };

            // Tombol Cek Views di dalam modal
            $('#modalBtnCheckViews').on('click', function(e) {
                e.preventDefault();
                if (!activeModalSubmission) return;
                handleCheckViewsAction(
                    activeModalSubmission.checkViewsUrl,
                    activeModalSubmission.id,
                    activeModalSubmission.clipTitle,
                    activeModalSubmission.clipperName
                );
            });

            // Action button delegation for Detail Submission
            $(document).on('click', '.btn-detail-submission', function(e) {
                e.preventDefault();
                let data = $(this).data('submission');
                if (typeof data === 'string') {
                    try {
                        data = JSON.parse(data);
                    } catch (err) {
                        console.error('Failed to parse submission data', err);
                    }
                }
                if (!data) {
                    let raw = $(this).attr('data-submission');
                    if (raw) {
                        try {
                            data = JSON.parse(raw);
                        } catch (err) {
                            console.error('Failed to parse raw data-submission', err);
                        }
                    }
                }
                if (typeof openDetailModal === 'function') {
                    openDetailModal(data || {});
                }
            });

            // Action button delegation for Check Views from table row
            $(document).on('click', '.btn-check-views', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let id = $(this).data('id');
                let title = $(this).data('title');
                let clipper = $(this).data('clipper');
                handleCheckViewsAction(url, id, title, clipper);
            });
        });

        function handleCheckViewsAction(url, subId, clipTitle, clipperName) {
            if (typeof Swal === 'undefined') return;

            Swal.fire({
                title: "Memeriksa Views...",
                html: `Mengambil data views terbaru dari TikTok untuk <strong>"${clipTitle}"</strong>...`,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    popup: "rounded-2xl dark:bg-[#222222] dark:text-white border border-slate-200 dark:border-[#2e2e2e]"
                }
            });

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(async (response) => {
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Gagal memeriksa views.');
                    }
                    return data;
                })
                .then((res) => {
                    // Update tampilan tabel baris submission
                    const $row = $(`#submission-row-${subId}`);
                    if ($row.length) {
                        $row.find('.views-num').text(res.data.current_views_formatted);
                        $row.find('.credited-views-val').text(res.data.credited_views_formatted);
                        $row.find('.total-earned-val').text(res.data.total_earned_formatted);
                        if (res.data.total_earned > 0) {
                            $row.find('.total-earned-val').removeClass('text-slate-400').addClass(
                                'text-emerald-600 dark:text-emerald-400');
                        }
                    }

                    // Update tampilan modal jika sedang dibuka
                    $('#modalViewsCount').text(`${res.data.current_views_formatted} Views`);
                    $('#modalPendapatan').text(res.data.total_earned_formatted);

                    if (res.data.earned_now > 0) {
                        Swal.fire({
                            icon: "success",
                            title: "Komisi Baru Dicairkan! 🎉",
                            html: `
                            <div class="mt-2 text-left text-xs bg-slate-50 dark:bg-[#161616] p-3.5 rounded-xl border border-slate-200 dark:border-[#2e2e2e] space-y-2">
                                <div class="flex justify-between items-center text-slate-600 dark:text-slate-300">
                                    <span>Views Saat Ini:</span>
                                    <strong class="text-slate-900 dark:text-white">${res.data.current_views_formatted} views</strong>
                                </div>
                                <div class="flex justify-between items-center text-slate-600 dark:text-slate-300">
                                    <span>Views Terhitung Komisi:</span>
                                    <strong class="text-indigo-600 dark:text-indigo-400">+${res.data.delta_views_formatted} views</strong>
                                </div>
                                <div class="flex justify-between items-center text-slate-600 dark:text-slate-300 border-t border-slate-200 dark:border-[#2e2e2e] pt-2">
                                    <span>Komisi Masuk Saldo:</span>
                                    <strong class="text-emerald-600 dark:text-emerald-400 font-bold">+${res.data.earned_now_formatted}</strong>
                                </div>
                                <div class="flex justify-between items-center text-slate-600 dark:text-slate-300">
                                    <span>Total Komisi Klip Ini:</span>
                                    <strong class="text-slate-900 dark:text-white font-bold">${res.data.total_earned_formatted}</strong>
                                </div>
                            </div>
                        `,
                            confirmButtonText: "Selesai",
                            customClass: {
                                popup: "rounded-2xl dark:bg-[#222222] dark:text-white border border-slate-200 dark:border-[#2e2e2e]",
                                confirmButton: "btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm"
                            },
                            buttonsStyling: false
                        });
                    } else {
                        Swal.fire({
                            icon: "info",
                            title: "Views Diperbarui",
                            html: `Views TikTok saat ini: <strong>${res.data.current_views_formatted} views</strong>.<br><span class="text-xs text-slate-400 mt-1 block">Belum mencapai kelipatan threshold baru untuk pencairan komisi.</span>`,
                            confirmButtonText: "OK",
                            customClass: {
                                popup: "rounded-2xl dark:bg-[#222222] dark:text-white border border-slate-200 dark:border-[#2e2e2e]",
                                confirmButton: "btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm"
                            },
                            buttonsStyling: false
                        });
                    }
                })
                .catch((err) => {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Cek Views",
                        text: err.message || "Terjadi kesalahan saat memeriksa views dari TikTok.",
                        confirmButtonText: "Tutup",
                        customClass: {
                            popup: "rounded-2xl dark:bg-[#222222] dark:text-white border border-slate-200 dark:border-[#2e2e2e]",
                            confirmButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                        },
                        buttonsStyling: false
                    });
                });
        }

        function copyLink(url) {
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Link berhasil disalin!',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        }

        // Action Approval
        function confirmApprove(submitUrl, clipperName) {
            Swal.fire({
                title: 'Setujui Pengajuan?',
                html: `Anda akan menyetujui submisi video dari <strong>${clipperName}</strong>.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                    confirmButton: "btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm ml-2",
                    cancelButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    submitStatusForm(submitUrl, 'active');
                }
            });
        }

        // Action Rejection
        function confirmReject(submitUrl, clipperName) {
            Swal.fire({
                title: 'Tolak Pengajuan',
                html: `Masukkan alasan penolakan untuk <strong>${clipperName}</strong>:`,
                input: 'textarea',
                inputPlaceholder: 'Tuliskan alasan spesifik...',
                showCancelButton: true,
                confirmButtonText: 'Tolak Submisi',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                    input: "!w-full !max-w-full !box-border !mx-0 !mt-3 p-3 text-sm bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-slate-800 dark:text-slate-200",
                    confirmButton: "btn btn-danger rounded-xl px-5 py-2.5 font-semibold text-sm ml-2",
                    cancelButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                },
                buttonsStyling: false,
                preConfirm: (reason) => {
                    if (!reason || !reason.trim()) {
                        Swal.showValidationMessage("Alasan penolakan wajib diisi!");
                        return false;
                    }
                    return reason.trim();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    submitStatusForm(submitUrl, 'rejected', result.value);
                }
            });
        }

        function submitStatusForm(url, status, reason = '') {
            let form = document.createElement('form');
            form.action = url;
            form.method = 'POST';

            form.innerHTML = `
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="${status}">
                <input type="hidden" name="rejection_reason" value="${reason.replace(/"/g, '&quot;')}">
            `;

            document.body.appendChild(form);
            form.submit();
        }

        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>
@endpush
