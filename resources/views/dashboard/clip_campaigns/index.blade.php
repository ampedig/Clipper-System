@extends('dashboard.layouts.app')

@section('title', 'Data Campaign Clipper - Masum.xyz')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                        Data Campaign Clipper
                    </h2>
                </div>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Campaign Clipper',
                    'crumb2_url' => '',
                    'crumb3_label' => '',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Table Container -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">
                <!-- Header Table Controls -->
                <div
                    class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Show</span>
                        <select class="select2-show-entries w-24">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Atur Kolom -->
                        <div class="relative shrink-0">
                            <button id="btnColumns" type="button"
                                class="w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-slate-600 dark:text-slate-400 font-medium text-sm hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200 transition flex items-center justify-center gap-2 whitespace-nowrap cursor-pointer">
                                <i class="fa-solid fa-table-columns"></i> <span class="whitespace-nowrap">Atur Kolom</span> <i
                                    class="fa-solid fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <!-- Column Dropdown Menu -->
                            <div id="columnMenu"
                                class="hidden absolute right-0 top-full mt-2 w-56 bg-white dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl shadow-lg z-50 p-2">
                                <div class="text-xs font-semibold text-slate-400 uppercase px-3 py-2">
                                    Tampilkan Kolom</div>
                                <!-- Container for Dynamic Columns -->
                                <div id="columnListContainer"
                                    class="space-y-1 max-h-60 overflow-y-auto custom-scrollbar">
                                    <!-- Checkboxes will be injected here by JS -->
                                    <div class="px-3 py-2 text-xs text-slate-400">Loading kolom...</div>
                                </div>
                            </div>
                        </div>

                        <a class="btn btn-primary" href="{{ route('admin.clip-campaigns.create') }}">
                            <i class="fa-solid fa-plus"></i> Tambah Campaign
                        </a>
                    </div>
                </div>

                @if (request('search'))
                    <div class="px-5 py-2.5 bg-brand-50/50 dark:bg-brand-950/20 border-b border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between text-xs">
                        <span class="text-slate-600 dark:text-slate-300">
                            Menampilkan hasil pencarian untuk: <strong class="text-brand-600 dark:text-brand-400 font-semibold">"{{ request('search') }}"</strong>
                        </span>
                        <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}"
                            class="text-rose-500 hover:text-rose-600 dark:text-rose-400 font-medium inline-flex items-center gap-1.5 hover:underline">
                            <i class="fa-solid fa-xmark"></i> Hapus Filter
                        </a>
                    </div>
                @endif

                <!-- Main Table -->
                <div class="overflow-x-auto">
                    <table id="campaignTable" class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 dark:bg-[#1c1c1c] text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    #</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Thumbnail</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Judul</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Komisi</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Maks View</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Batas</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Submission</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Periode</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Dibuat</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse ($campaigns as $campaign)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                    <td class="px-6 py-3.5 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $loop->iteration + ($campaigns->currentPage() - 1) * $campaigns->perPage() }}
                                    </td>
                                    <td class="px-6 py-3.5 td-nowrap">
                                        <div class="flex items-center">
                                            <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}"
                                                class="w-20 aspect-video object-cover rounded-lg border border-slate-200 dark:border-[#2e2e2e] shadow-sm shrink-0 bg-slate-100 dark:bg-[#1a1a1a]">
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        {{ $campaign->title }}
                                    </td>
                                    <td class="px-6 py-3.5 td-nowrap">
                                        <span class="inline-flex items-center gap-1.5 font-semibold text-slate-900 dark:text-white">
                                            Rp {{ number_format($campaign->commission_amount, 0, ',', '.') }}
                                            <span class="text-xs font-normal text-slate-400 dark:text-slate-500">/ {{ $campaign->view_threshold >= 1000 ? ($campaign->view_threshold / 1000) . 'K' : $campaign->view_threshold }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 td-nowrap">
                                        <span class="font-semibold text-slate-800 dark:text-slate-200">
                                            {{ $campaign->view_max ? number_format($campaign->view_max, 0, ',', '.') : 'Unlimited' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 td-nowrap">
                                        @php
                                            $used = $campaign->approved_submissions_count ?? 0;
                                            $limit = $campaign->clipper_limit;
                                            $percentage = $limit && $limit > 0 ? min(100, ($used / $limit) * 100) : 0;
                                            $remaining = $limit ? max(0, $limit - $used) : '∞';
                                        @endphp
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-slate-800 dark:text-slate-200" title="Sisa Kuota: {{ $remaining }}">{{ number_format($used, 0, ',', '.') }}</span>
                                            <span class="text-xs text-slate-400 dark:text-slate-500">/ {{ $limit ? number_format($limit, 0, ',', '.') : '∞' }}</span>
                                        </div>
                                        <div class="w-24 h-1.5 bg-slate-100 dark:bg-[#1a1a1a] rounded-full mt-1.5 overflow-hidden">
                                            <div class="h-full bg-brand-500 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        {{ $campaign->total_submissions_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-600 dark:text-slate-400 text-xs td-nowrap">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="fa-regular fa-calendar text-slate-400"></i>
                                            {{ $campaign->start_at ? $campaign->start_at->translatedFormat('d M Y') : '-' }} - {{ $campaign->end_at ? $campaign->end_at->translatedFormat('d M Y') : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-700 dark:text-slate-300 font-medium text-xs td-nowrap">
                                        {{ $campaign->creator->name ?? 'Sistem' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-center td-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold {{ $campaign->status->badgeClasses() }}">
                                            <i class="{{ $campaign->status->iconClass() }} text-[10px]"></i> {{ $campaign->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.clip-campaigns.show', $campaign) }}" class="btn btn-secondary btn-icon" title="Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.clip-campaigns.edit', $campaign) }}" class="btn btn-primary btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-icon" title="Hapus"
                                                onclick="confirmDelete('{{ addslashes($campaign->title) }}', '{{ route('admin.clip-campaigns.destroy', $campaign) }}')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        @if (request('search'))
                                            Tidak ada data campaign clipper yang cocok dengan pencarian "{{ request('search') }}".
                                        @else
                                            Belum ada data campaign clipper.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Rapi Presisi -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $campaigns->links('dashboard.components.pagination') }}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Vendor Scripts -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Page Specific Script for Column Visibility Toggle -->
    <script src="{{ asset('assets/js/clipp-campaign.page.js') }}"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Berhasil..!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Konfirmasi Hapus Data
        function confirmDelete(title, deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data campaign "${title}" akan dihapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl dark:bg-[#222222] dark:text-white border dark:border-[#2e2e2e]',
                    confirmButton: 'btn btn-danger rounded-xl px-5 py-2.5 font-semibold text-sm ml-2',
                    cancelButton: 'btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = deleteUrl;
                    form.method = 'POST';
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Handle reload on entries change
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2-show-entries').on('change', function() {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', $(this).val());
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });
            }
        });
    </script>
@endpush
