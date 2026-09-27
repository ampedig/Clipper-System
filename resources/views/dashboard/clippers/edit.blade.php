@extends('dashboard.layouts.app')

@section('title', 'Edit Clipper - Masum.xyz')
@section('description', 'Form Edit Data Clipper')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/css/select2.min.css') }}">
@endpush

@section('content')
<div class="flex-1 p-4 md:p-6">
    <div class="max-w-screen-2xl mx-auto space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">
                Edit Data Clipper
            </h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Ubah data profil dan status akun pengguna clipper.</p>
        </div>
        <!-- Breadcrumb -->
        @include('dashboard.partials.breadcrumb', [
            "crumb1_label" => "Dashboard",
            "crumb1_url"   => route('admin.dashboard'),
            "crumb2_label" => "Data Clipper",
            "crumb2_url"   => route('admin.clippers.index'),
            "crumb3_label" => "Edit",
            "crumb3_url"   => ""
        ])
    </div>

    <!-- Form Card Utama -->
    <form id="editClipperForm" action="{{ route('admin.clippers.update', $clipper->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-6 sm:p-8 space-y-8 transition-colors duration-300">

            <!-- Header Form -->
            <div class="pb-4 border-b border-slate-100 dark:border-[#2e2e2e] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">Form Akun Clipper</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Perbarui informasi profil pengguna clipper.</p>
                </div>
                <span class="text-xs text-slate-400 font-medium">* Kolom wajib diisi</span>
            </div>

            <!-- Informasi Profil & Kontak -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-user text-xs"></i>
                        </div>
                        <input type="text" name="name" id="clipperNameInput" placeholder="Contoh: Ahmad Fauzi" value="{{ old('name', $clipper->name) }}"
                            class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 font-medium text-slate-800 dark:text-slate-200" required>
                    </div>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Whatsapp -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">No. WhatsApp <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </div>
                        <input type="tel" name="whatsapp" id="clipperWhatsappInput" placeholder="0812-3456-7890" value="{{ old('whatsapp', $clipper->whatsapp) }}"
                            class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200" required>
                    </div>
                    @error('whatsapp')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </div>
                        <input type="email" name="email" id="clipperEmailInput" placeholder="clipper@example.com" value="{{ old('email', $clipper->email) }}"
                            class="w-full pl-9 pr-4 py-2.5 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors placeholder-slate-400 text-slate-800 dark:text-slate-200" required>
                    </div>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Peran / Role -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Peran / Role Pengguna <span class="text-rose-500">*</span></label>
                    <select name="role" id="clipperRoleSelect" class="select2-role w-full" required>
                        <option value="clipper" {{ old('role', $clipper->role) === 'clipper' ? 'selected' : '' }}>Clipper</option>
                        <option value="admin" {{ old('role', $clipper->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">Pilih peran akun pengguna. Jika diubah menjadi Administrator, akun ini akan dialihkan memiliki hak akses penuh ke panel admin.</p>
                    @error('role')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kredensial Password -->
            <div class="p-5 bg-slate-50 dark:bg-[#161616] rounded-2xl border border-slate-100 dark:border-[#2e2e2e] space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-brand-600 dark:text-brand-400"></i> Kredensial Kata Sandi
                    </label>
                    <button type="button" id="btnGeneratePassword" class="text-xs font-semibold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i> Acak Password
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Password Baru (Opsional)</label>
                        <div class="relative">
                            <input type="password" name="password" id="clipperPasswordInput" placeholder="Kosongkan jika tidak ingin diubah"
                                class="w-full pl-4 pr-10 py-2.5 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors text-slate-800 dark:text-slate-200">
                            <button type="button" id="togglePasswordVisibility" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors" title="Lihat password">
                                <i class="fa-regular fa-eye text-xs" id="passwordEyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="clipperPasswordConfirmInput" placeholder="Ulangi password..."
                                class="w-full pl-4 pr-10 py-2.5 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm focus:outline-none focus:border-brand-500 transition-colors text-slate-800 dark:text-slate-200">
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-500">Isi hanya jika ingin mengganti password akun clipper. Gunakan minimal 8 karakter.</p>
            </div>

            <!-- Status Akun (Boolean Toggle) -->
            <div class="p-4 border border-slate-100 dark:border-[#2e2e2e] rounded-2xl bg-slate-50 dark:bg-[#161616] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="statusIcon" class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm transition-colors duration-200">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                    <div>
                        <p id="statusTitle" class="text-sm font-semibold text-slate-800 dark:text-slate-200 transition-colors">Status Akun Aktif</p>
                        <p id="statusDesc" class="text-xs text-slate-400 dark:text-slate-500 transition-colors">Clipper dapat login dan mengirimkan klip.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" id="toggleStatusClipper" class="sr-only peer" {{ old('is_active', $clipper->is_active) ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500">
                    </div>
                </label>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e] flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.clippers.index') }}" class="btn btn-secondary rounded-xl px-6 py-2.5 text-sm font-semibold text-center">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary rounded-xl px-6 py-2.5 text-sm font-semibold flex items-center justify-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>

        </div>
    </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2-role').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });
        }
        const togglePasswordBtn = document.getElementById("togglePasswordVisibility");
        const passwordInput = document.getElementById("clipperPasswordInput");
        const eyeIcon = document.getElementById("passwordEyeIcon");

        if (togglePasswordBtn && passwordInput && eyeIcon) {
            togglePasswordBtn.addEventListener("click", () => {
                const isPassword = passwordInput.getAttribute("type") === "password";
                passwordInput.setAttribute("type", isPassword ? "text" : "password");
                eyeIcon.className = isPassword ? "fa-regular fa-eye-slash text-xs" : "fa-regular fa-eye text-xs";
            });
        }

        const btnGenerate = document.getElementById("btnGeneratePassword");
        const confirmPasswordInput = document.getElementById("clipperPasswordConfirmInput");

        if (btnGenerate && passwordInput && confirmPasswordInput) {
            btnGenerate.addEventListener("click", () => {
                const chars = "abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%";
                let generated = "";
                for (let i = 0; i < 10; i++) {
                    generated += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                passwordInput.value = generated;
                confirmPasswordInput.value = generated;
                passwordInput.setAttribute("type", "text");
                if (eyeIcon) eyeIcon.className = "fa-regular fa-eye-slash text-xs";
            });
        }

        const toggleStatus = document.getElementById("toggleStatusClipper");
        const statusTitle = document.getElementById("statusTitle");
        const statusDesc = document.getElementById("statusDesc");
        const statusIcon = document.getElementById("statusIcon");

        if (toggleStatus) {
            const updateStatusUI = (isChecked) => {
                if (isChecked) {
                    statusTitle.textContent = "Status Akun Aktif";
                    statusDesc.textContent = "Clipper dapat login dan mengirimkan klip.";
                    statusIcon.className = "w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm transition-colors duration-200";
                } else {
                    statusTitle.textContent = "Status Akun Nonaktif";
                    statusDesc.textContent = "Akun ditangguhkan sementara dan tidak dapat login.";
                    statusIcon.className = "w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-sm transition-colors duration-200";
                }
            };

            updateStatusUI(toggleStatus.checked);

            toggleStatus.addEventListener("change", function () {
                updateStatusUI(this.checked);
            });
        }
    });
</script>
@endpush
