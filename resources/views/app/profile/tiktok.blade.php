@include('app.partials.head', [
    'title' => 'Akun TikTok Saya',
    'description' => 'Kelola dan verifikasi akun TikTok untuk pengajuan klip campaign di AZCLIP',
])

<style>
    /* Modern Clean Styles - Soft subtle borders, no shadow */
    .tiktok-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1.25rem;
    }

    #usernameInput::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .tiktok-input-group {
        display: flex;
        align-items: stretch;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background-color: #f8fafc;
        overflow: hidden;
        transition: border-color 0.15s ease, background-color 0.15s ease;
    }

    /* Thin crisp outline on focus - no glow or box-shadow */
    .tiktok-input-group:focus-within {
        border-color: #ff0019 !important;
        box-shadow: none !important;
        background-color: #ffffff;
    }

    .tiktok-input-prefix {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        flex-shrink: 0;
        color: #94a3b8;
        border-right: 1px solid #e2e8f0;
        background-color: #f8fafc;
        user-select: none;
        transition: color 0.15s ease, border-color 0.15s ease, background-color 0.15s ease;
    }

    .tiktok-input-group:focus-within .tiktok-input-prefix {
        color: #ff0019;
        border-right-color: #fee2e2;
        background-color: #fff1f2;
    }

    .tiktok-input-group.has-error {
        border-color: #f43f5e !important;
        background-color: #fff1f2 !important;
    }

    .tiktok-input-group.has-error .tiktok-input-prefix {
        color: #f43f5e;
        border-right-color: #fecdd3;
    }

    .tiktok-input-field {
        flex: 1;
        min-width: 0;
        padding: 0.8125rem 1rem;
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: #0f172a;
        box-shadow: none;
    }

    .tiktok-verify-box {
        background-color: #fffdf5;
        border: 1px solid #fef3c7;
        border-radius: 1rem;
    }

    .tiktok-code-box {
        background-color: #ffffff;
        border: 1px solid #fde68a;
        border-radius: 0.75rem;
    }

    .badge-verified {
        background-color: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .badge-pending {
        background-color: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    /* Custom SweetAlert width alignment so content width matches actions buttons */
    .swal2-popup.custom-swal-popup {
        padding: 1.5rem !important;
        border-radius: 1.75rem !important;
    }

    .swal2-popup.custom-swal-popup .swal2-icon,
    .swal2-popup.custom-swal-popup .swal2-title {
        display: none !important;
        margin: 0 !important;
        padding: 0 !important;
        height: 0 !important;
    }

    .swal2-popup.custom-swal-popup .swal2-html-container {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    /* Remove outline and box-shadow completely from SweetAlert buttons */
    .swal2-popup.custom-swal-popup button,
    .swal2-popup.custom-swal-popup .swal2-styled,
    .swal2-popup.custom-swal-popup .swal2-confirm,
    .swal2-popup.custom-swal-popup .swal2-cancel {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
        -webkit-tap-highlight-color: transparent !important;
    }

    .swal2-popup.custom-swal-popup button:focus,
    .swal2-popup.custom-swal-popup button:focus-visible,
    .swal2-popup.custom-swal-popup .swal2-styled:focus,
    .swal2-popup.custom-swal-popup .swal2-styled:focus-visible,
    .swal2-popup.custom-swal-popup .swal2-confirm:focus,
    .swal2-popup.custom-swal-popup .swal2-confirm:focus-visible,
    .swal2-popup.custom-swal-popup .swal2-cancel:focus,
    .swal2-popup.custom-swal-popup .swal2-cancel:focus-visible {
        outline: none !important;
        box-shadow: none !important;
    }

    .custom-code-box {
        letter-spacing: 0.15em;
    }
</style>

<div class="min-h-[100dvh] bg-slate-50 relative pb-32">

    <!-- Top App Bar (Modern Glassmorphic) -->
    <header
        class="flex items-center justify-between px-5 py-2.5 bg-white/80 backdrop-blur-xl sticky top-0 z-50 border-b border-slate-200/50">
        <div class="flex items-center gap-3">
            <button type="button"
                onclick="window.history.length > 1 ? window.history.back() : window.location.href = '{{ route('app.profile') }}'"
                class="w-10 h-10 bg-white border border-slate-200 flex items-center justify-center text-indigo-600 hover:bg-slate-50 transition-colors rounded-full active:scale-95 shrink-0 cursor-pointer"
                aria-label="Kembali">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Akun TikTok Saya</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 space-y-4">

        <!-- Info Note Banner -->
        <div class="rounded-2xl p-4 flex items-center gap-3"
            style="background-color: #eef2ff; border: 1px solid #e0e7ff;">
            <div
                class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 mb-0.5">Verifikasi Kepemilikan Akun</h4>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">
                    Daftarkan akun TikTok Anda untuk verifikasi identitas clipper sebelum dapat mengajukan link video
                    tugas campaign.
                </p>
            </div>
        </div>

        <!-- Banner Kuota Akun (Progress Card) -->
        <div class="tiktok-card p-4 space-y-3.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm shrink-0 shadow-xs"
                        style="background-color: #0f172a; color: #ffffff;">
                        <i class="fa-brands fa-tiktok text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-800">Kuota Akun TikTok</h4>
                        <p class="text-[11px] text-slate-400 font-medium">Batas maksimal akun terdaftar</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-extrabold text-slate-800">{{ $totalAccounts }} <span
                            class="text-xs font-medium text-slate-400">/ {{ $maxAccounts }}</span></span>
                    <span class="text-[10px] text-slate-400 block font-medium">Akun</span>
                </div>
            </div>

            <!-- Progress Bar Kuota -->
            @php
                $percentage = $maxAccounts > 0 ? min(100, round(($totalAccounts / $maxAccounts) * 100)) : 0;
            @endphp
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500"
                    style="width: {{ $percentage }}%"></div>
            </div>

            <div class="flex items-center justify-between text-[11px] font-medium text-slate-500 pt-0.5">
                <span class="flex items-center gap-1.5 text-emerald-600 font-semibold">
                    <i class="fa-solid fa-circle-check text-[11px]"></i> {{ $verifiedAccounts }} Terverifikasi
                </span>
                <span>
                    @if ($canAddMore)
                        Sisa kuota: <strong class="text-slate-700">{{ $maxAccounts - $totalAccounts }} akun</strong>
                    @else
                        <span class="text-rose-500 font-bold">Kuota Penuh</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Form Tambah Akun Baru (Jika Kuota Tersedia) -->
        @if ($canAddMore)
            <div class="tiktok-card p-5 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs shrink-0">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tambah Akun TikTok</h3>
                </div>

                <form action="{{ route('app.tiktok.store') }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label for="usernameInput"
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Username TikTok
                        </label>

                        <!-- Input Group: Perfectly Centered @ Vector Icon + Clean Input -->
                        <div class="tiktok-input-group @error('username') has-error @enderror">
                            <div class="tiktok-input-prefix" aria-hidden="true">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
                                </svg>
                            </div>
                            <input type="text" id="usernameInput" name="username" value="{{ old('username') }}"
                                placeholder="cth. ampedig" required autocomplete="off" spellcheck="false"
                                pattern="[^@]+" title="Username TikTok tidak boleh menyertakan simbol @"
                                oninput="this.value = this.value.replace(/@/g, '')" class="tiktok-input-field">
                        </div>

                        @error('username')
                            <div class="mt-1.5 flex items-center gap-1.5 text-xs font-semibold text-rose-500">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @else
                            <p class="text-[11px] text-slate-400 font-medium mt-1.5 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-info text-[11px] text-slate-400 shrink-0"></i>
                                <span>Kode verifikasi unik akan digenerate otomatis untuk ditaruh di bio profil TikTok
                                    Anda.</span>
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-xs cursor-pointer">
                        <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                        <span>Lanjutkan & Dapatkan Kode Bio</span>
                    </button>
                </form>
            </div>
        @endif

        <!-- List Akun TikTok Terdaftar -->
        <div class="space-y-3">
            <div class="flex items-center justify-between px-1">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    Daftar Akun ({{ $totalAccounts }})
                </h3>
            </div>

            @forelse ($accounts as $account)
                <div class="bg-white rounded-[1.25rem] border border-slate-200 p-4 {{ $account->is_verified ? 'space-y-3' : 'space-y-4' }} transition-all">

                    <!-- Header Kartu Akun -->
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Avatar -->
                            @if ($account->avatar_url)
                                <img src="{{ $account->avatar_url }}" alt="{{ $account->username }}"
                                    class="w-10 h-10 rounded-full object-cover shrink-0 ring-1 ring-slate-100">
                            @else
                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center shrink-0 ring-1 ring-slate-100">
                                    <i class="fa-brands fa-tiktok text-slate-800 text-lg"></i>
                                </div>
                            @endif

                            <div class="min-w-0 flex flex-col justify-center">
                                <h4 class="text-[13px] font-bold text-slate-900 truncate">
                                    {{ $account->nickname ?? $account->username }}
                                </h4>
                                <p class="text-[11px] font-medium text-slate-500 truncate mt-0.5">
                                    &#64;{{ $account->username }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        @if ($account->is_verified)
                            <div class="shrink-0 flex items-center justify-center w-9 h-9 rounded-full bg-emerald-100 text-emerald-600">
                                <i class="fa-solid fa-check text-[17px]" style="-webkit-text-stroke: 0.5px currentColor;"></i>
                            </div>
                        @else
                            <div class="shrink-0 flex items-center justify-center w-9 h-9 rounded-full bg-amber-100 text-amber-600">
                                <i class="fa-solid fa-clock-rotate-left text-[17px]" style="-webkit-text-stroke: 0.5px currentColor;"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Jika Belum Terverifikasi: Step panduan + Kotak Kode Bio & Tombol Konfirmasi -->
                    @if (!$account->is_verified)
                        <div class="tiktok-verify-box p-4 space-y-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-xs shrink-0"
                                    style="background-color: #fef3c7; color: #b45309;">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <h5 class="text-xs font-bold text-slate-800">Langkah Verifikasi Akun</h5>
                            </div>

                            <ol
                                class="text-[11px] text-slate-600 font-medium space-y-1.5 list-decimal list-inside leading-relaxed">
                                <li>Salin <strong>kode verifikasi</strong> di bawah ini.</li>
                                <li>Buka aplikasi TikTok & tempelkan kode tersebut pada <strong>Bio profil
                                        &#64;{{ $account->username }}</strong> Anda.</li>
                                <li>Simpan profil di TikTok, lalu klik tombol <strong>"Cek Sekarang"</strong>.</li>
                            </ol>

                            <!-- Box Kode & Salin -->
                            <div class="flex items-center gap-2">
                                <div
                                    class="tiktok-code-box flex-1 py-2.5 px-3 flex items-center justify-center shadow-xs">
                                    <span
                                        class="text-base font-extrabold text-slate-900 font-mono custom-code-box tracking-widest select-all">
                                        {{ $account->verification_code }}
                                    </span>
                                </div>
                                <button type="button"
                                    onclick="copyToClipboard('{{ $account->verification_code }}', this)"
                                    class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all active:scale-95 shrink-0 cursor-pointer shadow-xs"
                                    style="border: 1px solid #e2e8f0;">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>Salin</span>
                                </button>
                            </div>

                            <!-- Tombol Trigger Cek Verifikasi Bio -->
                            <button type="button"
                                onclick="verifyAccount('{{ route('app.tiktok.verify', $account) }}', '{{ $account->username }}', this)"
                                class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                <span>Sudah Pasang Kode, Cek Sekarang</span>
                            </button>
                        </div>
                    @endif

                    <!-- Footer Kartu: Buka Profil & Tombol Hapus -->
                    <div class="pt-2 flex items-center justify-between" style="border-top: 1px solid #f1f5f9;">
                        <a href="https://www.tiktok.com/{{ '@' . $account->username }}" target="_blank"
                            rel="noopener noreferrer"
                            class="text-[11px] font-semibold text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-1.5 py-1">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            <span>Buka TikTok</span>
                        </a>

                        <button type="button"
                            onclick="deleteAccount('{{ route('app.tiktok.destroy', $account) }}', '{{ $account->username }}')"
                            class="text-[11px] font-semibold text-slate-400 hover:text-rose-600 transition-colors flex items-center gap-1.5 py-1 px-2.5 rounded-lg hover:bg-rose-50 cursor-pointer">
                            <i class="fa-regular fa-trash-can text-xs"></i>
                            <span>Hapus Akun</span>
                        </button>
                    </div>

                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-2xl p-8 text-center space-y-3" style="border: 1px dashed #cbd5e1;">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mx-auto"
                        style="border: 1px solid #e0e7ff;">
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
            btnElement.innerHTML =
                '<i class="fa-solid fa-check text-xs text-emerald-600"></i> <span class="text-emerald-600 font-bold">Tersalin!</span>';
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
            }, 2000);
        }

        // Trigger Verification via AJAX with modern SweetAlert modal
        function verifyAccount(verifyUrl, username, btnElement) {
            if (typeof Swal === 'undefined') return;

            const originalHtml = btnElement.innerHTML;
            btnElement.disabled = true;
            btnElement.innerHTML =
                '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i> <span>Mengecek Bio TikTok...</span>';
            btnElement.classList.add('opacity-80', 'cursor-not-allowed');

            fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({
                    status,
                    body
                }) => {
                    btnElement.disabled = false;
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('opacity-80', 'cursor-not-allowed');

                    if (status === 200 && body.success) {
                        Swal.fire({
                            html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #a7f3d0;">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Berhasil Diverifikasi!</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                ${body.message || 'Akun TikTok Anda kini telah terverifikasi dan siap digunakan.'}
                            </p>
                        </div>
                    `,
                            confirmButtonText: 'Selesai',
                            buttonsStyling: false,
                            backdrop: 'rgba(15, 23, 42, 0.65)',
                            customClass: {
                                popup: 'custom-swal-popup !rounded-[2.25rem]',
                                htmlContainer: '!m-0 !p-0 !w-full',
                                actions: 'w-full mt-6 px-0',
                                confirmButton: 'w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fde68a;">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Verifikasi Belum Berhasil</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                ${body.message || 'Kode belum ditemukan pada bio profil TikTok Anda. Pastikan sudah disimpan dan coba lagi.'}
                            </p>
                        </div>
                    `,
                            confirmButtonText: 'Coba Lagi Nanti',
                            buttonsStyling: false,
                            backdrop: 'rgba(15, 23, 42, 0.65)',
                            customClass: {
                                popup: 'custom-swal-popup !rounded-[2.25rem]',
                                htmlContainer: '!m-0 !p-0 !w-full',
                                actions: 'w-full mt-6 px-0',
                                confirmButton: 'w-full py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                            }
                        });
                    }
                })
                .catch(err => {
                    btnElement.disabled = false;
                    btnElement.innerHTML = originalHtml;
                    btnElement.classList.remove('opacity-80', 'cursor-not-allowed');

                    Swal.fire({
                        html: `
                    <div class="flex flex-col items-center text-center p-1">
                        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fecdd3;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Gangguan Koneksi</h3>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                            Terjadi kesalahan saat menghubungi server. Silakan coba beberapa saat lagi.
                        </p>
                    </div>
                `,
                        confirmButtonText: 'Tutup',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full mt-6 px-0',
                            confirmButton: 'w-full py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                        }
                    });
                });
        }

        // Delete Account Confirmation Modal (Modern SweetAlert)
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
                html: `
                <div class="flex flex-col items-center text-center p-1">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fecdd3;">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Hapus Akun TikTok?</h3>
                    <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                        Apakah Anda yakin ingin menghapus akun <span class="font-bold text-slate-800">@${username}</span> dari daftar akun Anda?
                    </p>
                </div>
            `,
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                backdrop: 'rgba(15, 23, 42, 0.65)',
                customClass: {
                    popup: 'custom-swal-popup !rounded-[2.25rem]',
                    htmlContainer: '!m-0 !p-0 !w-full',
                    actions: 'w-full flex flex-row flex-nowrap gap-3 mt-6 px-0',
                    confirmButton: 'flex-1 py-3.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer',
                    cancelButton: 'flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteAccountForm');
                    form.action = deleteUrl;
                    form.submit();
                }
            });
        }

        // Flash Messages handling (Modern SweetAlert)
        @if (session('success'))
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #a7f3d0;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Berhasil!</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                {{ session('success') }}
                            </p>
                        </div>
                    `,
                        timer: 2500,
                        showConfirmButton: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full'
                        }
                    });
                }
            });
        @endif

        @if (session('error'))
            document.addEventListener("DOMContentLoaded", () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        html: `
                        <div class="flex flex-col items-center text-center p-1">
                            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4" style="border: 1px solid #fecdd3;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Perhatian</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[270px]">
                                {{ session('error') }}
                            </p>
                        </div>
                    `,
                        confirmButtonText: 'Mengerti',
                        buttonsStyling: false,
                        backdrop: 'rgba(15, 23, 42, 0.65)',
                        customClass: {
                            popup: 'custom-swal-popup !rounded-[2.25rem]',
                            htmlContainer: '!m-0 !p-0 !w-full',
                            actions: 'w-full mt-6 px-0',
                            confirmButton: 'w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                        }
                    });
                }
            });
        @endif
    </script>
@endpush

@include('app.partials.vendor-script')
