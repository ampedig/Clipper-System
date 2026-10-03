@include('app.partials.head', [
    'title' => 'Akun TikTok Saya',
    'description' => 'Kelola dan verifikasi akun TikTok untuk pengajuan klip campaign di AZCLIP',
])

@push('styles')
    <style>
        .custom-code-box {
            letter-spacing: 0.15em;
        }
    </style>
@endpush

<div class="min-h-[100dvh] bg-slate-100 relative pb-32">

    <!-- Top App Bar (Modern Glassmorphic) -->
    <header
        class="flex items-center justify-between px-5 py-2.5 bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/60">
        <div class="flex items-center gap-3">
            <button type="button"
                onclick="window.history.length > 1 ? window.history.back() : window.location.href = '{{ route('app.profile') }}'"
                class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-slate-700 hover:text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0 cursor-pointer"
                aria-label="Kembali">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <div>
                <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Akun TikTok Saya</h1>
                <p class="text-[11px] font-medium text-slate-400 mt-1">Verifikasi kepemilikan akun profil TikTok</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 space-y-4 max-w-lg mx-auto">

        <!-- Banner Kuota Akun (Progress Card) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-sm shrink-0" style="background-color: #0f172a; color: #ffffff;">
                        <i class="fa-brands fa-tiktok"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Kuota Akun TikTok</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Batas pendaftaran akun clipper</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-slate-800">{{ $totalAccounts }} / {{ $maxAccounts }}</span>
                    <span class="text-[10px] text-slate-400 block font-medium">Akun Digunakan</span>
                </div>
            </div>

            <!-- Progress Bar Kuota -->
            @php
                $percentage = $maxAccounts > 0 ? min(100, round(($totalAccounts / $maxAccounts) * 100)) : 0;
            @endphp
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
            </div>

            <div class="flex items-center justify-between text-[11px] font-medium text-slate-500 pt-0.5">
                <span class="flex items-center gap-1 text-emerald-600 font-semibold">
                    <i class="fa-solid fa-circle-check text-[10px]"></i> {{ $verifiedAccounts }} Terverifikasi
                </span>
                <span>
                    @if ($canAddMore)
                        Sisa kuota: <strong>{{ $maxAccounts - $totalAccounts }} akun</strong>
                    @else
                        <span class="text-rose-500 font-semibold">Kuota Penuh</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Form Tambah Akun Baru (Jika Kuota Tersedia) -->
        @if ($canAddMore)
            <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-3">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tambah Akun TikTok</h3>
                </div>

                <form action="{{ route('app.tiktok.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="usernameInput" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                            Username TikTok <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3.5 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100/80 text-slate-600 text-sm font-bold select-none">
                                @
                            </span>
                            <input 
                                type="text" 
                                id="usernameInput" 
                                name="username" 
                                value="{{ old('username') }}" 
                                placeholder="username_kamu" 
                                required
                                class="flex-1 min-w-0 block w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-none rounded-r-xl text-sm font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:bg-white focus:border-indigo-500 outline-none transition-all"
                            >
                        </div>
                        @error('username')
                            <p class="text-xs text-rose-500 font-medium mt-1.5 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-sm shadow-indigo-600/20 cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Lanjutkan & Dapatkan Kode Bio</span>
                    </button>
                </form>
            </div>
        @endif

        <!-- List Akun TikTok Terdaftar -->
        <div class="space-y-3">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    Daftar Akun ({{ $totalAccounts }})
                </h3>
            </div>

            @forelse ($accounts as $account)
                <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-3 transition-all hover:border-slate-300">
                    
                    <!-- Header Kartu Akun -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Foto Profil / Avatar -->
                            @if ($account->avatar_url)
                                <img src="{{ $account->avatar_url }}" alt="{{ $account->username }}" 
                                    class="w-11 h-11 rounded-full object-cover border border-slate-200 shrink-0">
                            @else
                                <div class="w-11 h-11 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-sm" style="background-color: #0f172a; color: #ffffff;">
                                    <i class="fa-brands fa-tiktok"></i>
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">
                                        {{ $account->nickname ?: $account->username }}
                                    </h4>
                                </div>
                                <p class="text-xs font-semibold text-slate-500 font-mono truncate">
                                    &#64;{{ $account->username }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @if ($account->is_verified)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-[11px]"></i> Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                                <i class="fa-solid fa-clock text-amber-500 text-[11px]"></i> Belum Verifikasi
                            </span>
                        @endif
                    </div>

                    <!-- Jika Belum Terverifikasi: Kotak Kode Bio & Tombol Konfirmasi -->
                    @if (! $account->is_verified)
                        <div class="bg-amber-50/60 border border-amber-200/80 rounded-xl p-3.5 space-y-3">
                            <div>
                                <p class="text-[11px] font-bold text-slate-800 mb-0.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-key text-amber-600"></i> Kode Verifikasi Bio
                                </p>
                                <p class="text-[11px] text-slate-600 leading-relaxed">
                                    Salin kode di bawah ini lalu tempelkan ke <strong>Bio profil TikTok &#64;{{ $account->username }}</strong> Anda:
                                </p>
                            </div>

                            <!-- Box Kode & Salin -->
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-white border border-amber-300/80 rounded-xl py-2 px-3 flex items-center justify-center">
                                    <span class="text-base font-extrabold text-slate-900 font-mono custom-code-box tracking-widest select-all">
                                        {{ $account->verification_code }}
                                    </span>
                                </div>
                                <button type="button" 
                                    onclick="copyToClipboard('{{ $account->verification_code }}', this)"
                                    class="px-3.5 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all active:scale-95 shrink-0 cursor-pointer shadow-xs">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Salin</span>
                                </button>
                            </div>

                            <!-- Tombol Trigger Cek Verifikasi Bio -->
                            <button type="button"
                                onclick="verifyAccount('{{ route('app.tiktok.verify', $account) }}', '{{ $account->username }}', this)"
                                class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.99] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-sm shadow-emerald-600/20 cursor-pointer">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                <span>Saya Sudah Pasang Kode, Cek Sekarang</span>
                            </button>
                        </div>
                    @else
                        <!-- Informasi Akun Terverifikasi -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span>
                                <i class="fa-solid fa-shield-check text-emerald-500 mr-1"></i> Siap digunakan untuk pengajuan klip
                            </span>
                            @if ($account->verified_at)
                                <span>{{ $account->verified_at->translatedFormat('d M Y') }}</span>
                            @endif
                        </div>
                    @endif

                    <!-- Tombol Hapus Akun -->
                    <div class="pt-1 flex items-center justify-end">
                        <button type="button"
                            onclick="deleteAccount('{{ route('app.tiktok.destroy', $account) }}', '{{ $account->username }}')"
                            class="text-[11px] font-semibold text-slate-400 hover:text-rose-600 transition-colors flex items-center gap-1.5 py-1 px-2 rounded-lg hover:bg-rose-50 cursor-pointer">
                            <i class="fa-regular fa-trash-can text-xs"></i>
                            <span>Hapus Akun</span>
                        </button>
                    </div>

                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl border border-dashed border-slate-200 p-8 text-center space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center text-2xl mx-auto">
                        <i class="fa-brands fa-tiktok"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900">Belum Ada Akun TikTok</h4>
                        <p class="text-xs text-slate-400 font-medium max-w-[260px] mx-auto mt-1 leading-relaxed">
                            Daftarkan akun TikTok Anda untuk diverifikasi sebelum mengajukan video clip campaign.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

    </main>
</div>

<!-- Hidden Delete Form -->
<form id="deleteAccountForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    // Copy code to clipboard with visual feedback
    function copyToClipboard(text, btnElement) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showCopiedFeedback(btnElement);
            }).catch(() => {
                fallbackCopyText(text, btnElement);
            });
        } else {
            fallbackCopyText(text, btnElement);
        }
    }

    function fallbackCopyText(text, btnElement) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            showCopiedFeedback(btnElement);
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(textArea);
    }

    function showCopiedFeedback(btnElement) {
        const originalHtml = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="fa-solid fa-check text-xs text-emerald-600"></i> <span class="text-emerald-600 font-bold">Tersalin!</span>';
        setTimeout(() => {
            btnElement.innerHTML = originalHtml;
        }, 2000);
    }

    // Trigger Verification via AJAX
    function verifyAccount(verifyUrl, username, btnElement) {
        if (typeof Swal === 'undefined') return;

        const originalHtml = btnElement.innerHTML;
        btnElement.disabled = true;
        btnElement.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Mengecek Bio TikTok...</span>';
        btnElement.classList.add('opacity-80', 'cursor-not-allowed');

        fetch(verifyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            btnElement.disabled = false;
            btnElement.innerHTML = originalHtml;
            btnElement.classList.remove('opacity-80', 'cursor-not-allowed');

            if (status === 200 && body.success) {
                Swal.fire({
                    title: 'Berhasil Diverifikasi!',
                    text: body.message,
                    icon: 'success',
                    confirmButtonText: 'Selesai',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'btn btn-primary rounded-xl px-5 py-2.5 font-bold text-sm'
                    }
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Verifikasi Belum Berhasil',
                    text: body.message || 'Kode belum ditemukan pada bio profil TikTok Anda. Pastikan sudah disimpan dan coba lagi.',
                    icon: 'warning',
                    confirmButtonText: 'Coba Lagi Nanti',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'btn btn-secondary rounded-xl px-5 py-2.5 font-bold text-sm'
                    }
                });
            }
        })
        .catch(err => {
            btnElement.disabled = false;
            btnElement.innerHTML = originalHtml;
            btnElement.classList.remove('opacity-80', 'cursor-not-allowed');

            Swal.fire({
                title: 'Gangguan Koneksi',
                text: 'Terjadi kesalahan saat menghubungi server. Silakan coba beberapa saat lagi.',
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
        });
    }

    // Delete Account Confirmation
    function deleteAccount(deleteUrl, username) {
        if (typeof Swal === 'undefined') {
            if (confirm(`Yakin ingin menghapus akun @${username}?`)) {
                const form = document.getElementById('deleteAccountForm');
                form.action = deleteUrl;
                form.submit();
            }
            return;
        }

        Swal.fire({
            title: 'Hapus Akun TikTok?',
            text: `Apakah Anda yakin ingin menghapus akun @${username} dari daftar Anda?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-trash-can mr-1.5"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'btn btn-danger rounded-xl px-5 py-2.5 font-semibold text-sm ml-2',
                cancelButton: 'btn btn-secondary rounded-xl px-5 py-2.5 font-semibold text-sm'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteAccountForm');
                form.action = deleteUrl;
                form.submit();
            }
        });
    }

    // Flash Messages handling
    @if (session('success'))
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    timer: 2500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl' }
                });
            }
        });
    @endif

    @if (session('error'))
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Perhatian',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'Mengerti',
                    customClass: { popup: 'rounded-2xl' }
                });
            }
        });
    @endif
</script>
@endpush

@include('app.partials.vendor-script')
