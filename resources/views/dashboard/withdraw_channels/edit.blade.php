@extends('dashboard.layouts.app')

@section('title', 'Edit Metode Penarikan (WD)')
@section('description', 'Form edit channel rekening bank dan e-wallet untuk penarikan komisi')

@section('content')
    <div class="flex-1 p-4 md:p-6">
        <div class="max-w-screen-2xl mx-auto space-y-6">

            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                    Edit Metode WD
                </h2>
                <!-- Breadcrumb -->
                @include('dashboard.partials.breadcrumb', [
                    'crumb1_label' => 'Dashboard',
                    'crumb1_url' => route('dashboard'),
                    'crumb2_label' => 'Komisi',
                    'crumb2_url' => '',
                    'crumb3_label' => 'Metode WD',
                    'crumb3_url' => route('withdraw-channels.index'),
                ])
            </div>

            <!-- Form Card Utama -->
            <form id="editChannelForm" action="{{ route('withdraw-channels.update', $withdraw_channel->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-8 transition-colors duration-300">
                    
                    <div class="pb-4 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200">Form Channel Pembayaran</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Edit data di bawah ini untuk mengubah pengaturan saluran penarikan dana.</p>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">* Kolom wajib diisi</span>
                    </div>

                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                Nama Channel / Bank <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" placeholder="Contoh: Bank Central Asia (BCA) atau GoPay" value="{{ old('name', $withdraw_channel->name) }}"
                                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-medium text-slate-800 dark:text-slate-200 @error('name') border-rose-500 @enderror" required>
                            @error('name')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @else
                                <p class="text-[11px] text-slate-400 mt-1">Nama metode penarikan yang akan dilihat oleh pengguna.</p>
                            @enderror
                        </div>

                        <!-- Grid: Code & Fee -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                    Code <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="code" name="code" placeholder="Contoh: BCA" value="{{ old('code', $withdraw_channel->code) }}"
                                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm uppercase tracking-wider focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-semibold text-slate-800 dark:text-slate-200 @error('code') border-rose-500 @enderror" required>
                                @error('code')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @else
                                    <p class="text-[11px] text-slate-400 mt-1">Kode unik pengenal sistem (misal: BCA, MANDIRI, GOPAY).</p>
                                @enderror
                            </div>

                            <!-- Fee -->
                            <div>
                                <label for="fee" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                                    Fee (Biaya Penarikan) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-semibold text-slate-400">
                                        Rp
                                    </div>
                                    <input type="number" id="fee" name="fee" placeholder="0" min="0" value="{{ old('fee', $withdraw_channel->fee) }}"
                                        class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-medium text-slate-800 dark:text-slate-200 @error('fee') border-rose-500 @enderror" required>
                                </div>
                                @error('fee')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @else
                                    <p class="text-[11px] text-slate-400 mt-1">Isi 0 jika bebas biaya transfer (Gratis).</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status Toggle Card -->
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-[#161616] border border-slate-100 dark:border-[#2e2e2e] flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div id="statusIconBg" class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 transition-colors">
                                    <i id="statusIcon" class="fa-solid fa-toggle-on text-lg"></i>
                                </div>
                                <div>
                                    <h4 id="statusLabel" class="text-sm font-semibold text-slate-800 dark:text-slate-200">Status: Aktif</h4>
                                    <p id="statusDesc" class="text-xs text-slate-400 mt-0.5">Metode penarikan ini aktif dan siap digunakan oleh pengguna.</p>
                                </div>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $withdraw_channel->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="pt-4 border-t border-slate-100 dark:border-[#2e2e2e] flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3">
                        <a href="{{ route('withdraw-channels.index') }}" class="btn btn-secondary rounded-xl px-5 py-2.5 text-sm font-semibold text-center">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleStatus = document.getElementById('is_active');
            const statusLabel = document.getElementById('statusLabel');
            const statusDesc = document.getElementById('statusDesc');
            const statusIcon = document.getElementById('statusIcon');
            const statusIconBg = document.getElementById('statusIconBg');

            function updateStatusUI() {
                if (toggleStatus.checked) {
                    statusLabel.textContent = 'Status: Aktif';
                    statusDesc.textContent = 'Metode penarikan ini aktif dan siap digunakan oleh pengguna.';
                    statusIcon.className = 'fa-solid fa-toggle-on text-lg';
                    statusIconBg.className =
                        'w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 transition-colors';
                } else {
                    statusLabel.textContent = 'Status: Nonaktif';
                    statusDesc.textContent =
                        'Metode penarikan dinonaktifkan sementara dan tidak dapat dipilih pengguna.';
                    statusIcon.className = 'fa-solid fa-toggle-off text-lg';
                    statusIconBg.className =
                        'w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center shrink-0 transition-colors';
                }
            }

            if (toggleStatus) {
                // Initialize state
                updateStatusUI();

                toggleStatus.addEventListener('change', updateStatusUI);
            }
        });
    </script>
@endpush
