<a href="{{ route('app.campaigns.show', $campaign) }}"
    class="flex flex-col rounded-xl overflow-hidden hover:border-indigo-500/50 transition-all duration-200 cursor-pointer group active:scale-[0.98] outline-none focus:outline-none"
    style="background-color: #111827; border: 1px solid rgba(255, 255, 255, 0.08);">

    {{-- Accent strip warna di paling atas card --}}
    <div class="h-1 shrink-0" style="background: linear-gradient(90deg, #ff0019 0%, #ff6b76 60%, #f59e0b 100%);"></div>

    {{-- Thumbnail bersih tanpa overlay stiker --}}
    <div class="relative w-full overflow-hidden" style="aspect-ratio: 16/10; min-height: 90px; background-color: #1f2937;">
        <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
            loading="lazy">
    </div>

    {{-- Konten teks dengan padding proporsional --}}
    <div class="flex flex-col flex-1 justify-between gap-2.5" style="padding: 10px 10px 9px 10px;">
        <div class="space-y-1">
            {{-- Judul campaign (2 baris max, warna putih terang) --}}
            <h3 class="font-bold text-white leading-snug group-hover:text-indigo-400 transition-colors line-clamp-2"
                style="font-size: 11px; min-height: 1.7rem;">
                {{ $campaign->title }}
            </h3>

            {{-- Komisi per views: teks rupiah merah primary menyala --}}
            <div class="flex items-baseline gap-1 min-w-0 pt-0.5">
                <span class="font-extrabold shrink-0" style="font-size: 11px; color: #ff0019;">
                    Rp{{ number_format($campaign->commission_amount, 0, ',', '.') }}
                </span>
                <span style="font-size: 9.5px; color: #475569;">/</span>
                <span class="truncate" style="font-size: 9.5px; color: #94a3b8; font-weight: 600;">
                    {{ $campaign->view_threshold >= 1000 ? number_format($campaign->view_threshold / 1000, 0, ',', '.') . 'K' : $campaign->view_threshold }} views
                </span>
            </div>
        </div>

        {{-- Slot Kuota Progress Bar ala Flash Sale --}}
        @php
            $hasLimit = $campaign->clipper_limit !== null;
            $remaining = $hasLimit ? $campaign->remaining_quota : null;
            $used = $hasLimit ? max(0, $campaign->clipper_limit - $remaining) : 0;
            $percent = ($hasLimit && $campaign->clipper_limit > 0)
                ? min(100, round(($used / $campaign->clipper_limit) * 100))
                : 0;
        @endphp

        @if ($hasLimit)
            <div class="pt-2 space-y-1.5" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="flex items-center justify-between" style="font-size: 9px; line-height: 1;">
                    <span style="color: #94a3b8; font-weight: 500;">Slot Kuota</span>
                    <span class="font-bold text-slate-200">
                        Sisa <span style="color: #ff0019;">{{ $remaining }}</span>/{{ $campaign->clipper_limit }}
                    </span>
                </div>
                {{-- Progress bar tipis & halus --}}
                <div class="w-full rounded-full overflow-hidden" style="height: 4px; background-color: rgba(255, 255, 255, 0.08);">
                    <div class="h-full rounded-full transition-all duration-300"
                        style="width: {{ max(4, $percent) }}%; background: linear-gradient(90deg, #ff0019, #ff5c6a);">
                    </div>
                </div>
            </div>
        @else
            <div class="pt-2 flex items-center justify-between" style="font-size: 9px; line-height: 1; border-top: 1px solid rgba(255, 255, 255, 0.08);">
                <span style="color: #94a3b8; font-weight: 500;">Slot Kuota</span>
                <span class="font-bold text-emerald-400 inline-flex items-center gap-1">
                    <i class="fa-solid fa-infinity" style="font-size: 8px;"></i>
                    <span>Tanpa Batas</span>
                </span>
            </div>
        @endif
    </div>
</a>
