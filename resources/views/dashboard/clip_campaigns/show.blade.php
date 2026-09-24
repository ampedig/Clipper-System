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
                    'crumb1_url' => route('dashboard'),
                    'crumb2_label' => 'List Clip',
                    'crumb2_url' => route('clip-campaigns.index'),
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
                        <a href="{{ route('clip-campaigns.edit', $clip_campaign) }}" class="btn btn-primary rounded-xl px-4 py-2.5 text-xs font-semibold flex items-center gap-2">
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
                        <a href="#" class="btn btn-secondary rounded-xl px-3.5 py-2 text-xs font-semibold flex items-center gap-2">
                            <span>Lihat Semua (74)</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-[#2e2e2e] text-slate-400 dark:text-slate-500 uppercase tracking-wider font-semibold">
                                    <th class="py-2.5 px-3">Clipper</th>
                                    <th class="py-2.5 px-3">Link Video TikTok</th>
                                    <th class="py-2.5 px-3 text-right">Views Saat Ini</th>
                                    <th class="py-2.5 px-3 text-right">Komisi</th>
                                    <th class="py-2.5 px-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e]">
                                <!-- Row 1: Approved -->
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-brand-50 dark:bg-brand-500/20 text-brand-600 dark:text-brand-400 flex items-center justify-center font-semibold text-xs">
                                                RP
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">Rizky Pratama</div>
                                                <div class="text-[11px] text-slate-400">@rizkyclips</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="https://vt.tiktok.com/ZS2xY98a1" target="_blank" rel="noopener noreferrer" class="text-brand-600 dark:text-brand-400 hover:underline font-mono">
                                            vt.tiktok.com/ZS2xY98a1
                                        </a>
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-800 dark:text-slate-200">
                                        28.450
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                        Rp 100.000
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                            <i class="fa-solid fa-check text-[9px]"></i> Approved
                                        </span>
                                    </td>
                                </tr>
                                <!-- Row 2: Pending -->
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-purple-50 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center font-semibold text-xs">
                                                DS
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">Dinda Safitri</div>
                                                <div class="text-[11px] text-slate-400">@dindacreator</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="https://vt.tiktok.com/ZS2mK47b2" target="_blank" rel="noopener noreferrer" class="text-brand-600 dark:text-brand-400 hover:underline font-mono">
                                            vt.tiktok.com/ZS2mK47b2
                                        </a>
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-800 dark:text-slate-200">
                                        14.200
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-400 dark:text-slate-500">
                                        -
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400">
                                            <i class="fa-regular fa-clock text-[9px]"></i> Pending
                                        </span>
                                    </td>
                                </tr>
                                <!-- Row 3: Approved -->
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-semibold text-xs">
                                                KS
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">Kevin Sanjaya</div>
                                                <div class="text-[11px] text-slate-400">@kevin.shorts</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="https://vt.tiktok.com/ZS2hK91p8" target="_blank" rel="noopener noreferrer" class="text-brand-600 dark:text-brand-400 hover:underline font-mono">
                                            vt.tiktok.com/ZS2hK91p8
                                        </a>
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-800 dark:text-slate-200">
                                        108.300
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-emerald-600 dark:text-emerald-400">
                                        Rp 500.000
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                            <i class="fa-solid fa-check text-[9px]"></i> Approved
                                        </span>
                                    </td>
                                </tr>
                                <!-- Row 4: Pending -->
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-rose-50 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center font-semibold text-xs">
                                                SN
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">Siti Nurhaliza</div>
                                                <div class="text-[11px] text-slate-400">@siticlips</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="https://vt.tiktok.com/ZS2qM55f4" target="_blank" rel="noopener noreferrer" class="text-brand-600 dark:text-brand-400 hover:underline font-mono">
                                            vt.tiktok.com/ZS2qM55f4
                                        </a>
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-800 dark:text-slate-200">
                                        52.600
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-400 dark:text-slate-500">
                                        -
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400">
                                            <i class="fa-regular fa-clock text-[9px]"></i> Pending
                                        </span>
                                    </td>
                                </tr>
                                <!-- Row 5: Rejected -->
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#1a1a1a] transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-semibold text-xs">
                                                BW
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200">Budi Wicaksono</div>
                                                <div class="text-[11px] text-slate-400">@buditech</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3">
                                        <a href="https://vt.tiktok.com/ZS2pL19c3" target="_blank" rel="noopener noreferrer" class="text-brand-600 dark:text-brand-400 hover:underline font-mono">
                                            vt.tiktok.com/ZS2pL19c3
                                        </a>
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-800 dark:text-slate-200">
                                        6.800
                                    </td>
                                    <td class="py-3 px-3 text-right font-semibold text-slate-400 dark:text-slate-500">
                                        -
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[11px] font-semibold bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400">
                                            <i class="fa-solid fa-xmark text-[9px]"></i> Rejected
                                        </span>
                                    </td>
                                </tr>
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
                    <a href="{{ route('clip-campaigns.index') }}" class="w-full sm:w-auto btn btn-secondary rounded-xl px-6 py-2.5 text-sm font-semibold text-center">
                        Kembali
                    </a>
                    <a href="{{ route('clip-campaigns.edit', $clip_campaign) }}" class="w-full sm:w-auto btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Campaign</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            initCampaignDetailInteractions();
        });

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
    </script>
@endpush
