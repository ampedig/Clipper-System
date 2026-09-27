@extends('dashboard.layouts.app')

@section('title', 'Detail Campaign - ' . $clip_campaign->title . ' - Masum.xyz')

@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        /* Scoped Rich Text Content Display for Campaign Brief */
        .brief-content {
            font-size: 0.875rem;
            line-height: 1.625;
            color: #475569;
        }
        .dark .brief-content {
            color: #cbd5e1;
        }
        .brief-content > *:first-child {
            margin-top: 0 !important;
        }
        .brief-content > *:last-child {
            margin-bottom: 0 !important;
        }
        .brief-content p {
            margin-bottom: 0.625rem;
        }
        .brief-content h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .dark .brief-content h1 {
            color: #f8fafc;
        }
        .brief-content h2 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 1rem;
            margin-bottom: 0.375rem;
            letter-spacing: -0.01em;
        }
        .dark .brief-content h2 {
            color: #f8fafc;
        }
        .brief-content h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0.875rem;
            margin-bottom: 0.25rem;
        }
        .dark .brief-content h3 {
            color: #f8fafc;
        }
        .brief-content h4, .brief-content h5, .brief-content h6 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            margin-top: 0.75rem;
            margin-bottom: 0.25rem;
        }
        .dark .brief-content h4, .dark .brief-content h5, .dark .brief-content h6 {
            color: #f8fafc;
        }
        .brief-content strong, .brief-content b {
            font-weight: 600;
            color: #0f172a;
        }
        .dark .brief-content strong, .dark .brief-content b {
            color: #ffffff;
        }
        .brief-content em, .brief-content i {
            font-style: italic;
        }
        .brief-content u {
            text-decoration: underline;
            text-underline-offset: 2px;
        }
        .brief-content s {
            text-decoration: line-through;
            color: #94a3b8;
        }
        .brief-content a {
            color: var(--color-brand-600, #2563eb);
            text-decoration: underline;
            text-underline-offset: 2px;
            font-weight: 500;
        }
        .dark .brief-content a {
            color: var(--color-brand-400, #60a5fa);
        }
        .brief-content a:hover {
            color: var(--color-brand-700, #1d4ed8);
        }
        .dark .brief-content a:hover {
            color: var(--color-brand-300, #93c5fd);
        }
        .brief-content ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 0.375rem;
            margin-bottom: 0.75rem;
        }
        .brief-content ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin-top: 0.375rem;
            margin-bottom: 0.75rem;
        }
        .brief-content li {
            margin-bottom: 0.25rem;
            padding-left: 0.25rem;
        }
        .brief-content li[data-list="bullet"] {
            list-style-type: disc;
            margin-left: 1.25rem;
        }
        .brief-content li[data-list="ordered"] {
            list-style-type: decimal;
            margin-left: 1.25rem;
        }
        .brief-content li > .ql-ui {
            display: none;
        }
        .brief-content .ql-indent-1 {
            padding-left: 1.5rem !important;
        }
        .brief-content .ql-indent-2 {
            padding-left: 3rem !important;
        }
        .brief-content .ql-align-center {
            text-align: center;
        }
        .brief-content .ql-align-right {
            text-align: right;
        }
        .brief-content .ql-align-justify {
            text-align: justify;
        }
        .brief-content .ql-size-small {
            font-size: 0.75rem;
        }
        .brief-content .ql-size-large {
            font-size: 1.125rem;
        }
        .brief-content .ql-size-huge {
            font-size: 1.375rem;
        }
        .brief-content blockquote {
            border-left: 3px solid #cbd5e1;
            padding-left: 0.875rem;
            margin: 0.75rem 0;
            color: #64748b;
            font-style: italic;
            background-color: rgba(248, 250, 252, 0.6);
            border-radius: 0 0.5rem 0.5rem 0;
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
        }
        .dark .brief-content blockquote {
            border-left-color: #475569;
            color: #94a3b8;
            background-color: rgba(255, 255, 255, 0.03);
        }
        .brief-content code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.8125rem;
            padding: 0.125rem 0.375rem;
            border-radius: 0.375rem;
            background-color: #f1f5f9;
            color: #0f172a;
            border: 1px solid #e2e8f0;
        }
        .dark .brief-content code {
            background-color: #1a1a1a;
            color: #f1f5f9;
            border-color: #2e2e2e;
        }
        .brief-content pre {
            background-color: #0f172a;
            color: #f8fafc;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.8125rem;
            overflow-x: auto;
            margin: 0.75rem 0;
            border: 1px solid #1e293b;
        }
        .dark .brief-content pre {
            background-color: #141414;
            border-color: #2e2e2e;
        }
    </style>
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Detail Campaign
                    </h2>
                </div>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'List Clip',
                    'crumb2_url' => route('admin.clip-campaigns.index'),
                    'crumb3_label' => 'Detail',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Campaign Title & Status Header Card -->
            <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <img src="{{ $clip_campaign->thumbnail_url }}" alt="{{ $clip_campaign->title }}"
                            class="w-36 sm:w-44 aspect-video rounded-xl object-cover border border-slate-200 dark:border-[#2e2e2e] shadow-sm shrink-0 bg-slate-100 dark:bg-[#1a1a1a]">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
                                    {{ $clip_campaign->title }}
                                </h3>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xl text-xs font-semibold {{ $clip_campaign->status->badgeClasses() }}">
                                    <i class="{{ $clip_campaign->status->iconClass() }} text-[10px]"></i> {{ $clip_campaign->status->label() }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Dibuat pada {{ $clip_campaign->created_at ? $clip_campaign->created_at->translatedFormat('d M Y, H:i') : '-' }} WIB oleh <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $clip_campaign->creator->name ?? 'Sistem' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('admin.clip-campaigns.edit', $clip_campaign) }}" class="btn btn-primary rounded-xl px-4 py-2.5 text-xs font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Edit Campaign</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5 Top Metric Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
                <!-- Card 1: Komisi -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 sm:p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Komisi per Video</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($clip_campaign->commission_amount, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                        <span>per kelipatan {{ number_format($clip_campaign->view_threshold, 0, ',', '.') }} views</span>
                    </p>
                </div>

                <!-- Card 2: Minimal Views -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 sm:p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Target Views Min</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($clip_campaign->view_threshold, 0, ',', '.') }}
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Syarat minimal klaim komisi
                    </p>
                </div>

                <!-- Card 3: Maks View -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 sm:p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Maks View</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-arrow-up-right-dots"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        {{ $clip_campaign->view_max ? number_format($clip_campaign->view_max, 0, ',', '.') : 'Unlimited' }}
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Batas maksimal views dihitung
                    </p>
                </div>

                <!-- Card 4: Kuota Clipper -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 sm:p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Kuota Clipper</span>
                        <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-users"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <span class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">0</span>
                        <span class="text-xs sm:text-sm font-medium text-slate-400 dark:text-slate-500">/ {{ $clip_campaign->clipper_limit ? number_format($clip_campaign->clipper_limit, 0, ',', '.') : '∞' }} Kuota</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 dark:bg-[#1a1a1a] rounded-full mt-2.5 overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Card 5: Periode & Sisa Waktu -->
                @php
                    $remainingText = '-';
                    if ($clip_campaign->end_at) {
                        if ($clip_campaign->end_at->isPast()) {
                            $remainingText = 'Selesai';
                        } else {
                            $days = (int) now()->diffInDays($clip_campaign->end_at);
                            $remainingText = $days > 0 ? $days . ' Hari' : 'Hari Terakhir';
                        }
                    }
                @endphp
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-4 sm:p-5 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Durasi Campaign</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="text-xl sm:text-2xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        {{ $remainingText }}
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-slate-400"></i>
                        <span>
                            {{ $clip_campaign->start_at ? $clip_campaign->start_at->translatedFormat('d M') : '-' }} - {{ $clip_campaign->end_at ? $clip_campaign->end_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Detail Sections -->
            <div class="space-y-6">

                <!-- Panel 1: Deskripsi & Sumber Materi Video -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 transition-colors">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-[#2e2e2e] pb-3 mb-4">
                        <i class="fa-solid fa-circle-info text-brand-600 dark:text-brand-400"></i>
                        Deskripsi Campaign
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $clip_campaign->description ?: 'Tidak ada deskripsi tambahan untuk campaign ini.' }}
                    </p>

                    <!-- Link Acuan / Sumber Materi Box -->
                    @if ($clip_campaign->source_url)
                        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                Link Acuan / Sumber Materi
                            </label>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 p-3 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl">
                                <div class="w-9 h-9 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-link text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <input type="text"
                                           id="sourceUrlInput"
                                           readonly
                                           value="{{ $clip_campaign->source_url }}"
                                           class="w-full bg-transparent border-none p-0 text-sm text-slate-800 dark:text-slate-200 font-mono focus:ring-0 outline-none truncate cursor-pointer">
                                </div>
                                <div class="flex items-center gap-2 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200 dark:border-[#2e2e2e]">
                                    <button type="button"
                                            id="btnCopySourceUrl"
                                            class="px-3 py-2 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-lg text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-600 dark:hover:text-brand-400 transition flex items-center gap-1.5 cursor-pointer"
                                            title="Salin Link">
                                        <i class="fa-regular fa-copy"></i>
                                        <span>Salin</span>
                                    </button>
                                    <a href="{{ $clip_campaign->source_url }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="px-3 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1.5"
                                       title="Buka Link di Tab Baru">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        <span>Buka Link</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-6 pt-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                            <p class="text-xs text-slate-400 dark:text-slate-500 italic">
                                Tidak ada link acuan / sumber materi eksternal yang dilampirkan.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Panel 2: Brief & Panduan Konten -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-5 sm:p-6 transition-colors">
                    <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2 border-b border-slate-100 dark:border-[#2e2e2e] pb-3 mb-4">
                        <i class="fa-solid fa-file-lines text-brand-600 dark:text-brand-400"></i>
                        Brief & Panduan Konten
                    </h3>
                    @if ($clip_campaign->brief)
                        <div class="brief-content">
                            {!! $clip_campaign->brief !!}
                        </div>
                    @else
                        <p class="text-xs text-slate-400 dark:text-slate-500 italic">
                            Belum ada brief atau panduan konten untuk campaign ini.
                        </p>
                    @endif
                </div>

                <!-- Panel 3: Cuplikan Submisi Clip Terbaru (Dummy Sample) -->
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-2">
                                <i class="fa-solid fa-video text-brand-600 dark:text-brand-400"></i>
                                Submisi Clip Terbaru
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Daftar video yang baru saja diajukan clipper untuk campaign ini
                            </p>
                        </div>
                        <a href="{{ route('admin.clip-campaigns.submissions', $clip_campaign) }}" class="btn btn-secondary rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-2">
                            <span>Lihat Semua ({{ $clip_campaign->clipSubmissions()->count() }})</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="submissionTable" class="w-full text-left border-collapse">
                            <thead
                                class="bg-slate-50 dark:bg-[#1c1c1c] text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold tracking-wider">
                                <tr>
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
                                @forelse ($recent_submissions as $submission)
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
                                                    title="Detail Pengajuan"
                                                    data-submission="{{ json_encode($modalData) }}">
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
                                        <td colspan="7"
                                            class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                            Belum ada submisi clip terbaru untuk campaign ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Bottom Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 border-t border-slate-200 dark:border-[#2e2e2e]">
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="button"
                            id="btnSharePublicLink"
                            class="w-full sm:w-auto btn btn-secondary rounded-xl px-5 py-2.5 text-sm font-semibold flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-share-nodes"></i>
                        <span>Salin Link Detail</span>
                    </button>
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.clip-campaigns.index') }}" class="w-full sm:w-auto btn btn-secondary rounded-xl px-6 py-2.5 text-sm font-semibold text-center">
                        Kembali
                    </a>
                    <a href="{{ route('admin.clip-campaigns.edit', $clip_campaign) }}" class="w-full sm:w-auto btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Campaign</span>
                    </a>
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
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/clip-submission.page.js') }}"></script>

    <script>
        const csrfToken = "{{ csrf_token() }}";
        let activeModalSubmission = null;

        document.addEventListener("DOMContentLoaded", () => {
            initCampaignDetailInteractions();
        });

        $(document).ready(function() {
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
                const $row = $(`#submission-row-${subId}`);
                if ($row.length) {
                    $row.find('.views-num').text(res.data.current_views_formatted);
                    $row.find('.total-earned-val').text(res.data.total_earned_formatted);
                    if (res.data.total_earned > 0) {
                        $row.find('.total-earned-val').removeClass('text-slate-400').addClass('text-emerald-600 dark:text-emerald-400');
                    }
                }

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

        function initCampaignDetailInteractions() {
            const btnCopySource = document.getElementById("btnCopySourceUrl");
            const sourceUrlInput = document.getElementById("sourceUrlInput");
            const btnSharePublic = document.getElementById("btnSharePublicLink");

            // Copy Source URL Link
            if (btnCopySource && sourceUrlInput) {
                btnCopySource.addEventListener("click", () => {
                    copyToClipboard(
                        sourceUrlInput.value,
                        "Link Sumber Materi Berhasil Disalin!"
                    );
                });
            }

            // Copy Public / Detail Link
            if (btnSharePublic) {
                btnSharePublic.addEventListener("click", () => {
                    copyToClipboard(
                        window.location.href,
                        "Link Campaign Berhasil Disalin ke Clipboard!"
                    );
                });
            }
        }

        function copyToClipboard(text, successMessage) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard
                    .writeText(text)
                    .then(() => showToast(successMessage))
                    .catch(() => fallbackCopy(text, successMessage));
            } else {
                fallbackCopy(text, successMessage);
            }
        }

        function fallbackCopy(text, successMessage) {
            const tempInput = document.createElement("input");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            try {
                document.execCommand("copy");
                showToast(successMessage);
            } catch (err) {
                alert("Gagal menyalin link.");
            }
            document.body.removeChild(tempInput);
        }

        function showToast(message) {
            if (typeof Swal !== "undefined") {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    customClass: {
                        popup: "rounded-xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]"
                    }
                });
                Toast.fire({
                    icon: "success",
                    title: message
                });
            } else {
                alert(message);
            }
        }

        @if(session('success'))
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
