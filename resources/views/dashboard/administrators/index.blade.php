@extends('dashboard.layouts.app')

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
                    Data Administrator
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Administrator',
                    'crumb2_url' => '',
                    'crumb3_label' => '',
                    'crumb3_url' => '',
                ])
            </div>

            <!-- Table Container -->
            <div
                class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">
                <!-- Header Table -->
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

                    <a class="btn btn-primary" href="{{ route('admin.administrators.create') }}">
                        <i class="fa-solid fa-plus"></i> Tambah
                    </a>
                </div>

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
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Status</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse($administrators as $index => $admin)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                    <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $administrators->firstItem() + $index }}</td>
                                    <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $admin->name }}</td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-400 td-nowrap">
                                        {{ $admin->whatsapp ?? '-' }}</td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-400 td-nowrap">{{ $admin->email }}
                                    </td>
                                    <td class="px-6 py-3 text-center td-nowrap">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer"
                                                {{ $admin->is_active ? 'checked' : '' }} 
                                                onchange="toggleAdminStatus(this, '{{ route('admin.administrators.status', $admin->id) }}')">
                                            <div
                                                class="w-9 h-5 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500">
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-6 py-3 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.administrators.edit', $admin->id) }}"
                                                class="btn btn-primary btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button class="btn btn-danger btn-icon" title="Hapus"
                                                onclick="confirmDelete('{{ addslashes($admin->name) }}', '{{ route('admin.administrators.destroy', $admin->id) }}')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-slate-500 dark:text-slate-400">
                                        Belum ada data administrator.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Rapi Presisi -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $administrators->links('dashboard.components.pagination') }}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
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

        function confirmDelete(name, deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data administrator ${name} akan dihapus secara permanen!`,
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

        async function toggleAdminStatus(checkbox, url) {
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

        // if select2 is available
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-show-entries').select2({
                minimumResultsForSearch: Infinity
            }).on('change', function() {
                let url = new URL(window.location.href);
                url.searchParams.set('per_page', $(this).val());
                window.location.href = url.href;
            });
        } else {
            document.querySelector('.select2-show-entries').addEventListener('change', function() {
                let url = new URL(window.location.href);
                url.searchParams.set('per_page', this.value);
                window.location.href = url.href;
            });
        }
    </script>
@endpush
