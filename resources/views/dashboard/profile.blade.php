@extends('dashboard.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/profil.page.css') }}">
@endpush

@section('content')
<div class="flex-1 p-4 md:p-6">
    <div class="max-w-screen-2xl mx-auto space-y-6">

        <!-- Page Header & Breadcrumb -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white tracking-tight">Profil Pengguna</h2>

            <!-- Breadcrumb -->
            @include('dashboard.partials.breadcrumb', [
                'crumb1_label' => 'Dashboard',
                'crumb1_url' => route('dashboard'),
                'crumb2_label' => 'Akun',
                'crumb2_url' => '',
                'crumb3_label' => '',
                'crumb3_url' => '',
            ])
        </div>

        <!-- 1 Panel Utama: Profil di Atas, Form di Bawah -->
        <div class="bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl overflow-hidden transition-colors duration-300">
            
            <!-- Header Profil (Bagian Atas Panel) -->
            <div class="p-6 sm:p-8 border-b border-slate-100 dark:border-[#2e2e2e] bg-slate-50/50 dark:bg-[#1a1a1a]/40">
                <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5">
                    <!-- Avatar dengan Tombol Kamera -->
                    <div class="relative shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1e293b&color=fff&bold=true&size=128"
                            alt="Profile"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white dark:border-[#222222] object-cover shadow-sm">

                        <!-- Hidden File Input -->
                        <input type="file" id="photoInput" class="hidden" accept="image/*">

                        <!-- Tombol Ubah Foto -->
                        <button type="button"
                            class="absolute bottom-0 right-0 bg-brand-600 w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-full text-white hover:bg-brand-700 transition-colors border-2 border-white dark:border-[#222222] shadow-sm"
                            title="Ubah Foto">
                            <i class="fa-solid fa-camera text-[10px]"></i>
                        </button>
                    </div>

                    <!-- Detail Profil Singkat -->
                    <div class="text-center sm:text-left">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white leading-tight">{{ $user->name }}</h2>
                        <p class="text-xs sm:text-sm font-medium text-brand-600 dark:text-brand-400 mt-0.5">{{ ucfirst($user->role ?? 'User') }}</p>
                        
                        <div class="flex items-center justify-center sm:justify-start gap-1.5 mt-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                            <i class="fa-regular fa-envelope text-slate-400"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Input (Bagian Bawah Panel) -->
            <div class="p-6 sm:p-8 space-y-8">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')
                    <!-- Informasi Pribadi -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Informasi Pribadi</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbarui nama dan kontak yang terhubung dengan akun Anda.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-2.5 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                            @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full px-4 py-2.5 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                @error('email') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Nomor Telepon</label>
                                <div class="flex items-stretch rounded-xl border border-slate-200 dark:border-[#2e2e2e] bg-white dark:bg-[#1a1a1a] focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:border-brand-500 transition-all">
                                    <span class="inline-flex items-center px-4 bg-slate-50 dark:bg-[#222222] border-r border-slate-200 dark:border-[#2e2e2e] rounded-l-xl text-sm font-medium text-slate-500 dark:text-slate-400 select-none">
                                        +62
                                    </span>
                                    <input type="tel" name="whatsapp" value="{{ old('whatsapp', str_replace('+62', '', $user->whatsapp ?? '')) }}"
                                        class="w-full px-4 py-2.5 bg-transparent rounded-r-xl text-sm text-slate-900 dark:text-white outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                        placeholder="822-3456-7890">
                                </div>
                                @error('whatsapp') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')
                    <!-- Ganti Kata Sandi -->
                    <div class="pt-8 border-t border-slate-100 dark:border-[#2e2e2e] space-y-6">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">Ganti Kata Sandi</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="Masukkan password saat ini..."
                                class="w-full px-4 py-2.5 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                            @error('current_password', 'updatePassword') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                @error('password', 'updatePassword') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                    class="w-full px-4 py-2.5 bg-white dark:bg-[#1a1a1a] border border-slate-200 dark:border-[#2e2e2e] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                @error('password_confirmation', 'updatePassword') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-6 mt-6 border-t border-slate-100 dark:border-[#2e2e2e] flex items-center justify-end gap-3">
                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        @if (session('status') === 'profile-updated')
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Berhasil..!',
                text: "Informasi profil berhasil diperbarui.",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
        @if (session('status') === 'password-updated')
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Berhasil..!',
                text: "Kata sandi berhasil diperbarui.",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    </script>
@endpush
