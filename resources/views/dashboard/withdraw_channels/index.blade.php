@extends('dashboard.layouts.app')

@section('title', 'Metode Penarikan (WD)')
@section('description', 'Pengaturan rekening dan e-wallet untuk penarikan komisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Metode WD
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Komisi',
                    'crumb2_url' => '',
                    'crumb3_label' => 'Metode WD',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Table Container Card -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">

                <!-- Header Table Controls -->
                <div
                    class="p-5 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500 dark:text-slate-400 font-medium">Show</span>
                        <select class="select2-show-entries w-24">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <a class="btn btn-primary" href="{{ route('admin.withdraw-channels.create') }}">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </a>
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

                <!-- Main Data Table -->
                <div class="overflow-x-auto">
                    <table id="channelTable" class="w-full text-left border-collapse">
                        <thead
                            class="bg-slate-50 dark:bg-[#1c1c1c] text-slate-500 dark:text-slate-400 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    #</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Nama</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Code</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Fee</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">

                            @forelse($withdrawChannels as $channel)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $loop->iteration + $withdrawChannels->firstItem() - 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        <span>{{ $channel->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 td-nowrap">
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 dark:bg-[#161616] text-slate-600 dark:text-slate-400 rounded-lg text-xs font-semibold tracking-wider border border-slate-200 dark:border-[#333]">
                                            {{ $channel->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-600 dark:text-slate-400 td-nowrap">
                                        {{ $channel->fee > 0 ? 'Rp ' . number_format($channel->fee, 0, ',', '.') : 'Gratis' }}
                                    </td>
                                    <td class="px-6 py-4 td-nowrap">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer status-toggle"
                                                {{ $channel->is_active ? 'checked' : '' }}
                                                onchange="toggleChannelStatus(this, '{{ route('admin.withdraw-channels.status', $channel->id) }}')">
                                            <div
                                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500">
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-6 py-4 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.withdraw-channels.edit', $channel->id) }}"
                                                class="btn btn-primary btn-icon" title="Edit Data">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-icon btn-delete"
                                                title="Hapus Data"
                                                onclick="confirmDelete('{{ $channel->name }}', '{{ route('admin.withdraw-channels.destroy', $channel->id) }}')">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <i
                                                class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300 dark:text-slate-600"></i>
                                            <p class="text-sm font-medium">
                                                @if (request('search'))
                                                    Tidak ada data metode penarikan yang cocok dengan pencarian "{{ request('search') }}".
                                                @else
                                                    Belum ada data metode penarikan
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <!-- Pagination Rapi Presisi -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $withdrawChannels->links('dashboard.components.pagination') }}
                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil!',
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

            // if select2 is available
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2-show-entries').select2({
                    minimumResultsForSearch: Infinity
                }).on('change', function() {
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', $(this).val());
                    url.searchParams.set('page', 1);
                    window.location.href = url.href;
                });
            } else {
                const select = document.querySelector('.select2-show-entries');
                if (select) {
                    select.addEventListener('change', function() {
                        let url = new URL(window.location.href);
                        url.searchParams.set('per_page', this.value);
                        url.searchParams.set('page', 1);
                        window.location.href = url.href;
                    });
                }
            }
        });

        function confirmDelete(name, deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data metode penarikan ${name} akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
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

        async function toggleChannelStatus(checkbox, url) {
            const isActive = checkbox.checked;

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        is_active: isActive
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    checkbox.checked = !isActive; // Revert if failed
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } catch (error) {
                checkbox.checked = !isActive; // Revert if failed
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak dapat menghubungi server.',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        }
    </script>
@endpush
