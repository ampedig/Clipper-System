<a href="{{ route('app.campaigns.show', $campaign) }}" class="campaign-card flex flex-col bg-white border border-slate-200 rounded-[1.25rem] overflow-hidden hover:border-indigo-300 transition-all cursor-pointer group active:scale-[0.99]">
    <!-- Thumbnail -->
    <div class="relative w-full h-32 sm:h-36 bg-slate-100 overflow-hidden">
        <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" loading="lazy">
    </div>
    <div class="p-4 flex flex-col flex-1">
        <h3 class="font-bold text-sm text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2">
            {{ $campaign->title }}
        </h3>
        <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
            {{ $campaign->description }}
        </p>
        <div class="mt-4 flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-100/80">
                <i class="fa-solid fa-coins text-emerald-500"></i>
                <span>Rp{{ number_format($campaign->commission_amount, 0, ',', '.') }} / {{ $campaign->view_threshold >= 1000 ? ($campaign->view_threshold / 1000) . 'K' : $campaign->view_threshold }} views</span>
            </div>
            @if ($campaign->end_at)
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 text-[11px] font-medium border border-slate-200/60">
                    <i class="fa-solid fa-calendar-days text-slate-400"></i>
                    <span>{{ $campaign->end_at->translatedFormat('d M Y') }}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="border-t border-slate-100"></div>
    <div class="py-3 px-4 bg-slate-50/50 flex items-center justify-between">
        <span class="text-[11px] font-bold text-indigo-600 group-hover:text-indigo-700 flex items-center gap-1.5">
            Lihat Detail <i class="fa-solid fa-arrow-right transition-transform group-hover:translate-x-1"></i>
        </span>
        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider bg-slate-100 px-2 py-0.5 rounded-md">
            Sisa {{ $campaign->clipper_limit !== null ? $campaign->clipper_limit . ' slot' : 'Tanpa Batas' }}
        </span>
    </div>
</a>
