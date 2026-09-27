@extends('dashboard.layouts.app')

@section('title', 'Dashboard - AMPEDIG Admin')
@section('description', 'Dashboard Admin page')

@section('content')
<div class="flex-1 p-4 md:p-6">
                    <div class="max-w-screen-2xl mx-auto space-y-8">
                        
                        <!-- Bento Box Stats Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Card 1: Campaign Aktif -->
                            <div class="group relative h-32 p-6 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl flex items-center justify-between hover:border-brand-500/50 transition-colors duration-300">
                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Campaign Aktif</span>
                                    <h3 class="text-2xl font-semibold text-slate-900 dark:text-white leading-tight">{{ number_format($activeCampaignsCount, 0, ',', '.') }}</h3>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-950/30 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-600 dark:group-hover:bg-brand-500 group-hover:text-white dark:group-hover:text-white">
                                    <i class="fa-solid fa-bullhorn text-2xl"></i>
                                </div>
                            </div>
                            <!-- Card 2: Total Komisi (Bulan Ini) -->
                            <div class="group relative h-32 p-6 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden flex items-center justify-between hover:border-brand-500/50 transition-colors duration-300">
                                <span class="absolute top-0 right-0 text-[10px] font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/20 px-3 py-1 rounded-bl-2xl transition-all duration-300 group-hover:bg-brand-600 dark:group-hover:bg-brand-500 group-hover:text-white dark:group-hover:text-white">Bulan Ini</span>
                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Komisi</span>
                                    <h3 class="text-2xl font-semibold text-slate-900 dark:text-white leading-tight">Rp {{ number_format($monthlyCommissionTotal, 0, ',', '.') }}</h3>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-950/30 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-600 dark:group-hover:bg-brand-500 group-hover:text-white dark:group-hover:text-white">
                                    <i class="fa-solid fa-wallet text-2xl"></i>
                                </div>
                            </div>
                            <!-- Card 3: Klip Di Submit (Bulan Ini) -->
                            <div class="group relative h-32 p-6 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden flex items-center justify-between hover:border-brand-500/50 transition-colors duration-300">
                                <span class="absolute top-0 right-0 text-[10px] font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/20 px-3 py-1 rounded-bl-2xl transition-all duration-300 group-hover:bg-brand-600 dark:group-hover:bg-brand-500 group-hover:text-white dark:group-hover:text-white">Bulan Ini</span>
                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Klip Di Submit</span>
                                    <h3 class="text-2xl font-semibold text-slate-900 dark:text-white leading-tight">{{ number_format($monthlySubmissionsCount, 0, ',', '.') }}</h3>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-950/30 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-600 dark:group-hover:bg-brand-500 group-hover:text-white dark:group-hover:text-white">
                                    <i class="fa-solid fa-film text-2xl"></i>
                                </div>
                            </div>
                            <!-- Card 4: Jumlah Withdraw -->
                            <div class="group relative h-32 p-6 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl flex items-center justify-between hover:border-brand-500/50 transition-colors duration-300">
                                <div class="space-y-1">
                                    <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Jumlah Withdraw</span>
                                    <h3 class="text-2xl font-semibold text-slate-900 dark:text-white leading-tight">Rp {{ number_format($completedWithdrawalsTotal, 0, ',', '.') }}</h3>
                                </div>
                                <div class="w-14 h-14 rounded-2xl bg-brand-50 dark:bg-brand-950/30 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-brand-600 dark:group-hover:bg-brand-500 group-hover:text-white dark:group-hover:text-white">
                                    <i class="fa-solid fa-money-bill-transfer text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Layout Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                            
                            <!-- Submission Trend Area Chart (ApexCharts) -->
                            <div class="lg:col-span-3 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 flex flex-col justify-between">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-white text-base">Tren Pengajuan Klip</h3>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Statistik volume pengajuan dan persetujuan klip harian</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button id="btnTrend7Days" type="button" class="px-3 py-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/30 rounded-xl transition-colors hover:bg-brand-100 dark:hover:bg-brand-900/40">
                                            7 Hari
                                        </button>
                                        <button id="btnTrend30Days" type="button" class="px-3 py-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-[#2a2a2a] rounded-xl transition-colors">
                                            30 Hari
                                        </button>
                                    </div>
                                </div>
                                <div id="submissionTrendChart" class="w-full"></div>
                            </div>

                            <!-- Submission Status Breakdown Donut Chart (ECharts) -->
                            <div class="lg:col-span-2 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white text-base">Status Pengajuan Klip</h3>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Distribusi persentase seluruh klip berdasarkan status</p>
                                </div>
                                <div id="submissionStatusChart" class="w-full h-[300px]"></div>
                            </div>

                        </div>

                        <!-- Data Table Section: Pengajuan Klip Terbaru -->
                        <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-[#222222]">
                                <div>
                                    <h3 class="font-semibold text-slate-900 dark:text-white text-base">Pengajuan Klip Terbaru</h3>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">5 kiriman video TikTok terbaru dari clipper yang masuk ke sistem</p>
                                </div>
                                <a href="{{ route('admin.clip-submissions.index') }}" class="btn btn-outline-primary btn-sm rounded-xl">Lihat Semua</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-slate-50 dark:bg-[#1a1a1a] border-b border-slate-100 dark:border-[#2e2e2e]">
                                        <tr>
                                            <th class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">#</th>
                                            <th class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Tanggal</th>
                                            <th class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Clipper</th>
                                            <th class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Campaign</th>
                                            <th class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Link Video TikTok</th>
                                            <th class="px-6 py-3.5 text-right font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Views Saat Ini</th>
                                            <th class="px-6 py-3.5 text-right font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Komisi</th>
                                            <th class="px-6 py-3.5 text-center font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Status</th>
                                            <th class="px-6 py-3.5 text-center font-semibold text-slate-800 dark:text-slate-200 uppercase text-xs tracking-wider td-nowrap">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                                        @forelse ($recentSubmissions as $sub)
                                            @php
                                                $statusConfig = [
                                                    'pending' => [
                                                        'class' => 'bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                                        'icon' => 'fa-regular fa-clock',
                                                        'label' => 'Pending',
                                                    ],
                                                    'approved' => [
                                                        'class' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                                        'icon' => 'fa-solid fa-circle-check',
                                                        'label' => 'Disetujui',
                                                    ],
                                                    'active' => [
                                                        'class' => 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400',
                                                        'icon' => 'fa-solid fa-circle-check',
                                                        'label' => 'Aktif',
                                                    ],
                                                    'completed' => [
                                                        'class' => 'bg-purple-100 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400',
                                                        'icon' => 'fa-solid fa-flag-checkered',
                                                        'label' => 'Selesai',
                                                    ],
                                                    'rejected' => [
                                                        'class' => 'bg-rose-100 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400',
                                                        'icon' => 'fa-solid fa-circle-xmark',
                                                        'label' => 'Ditolak',
                                                    ],
                                                ];
                                                $st = $statusConfig[$sub->status] ?? [
                                                    'class' => 'bg-slate-100 dark:bg-slate-500/10 text-slate-700 dark:text-slate-400',
                                                    'icon' => 'fa-solid fa-info-circle',
                                                    'label' => ucfirst($sub->status),
                                                ];
                                            @endphp
                                            <tr class="hover:bg-slate-50/50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                                <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td class="px-6 py-4 td-nowrap">
                                                    <span class="font-medium text-slate-800 dark:text-slate-200 block">
                                                        {{ $sub->created_at->translatedFormat('d M Y') }}
                                                    </span>
                                                    <span class="text-xs text-slate-400 dark:text-slate-500">
                                                        {{ $sub->created_at->translatedFormat('H:i') }} WIB
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 td-nowrap">
                                                    <span class="font-semibold text-slate-800 dark:text-slate-200 block">
                                                        {{ $sub->user->name ?? 'User' }}
                                                    </span>
                                                    <span class="text-xs text-slate-400 dark:text-slate-500">
                                                        {{ $sub->user->email ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 td-nowrap">
                                                    <span class="font-medium text-slate-800 dark:text-slate-200 block max-w-[200px] truncate" title="{{ $sub->clipCampaign->title ?? '-' }}">
                                                        {{ $sub->clipCampaign->title ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 td-nowrap">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ $sub->submitted_url }}" target="_blank"
                                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-[#161616] dark:hover:bg-[#252525] text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-[#2e2e2e] transition">
                                                            <i class="fa-brands fa-tiktok text-slate-900 dark:text-white"></i> Tonton
                                                        </a>
                                                        <button type="button"
                                                            onclick="copyLink('{{ $sub->submitted_url }}')"
                                                            class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-[#161616] dark:hover:bg-[#252525] text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-[#2e2e2e] flex items-center justify-center transition"
                                                            title="Salin Link">
                                                            <i class="fa-solid fa-copy text-xs"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-right font-semibold text-slate-900 dark:text-white td-nowrap">
                                                    {{ number_format($sub->current_views, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 text-right font-semibold {{ $sub->total_earned > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }} td-nowrap">
                                                    Rp {{ number_format($sub->total_earned, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 text-center td-nowrap">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $st['class'] }}">
                                                        <i class="{{ $st['icon'] }} text-[10px]"></i> {{ $st['label'] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-center td-nowrap">
                                                    <a href="{{ route('admin.clip-submissions.index', ['search' => $sub->user->email ?? $sub->video_id]) }}"
                                                        class="btn btn-secondary btn-icon btn-sm" title="Lihat di Pengajuan">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                                    Belum ada pengajuan klip terbaru.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- 1. ApexCharts: Tren Pengajuan Klip ---
        const trendData7 = @json($trendData7Days);
        const trendData30 = @json($trendData30Days);

        const trendEl = document.querySelector("#submissionTrendChart");
        let trendChart = null;

        if (trendEl) {
            const isDark = document.documentElement.classList.contains("dark");
            const textColor = isDark ? "#94a3b8" : "#64748b";
            const gridBorderColor = isDark ? "#2e2e2e" : "#f1f5f9";

            const brandColor = (window.getThemeColor && window.getThemeColor("--color-brand-600")) ? window.getThemeColor("--color-brand-600") : "#2563eb";
            const emeraldColor = "#10b981";

            const getOptions = (data) => ({
                series: [
                    { name: "Klip Diajukan", data: data.submitted },
                    { name: "Klip Disetujui", data: data.approved }
                ],
                chart: {
                    height: 320,
                    type: "area",
                    fontFamily: "'Work Sans', sans-serif",
                    toolbar: { show: false },
                    animations: { enabled: true, easing: "easeinout", speed: 600 }
                },
                dataLabels: { enabled: false },
                stroke: { curve: "smooth", width: 2.5 },
                colors: [brandColor, emeraldColor],
                fill: {
                    type: "gradient",
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] }
                },
                xaxis: {
                    categories: data.categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: { colors: textColor, fontSize: "11px", fontFamily: "'Work Sans', sans-serif" }
                    }
                },
                yaxis: {
                    labels: {
                        style: { colors: textColor, fontSize: "11px", fontFamily: "'Work Sans', sans-serif" },
                        formatter: (val) => Math.round(val)
                    },
                    min: 0,
                    forceNiceScale: true
                },
                grid: {
                    borderColor: gridBorderColor,
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: isDark ? "dark" : "light",
                    y: {
                        formatter: (val) => val + " Klip"
                    }
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                    labels: { colors: textColor },
                    fontFamily: "'Work Sans', sans-serif"
                }
            });

            trendChart = new ApexCharts(trendEl, getOptions(trendData7));
            trendChart.render();

            const btn7 = document.getElementById("btnTrend7Days");
            const btn30 = document.getElementById("btnTrend30Days");

            const activeClass = "px-3 py-1.5 text-xs font-semibold text-brand-600 dark:text-brand-400 bg-brand-50 dark:bg-brand-950/30 rounded-xl transition-colors hover:bg-brand-100 dark:hover:bg-brand-900/40";
            const inactiveClass = "px-3 py-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-[#2a2a2a] rounded-xl transition-colors";

            if (btn7 && btn30) {
                btn7.addEventListener("click", () => {
                    btn7.className = activeClass;
                    btn30.className = inactiveClass;
                    trendChart.updateOptions(getOptions(trendData7));
                });

                btn30.addEventListener("click", () => {
                    btn30.className = activeClass;
                    btn7.className = inactiveClass;
                    trendChart.updateOptions(getOptions(trendData30));
                });
            }
        }

        // --- 2. ECharts: Status Pengajuan Klip ---
        const statusChartEl = document.getElementById("submissionStatusChart");
        let statusChart = null;

        if (statusChartEl) {
            statusChart = echarts.init(statusChartEl);
            const statusData = @json($statusChartData);
            const isDark = document.documentElement.classList.contains("dark");
            const textColor = isDark ? "#94a3b8" : "#64748b";
            const tooltipBg = isDark ? "#222222" : "#ffffff";
            const tooltipBorder = isDark ? "#2e2e2e" : "#e2e8f0";
            const tooltipTextColor = isDark ? "#cbd5e1" : "#334155";
            const pieBorderColor = isDark ? "#222222" : "#ffffff";

            const statusOption = {
                tooltip: {
                    trigger: "item",
                    formatter: "{b} : {c} klip ({d}%)",
                    backgroundColor: tooltipBg,
                    borderColor: tooltipBorder,
                    textStyle: { color: tooltipTextColor }
                },
                legend: {
                    bottom: 0,
                    icon: "circle",
                    itemWidth: 8,
                    itemHeight: 8,
                    textStyle: { color: textColor, fontSize: 11, fontFamily: "'Work Sans', sans-serif" }
                },
                series: [
                    {
                        name: "Status Klip",
                        type: "pie",
                        radius: ["42%", "72%"],
                        center: ["50%", "45%"],
                        itemStyle: {
                            borderRadius: 8,
                            borderColor: pieBorderColor,
                            borderWidth: 2
                        },
                        label: { show: false },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: "14",
                                fontWeight: "600",
                                color: textColor
                            }
                        },
                        data: statusData
                    }
                ]
            };

            statusChart.setOption(statusOption);

            window.addEventListener("resize", () => {
                statusChart && statusChart.resize();
            });

            window.addEventListener("theme-changed", () => {
                if (statusChart) {
                    const dark = document.documentElement.classList.contains("dark");
                    statusChart.setOption({
                        tooltip: {
                            backgroundColor: dark ? "#222222" : "#ffffff",
                            borderColor: dark ? "#2e2e2e" : "#e2e8f0",
                            textStyle: { color: dark ? "#cbd5e1" : "#334155" }
                        },
                        legend: {
                            textStyle: { color: dark ? "#94a3b8" : "#64748b" }
                        },
                        series: [
                            {
                                itemStyle: {
                                    borderColor: dark ? "#222222" : "#ffffff"
                                }
                            }
                        ]
                    });
                }
            });
        }
    });

    // --- Clipboard Helper ---
    function copyLink(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showToastAlert('Link video berhasil disalin!');
            }).catch(() => {
                fallbackCopyText(text);
            });
        } else {
            fallbackCopyText(text);
        }
    }

    function fallbackCopyText(text) {
        const temp = document.createElement('input');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showToastAlert('Link video berhasil disalin!');
    }

    function showToastAlert(msg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: msg,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            alert(msg);
        }
    }
</script>
@endpush
