<a href="{{ route('app.campaigns.show', $campaign) }}"
    class="flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-indigo-300 transition-all duration-200 cursor-pointer group active:scale-[0.98] outline-none focus:outline-none">

    {{-- Accent strip warna di paling atas card --}}
    <div class="h-1 shrink-0" style="background: linear-gradient(90deg, #ff0019 0%, #ff6b76 60%, #f59e0b 100%);"></div>

    {{-- Thumbnail dengan badge overlay --}}
    <div class="relative w-full overflow-hidden bg-slate-100" style="aspect-ratio: 16/10; min-height: 90px;">
        <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
            loading="lazy">

        {{-- Badge Slot — pojok kanan atas, warna merah sesuai color primary (#ff0019) --}}
        @php
            $quota = $campaign->clipper_limit !== null ? $campaign->remaining_quota : null;
        @endphp
        <div class="absolute top-2 right-2 inline-flex items-center gap-1 font-bold text-white leading-none rounded-full"
            style="font-size: 9px; padding: 3px 6.5px; background-color: #ff0019; box-shadow: 0 2px 6px rgba(255, 0, 25, 0.4);">
            <span>{{ $quota !== null ? $quota . ' Slot' : '∞ Slot' }}</span>
        </div>
    </div>

    {{-- Konten teks dengan padding proporsional (8px atas-bawah, 10px kiri-kanan) --}}
    <div class="flex flex-col flex-1 justify-between gap-2" style="padding: 8px 10px;">
        <div class="space-y-1">
            {{-- Judul campaign (2 baris max) --}}
            <h3 class="font-bold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2"
                style="font-size: 11px; min-height: 1.7rem;">
                {{ $campaign->title }}
            </h3>

            {{-- Komisi per views di bawah judul --}}
            <div class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100/80 font-bold max-w-full"
                style="font-size: 9px; line-height: 1.2;">
                <i class="fa-solid fa-coins text-emerald-500 shrink-0" style="font-size: 8px;"></i>
                <span class="truncate">
                    Rp{{ number_format($campaign->commission_amount, 0, ',', '.') }} / {{ $campaign->view_threshold >= 1000 ? number_format($campaign->view_threshold / 1000, 0, ',', '.') . 'K' : $campaign->view_threshold }} views
                </span>
            </div>
        </div>

        {{-- CTA footer yang bersih & ringkas --}}
        <div class="pt-1.5 border-t border-slate-100 flex items-center justify-between">
            <span class="font-black inline-flex items-center gap-1 group-hover:gap-1.5 transition-all"
                style="font-size: 10px; color: #ff0019;">
                Ikuti <i class="fa-solid fa-arrow-right" style="font-size: 8px;"></i>
            </span>
            <span class="font-bold uppercase tracking-wider text-slate-400" style="font-size: 8px;">
                DETAIL
            </span>
        </div>
    </div>
</a>
