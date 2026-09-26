@foreach ($groupedTransactions as $dateLabel => $items)
    <div class="tx-group" data-date-group="{{ $dateLabel }}">
        <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
            {{ $dateLabel }}</h3>
        <div class="space-y-3 tx-group-items">
            @foreach ($items as $tx)
                @php
                    $isIncome = $tx->type === 'credit';
                    $txType = $isIncome ? 'income' : 'outcome';
                    $title = $isIncome ? 'Reward Komisi Klip' : 'Penarikan Dana';
                    $formattedAmount = 'Rp ' . number_format($tx->amount, 0, ',', '.');
                    $displayAmount = ($isIncome ? '+Rp ' : '-Rp ') . number_format($tx->amount, 0, ',', '.');
                    $dateTimeStr = $tx->created_at->locale('id')->isoFormat('D MMM Y, HH:mm');
                    $refCode = ($isIncome ? 'TRX-' : 'WD-') . $tx->created_at->format('ymd') . str_pad($tx->id, 4, '0', STR_PAD_LEFT);

                    // Subtitle line (Time • Notes snippet)
                    $timeStr = $tx->created_at->format('H:i');
                    if ($isIncome) {
                        $subtitleNote = str_contains($tx->notes ?? '', ' - ')
                            ? explode(' - ', $tx->notes)[1]
                            : ($tx->notes ?? 'Reward Klip');
                    } else {
                        $subtitleNote = str_contains($tx->notes ?? '', 'ke ')
                            ? 'Tarik ' . explode('ke ', $tx->notes)[1]
                            : 'Penarikan Dana';
                    }
                @endphp
                <div onclick="openTxDetail(this)"
                    data-tx-type="{{ $txType }}"
                    data-tx-title="{{ $title }}"
                    data-tx-amount="{{ $formattedAmount }}"
                    data-tx-date="{{ $dateTimeStr }}"
                    data-tx-ref="{{ $refCode }}"
                    data-tx-notes="{{ $tx->notes ?? ($isIncome ? 'Reward otomatis dari pencapaian views klip.' : 'Penarikan dana berhasil diproses.') }}"
                    class="tx-item flex items-center justify-between p-3 bg-white rounded-2xl border border-slate-200 cursor-pointer hover:border-indigo-200 transition-all active:scale-95">
                    <div class="flex items-center gap-3 overflow-hidden">
                        @if ($isIncome)
                            <div
                                class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-arrow-down"></i>
                            </div>
                        @else
                            <div
                                class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-lg shrink-0">
                                <i class="fa-solid fa-arrow-up"></i>
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $title }}</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5 truncate">{{ $timeStr }} • {{ \Illuminate\Support\Str::limit($subtitleNote, 28) }}</p>
                        </div>
                    </div>
                    <p
                        class="text-sm font-bold {{ $isIncome ? 'text-emerald-600' : 'text-rose-600' }} shrink-0 whitespace-nowrap ml-3">
                        {{ $displayAmount }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
