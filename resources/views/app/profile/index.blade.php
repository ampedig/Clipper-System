@include('app.partials.head', [
    'title' => 'Akun',
])

@php
    $currentUser = $user ?? auth()->user();
    $name = trim($currentUser->name ?? 'Masum');
    $email = $currentUser->email ?? 'masum@example.com';
    $words = preg_split('/\s+/', $name);
    if (count($words) >= 2) {
        $initial = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    } else {
        $initial = strtoupper(substr($name, 0, 2));
    }
@endphp

<div class="px-5 pt-6 space-y-6 pb-24">
    <!-- Master Card: Profil & Statistik Terpadu (Panel Putih Polos + Aksen Primary) -->
    <section class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <!-- Bagian Atas: Info Akun -->
        <div class="p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3.5 min-w-0">
                <!-- Monogram Avatar (Background Primary) -->
                <div
                    class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-extrabold text-lg tracking-tight shrink-0 shadow-sm">
                    {{ $initial }}
                </div>
                <!-- User Info -->
                <div class="min-w-0">
                    <h2 class="text-base font-bold text-slate-900 tracking-tight truncate">{{ $name }}</h2>
                    <p class="text-sm font-semibold text-slate-600 truncate mt-0.5">{{ $email }}</p>
                </div>
            </div>
        </div>

        <!-- Divider Horizontal Halus -->
        <div class="border-t border-slate-100"></div>

        <!-- Bagian Bawah: Statistik Saldo & Klip (Interaktif) -->
        <div class="py-3.5 px-2 bg-slate-50/40 grid grid-cols-2 divide-x divide-slate-100">
            <!-- Stat 1: Saldo Aktif -->
            <a href="{{ route('app.wallet.index') }}"
                class="px-3 flex flex-col items-center justify-center text-center group active:scale-[0.98] transition-all">
                <span
                    class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-600 transition-colors">Total
                    Saldo</span>
                <p class="text-xl font-extrabold text-indigo-600 tracking-tight mt-0.5">Rp
                    {{ number_format($currentUser->balance ?? 0, 0, ',', '.') }}</p>
            </a>

            <!-- Stat 2: Klip Disetujui -->
            <a href="{{ route('app.submissions.index') }}"
                class="px-3 flex flex-col items-center justify-center text-center group active:scale-[0.98] transition-all">
                <span
                    class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-600 transition-colors">Klip
                    Disetujui</span>
                <div class="flex items-baseline justify-center gap-1 mt-0.5">
                    <span
                        class="text-xl font-extrabold text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">{{ $approvedSubmissions ?? 0 }}</span>
                    <span class="text-xs font-medium text-slate-400">/ {{ $totalSubmissions ?? 0 }} klip</span>
                </div>
            </a>
        </div>
    </section>

    <!-- List Menu -->
    <div class="space-y-6">
        <!-- Group 1: Akun & Keamanan -->
        <div>
            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Akun & Keamanan</h3>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100">
                <!-- Kelola Profil -->
                <a href="{{ route('app.profile.edit') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Kelola Profil</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Perbarui Data Profil</p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>

                <!-- Ubah Password -->
                <a href="{{ route('app.password.edit') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Kata Sandi</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Ubah password akun secara berkala
                            </p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>

                <!-- Atur Rekening -->
                <a href="{{ route('app.rekening') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Atur Rekening Pencairan</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Kelola rekening bank atau e-wallet
                            </p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>

                <!-- Akun TikTok Saya -->
                <a href="{{ route('app.tiktok.index') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center text-base group-hover:scale-105 transition-transform shadow-sm"
                            style="background-color: #0f172a; color: #ffffff;">
                            <i class="fa-brands fa-tiktok text-base"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5">
                                <p class="text-xs font-bold text-slate-800">Akun TikTok Saya</p>
                                @php
                                    $tiktokCount = $currentUser->tiktokAccounts()->count();
                                    $verifiedCount = $currentUser->tiktokAccounts()->where('is_verified', true)->count();
                                @endphp
                                @if ($verifiedCount > 0)
                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[8px]"></i> {{ $verifiedCount }}
                                    </span>
                                @elseif ($tiktokCount > 0)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                        {{ $tiktokCount }} Pending
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Kelola & verifikasi kepemilikan akun TikTok</p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>

                <!-- Riwayat Penarikan -->
                <a href="{{ route('app.withdrawals.index') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Riwayat Penarikan</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Pantau status pencairan dana</p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>
            </div>
        </div>

        <!-- Group 2: Bantuan & Kebijakan -->
        <div>
            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Bantuan & Kebijakan</h3>
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden divide-y divide-slate-100">
                <!-- Hubungi CS -->
                <a href="{{ route('app.help') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Bantuan & Kontak CS</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Hubungi customer care via WhatsApp
                                & Telegram
                            </p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>

                <!-- Syarat & Ketentuan -->
                <a href="{{ route('app.policy') }}"
                    class="flex items-center justify-between p-3.5 hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center text-base group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-file-shield"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Syarat & Kebijakan Layanan</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-0.5">Ketentuan verifikasi view & hak
                                cipta</p>
                        </div>
                    </div>
                    <i
                        class="fa-solid fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-500 transition-colors"></i>
                </a>
            </div>
        </div>

        <!-- Group 3: Logout -->
        <div class="pt-2">
            <button type="button" onclick="confirmLogout()"
                class="w-full flex items-center justify-center gap-2.5 p-3.5 bg-rose-50 text-rose-600 hover:bg-rose-100/80 rounded-2xl border border-rose-200/70 font-bold text-xs transition-all active:scale-[0.98] cursor-pointer">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar dari Akun</span>
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>

@include('app.partials.bottom-nav', [
    'active' => 'akun',
])

@include('app.partials.vendor-script')

<!-- Logout Confirmation Script via SweetAlert2 -->
<script>
    function confirmLogout() {
        Swal.fire({
            html: `
                <div class="flex flex-col items-center text-center pt-2">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl mb-4 border border-rose-100">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1.5">Konfirmasi Keluar</h3>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-[250px]">Apakah kamu yakin ingin keluar dari akun Clipper ini?</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            backdrop: 'rgba(15, 23, 42, 0.65)',
            customClass: {
                popup: 'custom-swal-logout-popup',
                actions: 'w-full flex flex-row flex-nowrap gap-3 mt-5 px-0',
                confirmButton: 'flex-1 py-3 px-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs whitespace-nowrap text-center transition-colors active:scale-95 cursor-pointer',
                cancelButton: 'flex-1 py-3 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs whitespace-nowrap text-center transition-colors active:scale-95 cursor-pointer'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>

@if (session('status') === 'profile-updated')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center text-center pt-2">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 border border-emerald-100/80">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Profil Diperbarui!</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[260px]">
                                Perubahan nama dan nomor WhatsApp Anda telah berhasil disimpan.
                            </p>
                        </div>
                    `,
                    showConfirmButton: false,
                    timer: 2000,
                    backdrop: 'rgba(15, 23, 42, 0.65)',
                    customClass: {
                        popup: 'custom-swal-popup !rounded-[2.25rem]'
                    }
                });
            }
        });
    </script>
@endif

@if (session('status') === 'password-updated')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center text-center pt-2">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4 border border-emerald-100/80">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Kata Sandi Diperbarui!</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[260px]">
                                Kata sandi baru berhasil disimpan. Silakan gunakan kata sandi baru untuk masuk berikutnya.
                            </p>
                        </div>
                    `,
                    showConfirmButton: false,
                    timer: 2000,
                    backdrop: 'rgba(15, 23, 42, 0.65)',
                    customClass: {
                        popup: 'custom-swal-popup !rounded-[2.25rem]'
                    }
                });
            }
        });
    </script>
@endif
