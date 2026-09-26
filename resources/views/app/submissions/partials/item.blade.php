@php
    // Map status internal database ke kategori filter tampilan
    if ($sub->status === 'pending') {
        $statusGroup = 'diproses';
    } elseif (in_array($sub->status, ['active', 'approved', 'completed'], true)) {
        $statusGroup = 'disetujui';
    } else {
        $statusGroup = 'ditolak';
    }
@endphp

<a href="{{ route('app.submissions.show', $sub) }}" data-status="{{ $statusGroup }}" class="submission-item block bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99] flex flex-col">
    <div class="p-4">
        <h3 class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
            {{ $sub->clipCampaign->title ?? 'Campaign' }}
        </h3>

        <div class="mt-3.5 flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100/70 text-slate-600 text-xs font-medium border border-slate-200/60 hover:bg-slate-200 transition-colors">
                <i class="fa-brands fa-tiktok text-slate-900 text-[11px]"></i>
                <span>{{ \Illuminate\Support\Str::limit(str_replace(['https://www.', 'https://', 'http://www.', 'http://'], '', $sub->submitted_url), 32) }}</span>
            </span>
        </div>
    </div>

    <div class="border-t border-slate-100"></div>

    <div class="py-2.5 px-4 bg-slate-50/50 flex items-center justify-between">
        <div class="flex items-center gap-3">
            @if($statusGroup === 'disetujui')
                <div class="flex items-center gap-1.5 text-slate-500">
                    <i class="fa-regular fa-eye text-[11px]"></i>
                    <span class="text-[11px] font-medium">{{ number_format($sub->current_views, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-indigo-600">
                    <i class="fa-solid fa-coins text-[11px]"></i>
                    <span class="text-[11px] font-bold">Rp{{ number_format($sub->total_earned, 0, ',', '.') }}</span>
                </div>
            @elseif($statusGroup === 'diproses')
                <div class="flex items-center gap-1.5 text-slate-400">
                    <i class="fa-regular fa-eye text-[11px]"></i>
                    <span class="text-[11px] font-medium">-</span>
                </div>
                <div class="flex items-center gap-1.5 text-slate-400">
                    <i class="fa-solid fa-coins text-[11px]"></i>
                    <span class="text-[11px] font-medium">-</span>
                </div>
            @else
                <div class="flex items-center gap-1.5 text-slate-400">
                    <i class="fa-regular fa-eye text-[11px]"></i>
                    <span class="text-[11px] font-medium">{{ $sub->current_views > 0 ? number_format($sub->current_views, 0, ',', '.') : '-' }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-slate-400">
                    <i class="fa-solid fa-coins text-[11px]"></i>
                    <span class="text-[11px] font-medium">-</span>
                </div>
            @endif
        </div>

        <!-- Status Badge -->
        @if($statusGroup === 'disetujui')
            <div class="flex items-center gap-1 text-white bg-emerald-500 px-2 py-1 rounded-md">
                <i class="fa-solid fa-check text-[9px]"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Disetujui</span>
            </div>
        @elseif($statusGroup === 'diproses')
            <div class="flex items-center gap-1 text-white bg-amber-500 px-2 py-1 rounded-md">
                <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Diproses</span>
            </div>
        @else
            <div class="flex items-center gap-1 text-white bg-rose-500 px-2 py-1 rounded-md">
                <i class="fa-solid fa-xmark text-[9px]"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider">Ditolak</span>
            </div>
        @endif
    </div>
</a>
