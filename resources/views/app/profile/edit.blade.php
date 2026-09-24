@include('app.partials.head', [
    'title' => 'Edit Profil',
])

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
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Edit Profil</h1>
        </div>
    </header>

    <!-- Main Content Form -->
    <main class="p-4 space-y-4">

        <!-- Info Banner Card -->
        <div class="bg-indigo-50/70 border border-indigo-100/80 rounded-2xl p-4 flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800 mb-0.5">Informasi Akun</h4>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed">Pastikan nama dan nomor WhatsApp aktif
                    untuk kelancaran verifikasi tugas klip serta pencairan saldo reward.</p>
            </div>
        </div>

        <form id="editProfileForm" action="{{ route('app.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Form Fields Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-5">

                <!-- Field 1: Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}"
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-4 py-3.5 bg-slate-50/80 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm font-semibold text-slate-900 focus:outline-none focus:border-indigo-500 focus:bg-white transition-colors" />
                    <div id="name-error"
                        class="@error('name') flex @else hidden @enderror mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first('name') ?? 'Nama tidak boleh kosong (minimal 2 karakter)' }}</span>
                    </div>
                </div>

                <!-- Field 2: Nomor WhatsApp -->
                <div>
                    <label for="whatsapp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Nomor WhatsApp
                    </label>
                    <div
                        class="relative flex items-center rounded-xl bg-slate-50/80 border @error('whatsapp') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                        <div
                            class="pl-4 pr-3 py-3.5 text-sm font-bold text-slate-500 select-none border-r border-slate-200/80 bg-slate-100/50 shrink-0">
                            +62
                        </div>
                        <input type="tel" id="whatsapp" name="whatsapp"
                            value="{{ old('whatsapp', $formattedWa ?? '') }}" placeholder="81234567890"
                            class="w-full px-3.5 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none tracking-wide" />
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium mt-1.5">Masukkan nomor tanpa angka 0 di awal
                        (contoh: 812xxxxxxx).</p>
                    <div id="whatsapp-error"
                        class="@error('whatsapp') flex @else hidden @enderror mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first('whatsapp') ?? 'Nomor WhatsApp tidak valid (minimal 8 digit angka)' }}</span>
                    </div>
                </div>

                <!-- Field 3: Email Akun (Read-only / Locked) -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Email Terdaftar
                        </label>
                        <span
                            class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Permanen</span>
                        </span>
                    </div>
                    <input type="email" value="{{ $user->email ?? '' }}" disabled
                        class="w-full px-4 py-3.5 bg-slate-100/70 border border-slate-200 rounded-xl text-sm font-semibold text-slate-500 cursor-not-allowed select-none" />
                    <p class="text-[11px] text-slate-400 font-medium mt-1.5">Email tidak dapat diubah.</p>
                </div>

            </div>

            <!-- Bottom Fixed Save Button (Floating) -->
            <div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto px-4 pb-6 pt-2 z-40 pb-safe">
                <button type="button" id="btn-save"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-3.5 rounded-2xl transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
                    <i class="fa-regular fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>

    </main>

</div>

@include('app.partials.vendor-script')

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById('editProfileForm');
        const nameInput = document.getElementById('name');
        const waInput = document.getElementById('whatsapp');
        const btnSave = document.getElementById('btn-save');
        const nameError = document.getElementById('name-error');
        const waError = document.getElementById('whatsapp-error');

        // Khusus angka pada input WhatsApp
        waInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });

        btnSave.addEventListener('click', () => {
            const nameVal = nameInput.value.trim();
            const waVal = waInput.value.trim();
            let isValid = true;

            // Validasi Nama
            if (nameVal.length < 2) {
                nameError.classList.remove('hidden');
                nameError.classList.add('flex');
                nameInput.classList.add('border-rose-400', 'bg-rose-50/30');
                isValid = false;
            } else {
                nameError.classList.add('hidden');
                nameError.classList.remove('flex');
                nameInput.classList.remove('border-rose-400', 'bg-rose-50/30');
            }

            // Validasi WhatsApp
            if (waVal.length < 8) {
                waError.classList.remove('hidden');
                waError.classList.add('flex');
                isValid = false;
            } else {
                waError.classList.add('hidden');
                waError.classList.remove('flex');
            }

            if (!isValid) return;

            // Tampilkan SweetAlert konfirmasi
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    html: `
                        <div class="flex flex-col items-center text-center pt-2">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4 border border-indigo-100/80">
                                <i class="fa-solid fa-user-pen"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5 tracking-tight">Konfirmasi Perubahan</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed max-w-[260px]">
                                Apakah Anda yakin ingin menyimpan perubahan data profil ini?
                            </p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    backdrop: 'rgba(15, 23, 42, 0.65)',
                    customClass: {
                        popup: 'custom-swal-popup !rounded-[2.25rem]',
                        actions: 'w-full flex flex-row flex-nowrap gap-3 mt-6 px-0',
                        confirmButton: 'flex-1 py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer',
                        cancelButton: 'flex-1 py-3.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl text-sm whitespace-nowrap text-center transition-all active:scale-[0.98] cursor-pointer'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                form.submit();
            }
        });
    });
</script>
