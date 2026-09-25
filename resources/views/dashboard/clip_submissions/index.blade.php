@extends('dashboard.layouts.app')

@section('title', 'Pengajuan Clip - Admin Clipper')

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
                    Pengajuan Clip
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Clip',
                    'crumb2_url' => route('admin.clip-campaigns.index'),
                    'crumb3_label' => 'Pengajuan',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Ringkasan Statistik Singkat -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 flex items-center gap-4 transition-colors">
                    <div
                        class="w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-film"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Pengajuan</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white mt-0.5">
                            {{ number_format($stats['total'], 0, ',', '.') }} Video</p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 flex items-center gap-4 transition-colors">
                    <div
                        class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu Review</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white mt-0.5">
                            {{ number_format($stats['pending'], 0, ',', '.') }} Submission</p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 flex items-center gap-4 transition-colors">
                    <div
                        class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tayang Aktif</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white mt-0.5">
                            {{ number_format($stats['active'], 0, ',', '.') }} Clip</p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 flex items-center gap-4 transition-colors">
                    <div
                        class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Komisi</p>
                        <p class="text-lg font-semibold text-slate-900 dark:text-white mt-0.5">Rp
                            {{ number_format($stats['total_commission'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Table Container Card -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">

                <!-- Header Table Controls -->
                <div
                    class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Show</span>
                        <select class="select2-show-entries w-24">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Filter Status -->
                        <select id="statusFilterSelect" class="select2-filter-status">
                            <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status
                            </option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
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
                    <table id="submissionTable" class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 dark:bg-[#1c1c1c] text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    #</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Clipper</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Clip</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Views</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Pendapatan</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Video</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse ($submissions as $sub)
                                @php
                                    $badgeConfig = [
                                        'pending' => [
                                            'class' =>
                                                'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                            'icon' => 'fa-solid fa-clock text-[10px]',
                                            'label' => 'Pending',
                                        ],
                                        'approved' => [
                                            'class' =>
                                                'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                            'icon' => 'fa-solid fa-check-double text-[10px]',
                                            'label' => 'Approved',
                                        ],
                                        'active' => [
                                            'class' =>
                                                'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                            'icon' => 'fa-solid fa-circle-check text-[10px]',
                                            'label' => 'Active',
                                        ],
                                        'completed' => [
                                            'class' =>
                                                'bg-purple-100 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400',
                                            'icon' => 'fa-solid fa-flag-checkered text-[10px]',
                                            'label' => 'Completed',
                                        ],
                                        'rejected' => [
                                            'class' =>
                                                'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400',
                                            'icon' => 'fa-solid fa-circle-xmark text-[10px]',
                                            'label' => 'Rejected',
                                        ],
                                    ];
                                    $statusData = $badgeConfig[$sub->status] ?? [
                                        'class' =>
                                            'bg-slate-100 dark:bg-slate-500/10 text-slate-700 dark:text-slate-400',
                                        'icon' => 'fa-solid fa-info-circle text-[10px]',
                                        'label' => ucfirst($sub->status),
                                    ];

                                    $modalData = [
                                        'clipperName' => $sub->user->name ?? 'User',
                                        'clipperHandle' => $sub->user->email ?? '',
                                        'clipTitle' => $sub->clipCampaign->title ?? '-',
                                        'views' => number_format($sub->current_views, 0, ',', '.') . ' Views',
                                        'pendapatan' => 'Rp ' . number_format($sub->total_earned, 0, ',', '.'),
                                        'submittedAt' => $sub->submitted_at
                                            ? $sub->submitted_at->translatedFormat('d M Y, H:i') . ' WIB'
                                            : '-',
                                        'approvedAt' => $sub->approved_at
                                            ? $sub->approved_at->translatedFormat('d M Y, H:i') . ' WIB'
                                            : null,
                                        'rejectedAt' => $sub->rejected_at
                                            ? $sub->rejected_at->translatedFormat('d M Y, H:i') . ' WIB'
                                            : null,
                                        'rejectedReason' => $sub->rejection_reason,
                                        'videoUrl' => $sub->submitted_url,
                                        'badgeClass' => $statusData['class'],
                                        'badgeHtml' =>
                                            '<i class="' . $statusData['icon'] . '"></i> ' . $statusData['label'],
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors"
                                    data-status="{{ $sub->status }}">
                                    <!-- NO -->
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}
                                    </td>

                                    <!-- CLIPPER -->
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        <div>
                                            <p class="font-semibold text-slate-800 dark:text-slate-200">
                                                {{ $sub->user->name ?? 'User' }}</p>
                                            <p class="text-xs text-slate-400 font-normal">{{ $sub->user->email ?? '' }}</p>
                                        </div>
                                    </td>

                                    <!-- CLIP -->
                                    <td class="px-6 py-4 td-nowrap">
                                        <p class="font-medium text-slate-800 dark:text-slate-200 max-w-[220px] truncate"
                                            title="{{ $sub->clipCampaign->title ?? '-' }}">
                                            {{ $sub->clipCampaign->title ?? '-' }}
                                        </p>
                                        <span class="text-xs text-slate-400">
                                            Komisi Rp
                                            {{ number_format($sub->clipCampaign->commission_amount ?? 0, 0, ',', '.') }} /
                                            {{ number_format($sub->clipCampaign->view_threshold ?? 0, 0, ',', '.') }} Views
                                        </span>
                                    </td>

                                    <!-- VIEWS -->
                                    <td class="px-6 py-4 td-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 font-semibold text-slate-900 dark:text-white">
                                            <i class="fa-regular fa-eye text-xs text-slate-400"></i>
                                            {{ number_format($sub->current_views, 0, ',', '.') }}
                                        </span>
                                        <span class="block text-[11px] text-slate-400 font-normal">
                                            Credited: {{ number_format($sub->credited_views, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <!-- PENDAPATAN -->
                                    <td class="px-6 py-4 td-nowrap">
                                        <span
                                            class="font-semibold {{ $sub->total_earned > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                            Rp {{ number_format($sub->total_earned, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <!-- VIDEO -->
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <a href="{{ $sub->submitted_url }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-[#161616] dark:hover:bg-[#252525] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#2e2e2e] transition">
                                            <i class="fa-brands fa-tiktok text-slate-900 dark:text-white"></i> Tonton
                                        </a>
                                    </td>

                                    <!-- STATUS -->
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $statusData['class'] }}">
                                            <i class="{{ $statusData['icon'] }}"></i> {{ $statusData['label'] }}
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Detail Button -->
                                            <button type="button" class="btn btn-secondary btn-icon btn-detail-submission"
                                                title="Detail Pengajuan" data-submission="{{ json_encode($modalData) }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>

                                            <!-- Approve Button (jika bukan active/approved) -->
                                            @if ($sub->status !== 'active')
                                                <button type="button"
                                                    class="btn btn-primary btn-icon btn-approve-submission"
                                                    title="Setujui Pengajuan"
                                                    data-url="{{ route('admin.clip-submissions.update-status', $sub) }}"
                                                    data-title="{{ $sub->clipCampaign->title ?? '' }}"
                                                    data-clipper="{{ $sub->user->name ?? '' }}">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            @endif

                                            <!-- Reject Button (jika bukan rejected) -->
                                            @if ($sub->status !== 'rejected')
                                                <button type="button"
                                                    class="btn btn-danger btn-icon btn-reject-submission"
                                                    title="Tolak Pengajuan"
                                                    data-url="{{ route('admin.clip-submissions.update-status', $sub) }}"
                                                    data-title="{{ $sub->clipCampaign->title ?? '' }}"
                                                    data-clipper="{{ $sub->user->name ?? '' }}">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            <button type="button"
                                                class="btn btn-secondary btn-icon hover:!text-rose-500 hover:!bg-rose-50 dark:hover:!bg-rose-950/20 btn-delete-submission"
                                                title="Hapus Pengajuan"
                                                data-url="{{ route('admin.clip-submissions.destroy', $sub) }}"
                                                data-title="{{ $sub->clipCampaign->title ?? '' }}">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fa-solid fa-film text-3xl text-slate-300 dark:text-slate-600"></i>
                                            <p class="font-medium text-sm">Belum ada pengajuan video yang sesuai kriteria.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
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
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/clip-submission.page.js') }}"></script>
    <script>
        const csrfToken = "{{ csrf_token() }}";

        @if (session('success'))
            if (typeof Swal !== 'undefined') {
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
            }
        @endif

        function submitStatusForm(url, status, reason = null) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            let html = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="status" value="${status}">
            `;
            if (reason) {
                html += `<input type="hidden" name="rejection_reason" value="${reason}">`;
            }
            form.innerHTML = html;
            document.body.appendChild(form);
            form.submit();
        }

        function confirmApproveAction(url, clipTitle, clipperName) {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                title: "Setujui Pengajuan?",
                html: `Apakah Anda yakin ingin menyetujui clip <strong>"${clipTitle}"</strong> dari <strong>${clipperName}</strong>? Status akan diubah menjadi aktif.`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-check mr-1.5"></i> Ya, Setujui',
                cancelButtonText: "Batal",
                reverseButtons: true,
                customClass: {
                    popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                    confirmButton: "btn btn-primary rounded-xl px-5 py-2.5 font-semibold text-sm ml-2",
                    cancelButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    submitStatusForm(url, 'active');
                }
            });
        }

        function confirmRejectAction(url, clipTitle, clipperName) {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                title: "Tolak Pengajuan Clip",
                text: `Berikan alasan penolakan untuk pengajuan "${clipTitle}" oleh ${clipperName}:`,
                input: "textarea",
                inputPlaceholder: "Contoh: Video tidak mencantumkan hashtag wajib, watermark kompetitor masih ada, durasi video terlalu pendek...",
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-xmark mr-1.5"></i> Tolak Pengajuan',
                cancelButtonText: "Batal",
                reverseButtons: true,
                inputAttributes: {
                    rows: "4",
                    class: "!w-full !box-border px-4 py-3 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 text-slate-800 dark:text-slate-200 mt-3 min-h-[120px] resize-y placeholder:text-slate-400"
                },
                customClass: {
                    popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                    input: "!w-full !max-w-full !box-border !mx-0",
                    confirmButton: "btn btn-danger rounded-xl px-5 py-2.5 font-semibold text-sm ml-2",
                    cancelButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                },
                buttonsStyling: false,
                preConfirm: (value) => {
                    if (!value || !value.trim()) {
                        Swal.showValidationMessage("Alasan penolakan wajib diisi!");
                        return false;
                    }
                    return value.trim();
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    submitStatusForm(url, 'rejected', result.value);
                }
            });
        }

        function confirmDeleteAction(url, clipTitle) {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                title: "Hapus Pengajuan?",
                html: `Apakah Anda yakin ingin menghapus data pengajuan <strong>"${clipTitle}"</strong>? Data tidak dapat dikembalikan!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true,
                customClass: {
                    popup: "rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]",
                    confirmButton: "btn btn-danger rounded-xl px-5 py-2.5 font-semibold text-sm ml-2",
                    cancelButton: "btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        $(document).ready(function() {
            // Action button delegations
            $(document).on('click', '.btn-detail-submission', function(e) {
                e.preventDefault();
                let data = $(this).data('submission');
                if (typeof openDetailModal === 'function') {
                    openDetailModal(data);
                }
            });

            $(document).on('click', '.btn-approve-submission', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let title = $(this).data('title');
                let clipper = $(this).data('clipper');
                confirmApproveAction(url, title, clipper);
            });

            $(document).on('click', '.btn-reject-submission', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let title = $(this).data('title');
                let clipper = $(this).data('clipper');
                confirmRejectAction(url, title, clipper);
            });

            $(document).on('click', '.btn-delete-submission', function(e) {
                e.preventDefault();
                let url = $(this).data('url');
                let title = $(this).data('title');
                confirmDeleteAction(url, title);
            });

            if ($.fn.select2) {
                $('.select2-show-entries').on('change', function() {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', $(this).val());
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });

                $('#statusFilterSelect').on('change', function() {
                    let url = new URL(window.location.href);
                    let val = $(this).val();
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
