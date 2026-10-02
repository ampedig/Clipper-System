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
                    Template Penolakan
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('admin.dashboard'),
                    'crumb2_label' => 'Template Penolakan',
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

                    <a class="btn btn-primary" href="{{ route('admin.rejection-templates.create') }}">
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
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap w-16">
                                    #</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Judul</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap">
                                    Command</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                    Isi Pesan</th>
                                <th
                                    class="px-6 py-3.5 border-b border-slate-100 dark:border-[#2e2e2e] text-center t-title-data font-semibold text-slate-800 dark:text-slate-200 uppercase tracking-wider td-nowrap w-32">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#2e2e2e] text-sm">
                            @forelse($templates as $index => $template)
                                <tr class="hover:bg-slate-50 dark:hover:bg-[#2a2a2a]/30 transition-colors">
                                    <td class="px-6 py-3 font-semibold text-slate-700 dark:text-slate-300 td-nowrap">
                                        {{ $templates->firstItem() + $index }}</td>
                                    <td class="px-6 py-3 font-semibold text-slate-800 dark:text-slate-200 td-nowrap">
                                        {{ $template->title }}
                                    </td>
                                    <td class="px-6 py-3 font-semibold text-brand-600 dark:text-brand-400 td-nowrap">
                                        <code class="px-2 py-1 bg-brand-50 text-brand-600 rounded-md text-xs">{{ $template->command }}</code>
                                    </td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-400">
                                        {{ Str::limit($template->message, 100) }}
                                    </td>
                                    <td class="px-6 py-3 text-center td-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.rejection-templates.edit', $template->id) }}"
                                                class="btn btn-primary btn-icon" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <button class="btn btn-danger btn-icon" title="Hapus"
                                                onclick="confirmDelete('{{ addslashes($template->title) }}', '{{ route('admin.rejection-templates.destroy', $template->id) }}')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-400 opacity-50"></i><br>
                                        Belum ada template penolakan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-5 border-t border-slate-100 dark:border-[#2e2e2e]">
                    {{ $templates->links('dashboard.components.pagination') }}
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
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

        function confirmDelete(title, deleteUrl) {
            Swal.fire({
                title: 'Hapus Template?',
                text: `Template penolakan "${title}" akan dihapus permanen!`,
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

        // Select2 for per_page
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-show-entries').select2({
                minimumResultsForSearch: Infinity
            }).on('change', function() {
                let url = new URL(window.location.href);
                url.searchParams.set('per_page', $(this).val());
                url.searchParams.set('page', '1');
                window.location.href = url.href;
            });
        }
    </script>
@endpush
