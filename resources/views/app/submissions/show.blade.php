@include('app.partials.head', [
    'title' => 'Detail Submission',
])

<div class="min-h-[100dvh] bg-slate-50 relative pb-24">

    <!-- Top App Bar (Modern Premium) -->
    <header
        class="flex items-center justify-between px-5 py-2.5 bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/50">
        <div class="flex items-center gap-3">
            <a href="{{ route('app.submissions.index') }}"
                class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Detail Submission</h1>
        </div>
    </header>

    @php
        $isApproved = in_array($submission->status, ['active', 'approved', 'completed'], true);
        $isPending = $submission->status === 'pending';
        $isRejected = $submission->status === 'rejected';
        $submitDate = $submission->submitted_at ?? $submission->created_at;
    @endphp

    <!-- Main Content Area -->
    <main class="px-5 pt-6 space-y-6 pb-8">

        <!-- Hero / Status Panel -->
        <div class="bg-white border border-slate-200 rounded-[1.5rem] p-5 overflow-hidden relative">
            <div class="flex items-center justify-between mb-4">
                @if ($isApproved)
                    <span
                        class="px-3 py-1 bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-wider rounded-lg border border-emerald-100/60 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-check text-[10px]"></i>
                        Disetujui
                    </span>
                @elseif ($isPending)
                    <span
                        class="px-3 py-1 bg-amber-50 text-amber-600 font-bold text-[10px] uppercase tracking-wider rounded-lg border border-amber-100/60 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-clock-rotate-left text-[10px]"></i>
                        Diproses
                    </span>
                @else
                    <span
                        class="px-3 py-1 bg-rose-50 text-rose-600 font-bold text-[10px] uppercase tracking-wider rounded-lg border border-rose-100/60 inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-xmark text-[10px]"></i>
                        Ditolak
                    </span>
                @endif

                <span class="text-[11px] font-medium text-slate-400">
                    {{ $submitDate ? $submitDate->translatedFormat('d M Y') : '-' }}
                </span>
            </div>

            <h2 class="text-lg font-bold text-slate-900 leading-snug tracking-tight mb-1.5">
                @if ($submission->clipCampaign)
                    <a href="{{ route('app.campaigns.show', $submission->clipCampaign) }}"
                        class="hover:text-indigo-600 transition-colors inline-flex items-center gap-1.5 group">
                        <span>{{ $submission->clipCampaign->title }}</span>
                        <i
                            class="fa-solid fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-indigo-600 transition-colors"></i>
                    </a>
                @else
                    <span>Campaign</span>
                @endif
            </h2>
            <p class="text-xs text-slate-500">
                Rate: Rp{{ number_format($submission->clipCampaign->commission_amount ?? 0, 0, ',', '.') }} /
                {{ number_format($submission->clipCampaign->view_threshold ?? 0, 0, ',', '.') }} views
            </p>
        </div>

        <!-- Tautan Video Panel -->
        <div class="bg-white border border-slate-200 rounded-[1.5rem] p-5">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">Tautan Video Anda</h3>
            <a href="{{ $submission->submitted_url }}" target="_blank"
                class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-100 rounded-[1.25rem] hover:border-indigo-300 hover:bg-indigo-50/30 transition-all group active:scale-[0.99]">
                <div
                    class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-indigo-500 group-hover:text-indigo-600 transition-colors shrink-0 shadow-xs border border-slate-200/60">
                    <i class="fa-brands fa-tiktok text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xs font-bold text-slate-900 group-hover:text-indigo-700 transition-colors truncate">
                        Buka Tautan Video</h3>
                    <p class="text-[10px] text-slate-500 truncate mt-0.5 font-medium group-hover:text-indigo-500/80">
                        {{ $submission->submitted_url }}
                    </p>
                </div>
                <div
                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors shrink-0 shadow-xs border border-slate-200/60">
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>
        </div>

        <!-- Performa & Metrik -->
        <div class="bg-white border border-slate-200 rounded-[1.5rem] p-5">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">Performa & Komisi</h3>

            <div class="space-y-4">
                <!-- Views -->
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total
                            Penayangan (Valid)</span>
                        <span class="text-sm font-bold text-slate-900">
                            {{ $isPending ? '-' : number_format($submission->current_views, 0, ',', '.') }}
                            @if (!$isPending)
                                <span class="text-[10px] font-medium text-slate-400 ml-1">views</span>
                            @endif
                        </span>
                        @if ($isApproved && $submission->credited_views > 0)
                            <span class="block text-[10px] text-emerald-600 font-semibold mt-0.5">
                                {{ number_format($submission->credited_views, 0, ',', '.') }} views terhitung komisi
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Divider -->
                <div class="h-px bg-slate-100 ml-14"></div>

                <!-- Reward -->
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-sack-dollar text-sm"></i>
                    </div>
                    <div>
                        <span
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Estimasi
                            Komisi</span>
                        <span class="text-sm font-bold text-slate-900">
                            {{ $isPending ? '-' : 'Rp' . number_format($submission->total_earned, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Status (Timeline) -->
        <div class="bg-white border border-slate-200 rounded-[1.5rem] p-5">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-5">Riwayat Status</h3>

            <div class="relative border-l-2 border-slate-100 ml-[9px] space-y-6">

                @if ($isApproved)
                    <!-- Step 2: Approved -->
                    <div class="relative pl-6">
                        <div
                            class="absolute w-4 h-4 bg-emerald-500 rounded-full -left-[9px] top-0 border-[3px] border-white ring-1 ring-slate-100">
                        </div>
                        <h4 class="text-[13px] font-bold text-slate-800 leading-none">Submission Disetujui</h4>
                        <p class="text-[10px] text-slate-500 mt-1.5">
                            {{ $submission->approved_at ? $submission->approved_at->translatedFormat('d M Y, H:i') : $submission->updated_at->translatedFormat('d M Y, H:i') }}
                        </p>
                        <p class="text-[11px] text-slate-600 mt-2 leading-relaxed">
                            Selamat! Tautan video Anda telah divalidasi. Komisi akan terus dihitung otomatis setiap hari
                            berdasarkan pertumbuhan tayangan video Anda.
                        </p>
                    </div>
                @elseif ($isRejected)
                    <!-- Step 2: Rejected -->
                    <div class="relative pl-6">
                        <div
                            class="absolute w-4 h-4 bg-rose-500 rounded-full -left-[9px] top-0 border-[3px] border-white ring-1 ring-slate-100">
                        </div>
                        <h4 class="text-[13px] font-bold text-rose-600 leading-none">Submission Ditolak</h4>
                        <p class="text-[10px] text-slate-500 mt-1.5">
                            {{ $submission->rejected_at ? $submission->rejected_at->translatedFormat('d M Y, H:i') : $submission->updated_at->translatedFormat('d M Y, H:i') }}
                        </p>
                        @if ($submission->rejection_reason)
                            <p class="text-[11px] text-rose-600 mt-2 leading-relaxed">
                                <span class="font-semibold text-rose-700">Alasan:</span> {{ $submission->rejection_reason }}
                            </p>
                        @endif
                    </div>
                @endif

                <!-- Step 1: Submitted -->
                <div class="relative pl-6 {{ !$isPending ? 'opacity-60' : '' }}">
                    <div
                        class="absolute w-4 h-4 {{ $isPending ? 'bg-amber-500' : 'bg-slate-300' }} rounded-full -left-[9px] top-0 border-[3px] border-white ring-1 ring-slate-100">
                    </div>
                    <h4
                        class="text-[13px] font-bold {{ $isPending ? 'text-amber-600' : 'text-slate-800' }} leading-none">
                        Video Berhasil Disubmit</h4>
                    <p class="text-[10px] text-slate-500 mt-1.5">
                        {{ $submitDate ? $submitDate->translatedFormat('d M Y, H:i') : '-' }}
                    </p>
                    @if ($isPending)
                        <p class="text-[11px] text-slate-600 mt-2 leading-relaxed">
                            Video Anda sudah masuk dalam antrean sistem dan sedang menunggu ditinjau oleh Admin.
                        </p>
                    @endif
                </div>
            </div>
        </div>

    </main>

</div>

@include('app.partials.vendor-script')
