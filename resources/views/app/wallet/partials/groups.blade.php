@foreach ($groupedTransactions as $dateLabel => $items)
    <div class="tx-group" data-date-group="{{ $dateLabel }}">
        <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
            {{ $dateLabel }}</h3>
        <div class="space-y-3 tx-group-items">
            @foreach ($items as $tx)
                @php
                    $isIncome = $tx->type === 'credit';
                    $txType = $isIncome ? 'income' : 'outcome';
                    $title = $isIncome ? 'Penambahan Saldo' : 'Pengurangan Saldo';
                    $formattedAmount = 'Rp ' . number_format($tx->amount, 0, ',', '.');
                    $displayAmount = ($isIncome ? '+Rp ' : '-Rp ') . number_format($tx->amount, 0, ',', '.');
                    $dateTimeStr = $tx->created_at->locale('id')->isoFormat('D MMM Y, HH:mm');
                    $refCode = ($isIncome ? 'TRX-' : 'WD-') . $tx->created_at->format('ymd') . str_pad($tx->id, 4, '0', STR_PAD_LEFT);
                    $timeStr = $tx->created_at->format('H:i');
                @endphp
                <div onclick="openTxDetail(this)"
                    data-tx-type="{{ $txType }}"
                    data-tx-title="{{ $title }}"
                    data-tx-amount="{{ $formattedAmount }}"
                    data-tx-date="{{ $dateTimeStr }}"
                    data-tx-ref="{{ $refCode }}"
                    data-tx-balance-after="{{ 'Rp ' . number_format($tx->balance_after, 0, ',', '.') }}"
                    data-tx-notes="{{ $tx->notes ?? ($isIncome ? 'Penambahan saldo berhasil diproses.' : 'Pengurangan saldo berhasil diproses.') }}"
                    class="tx-item flex items-center gap-3.5 p-3.5 bg-white rounded-2xl border border-slate-200 cursor-pointer hover:border-indigo-200 transition-all active:scale-[0.98]">
                    
                    <!-- Kiri sendiri: ICON -->
                    @if ($isIncome)
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-arrow-down"></i>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-arrow-up"></i>
                        </div>
                    @endif

                    <!-- Content 2 kolom (Baris Atas & Baris Bawah) -->
                    <div class="flex-1 min-w-0">
                        <!-- Baris Atas: Nominal (kiri) & Badge Tambah/Kurang (kanan) -->
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-bold text-slate-900 truncate">
                                {{ $formattedAmount }}
                            </p>
                            @if ($isIncome)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/80 shrink-0">
                                    Tambah
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 text-[10px] font-bold border border-rose-200/80 shrink-0" style="color: #e11d48;">
                                    Kurang
                                </span>
                            @endif
                        </div>

                        <!-- Baris Bawah: Jam (kiri) & Sisa Saldo (kanan) -->
                        <div class="flex items-center justify-between gap-2 mt-1">
                            <p class="text-[10px] font-medium text-slate-400">
                                {{ $timeStr }}
                            </p>
                            <p class="text-[11px] font-medium text-slate-500">
                                Sisa Saldo: Rp {{ number_format($tx->balance_after, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
