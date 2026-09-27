@extends('dashboard.layouts.app')

@section('title', 'Data Clipper - Masum.xyz')
@section('description', 'Halaman Pengelolaan Data Clipper')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Data Clipper
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Data Clipper',
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
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <a class="btn btn-primary" href="{{ route('admin.clippers.create') }}">
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

                <!-- Main Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
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
                                    Whatsapp</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Email</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Saldo</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse ($clippers as $clipper)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                    <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $loop->iteration + ($clippers->currentPage() - 1) * $clippers->perPage() }}
                                    </td>
                                    <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $clipper->name }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-400 td-nowrap">
                                        {{ $clipper->whatsapp ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-400 td-nowrap">
                                        {{ $clipper->email }}
                                    </td>
                                    <td class="px-6 py-3 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        Rp {{ number_format($clipper->balance, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-center td-nowrap">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer"
                                                {{ $clipper->is_active ? 'checked' : '' }}
                                                onchange="toggleClipperStatus(this, '{{ route('admin.clippers.status', $clipper->id) }}')">
                                            <div
                                                class="w-9 h-5 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500">
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-6 py-3 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.clippers.show', $clipper->id) }}"
                                                class="btn btn-secondary btn-icon" title="Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.clippers.edit', $clipper->id) }}"
                                                class="btn btn-primary btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-icon" title="Hapus"
                                                onclick="confirmDelete('{{ addslashes($clipper->name) }}', '{{ route('admin.clippers.destroy', $clipper->id) }}')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        @if (request('search'))
                                            Tidak ada data clipper yang cocok dengan pencarian "{{ request('search') }}".
                                        @else
                                            Belum ada data clipper.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Rapi Presisi -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $clippers->links('dashboard.components.pagination') }}
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <!-- Select2 & SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
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


        function confirmDelete(name, deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data clipper ${name} akan dihapus secara permanen!`,
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

        async function toggleClipperStatus(checkbox, url) {
            const isActive = checkbox.checked;

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ is_active: isActive })
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
                    checkbox.checked = !isActive;
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
                checkbox.checked = !isActive;
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

        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
@endpush
