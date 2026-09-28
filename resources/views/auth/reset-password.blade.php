@include('app.partials.head', [
    'title' => 'Reset Kata Sandi',
    'description' => 'Atur ulang kata sandi akun AZCLIP Anda',
    'containerClass' => 'min-h-[100dvh] flex flex-col justify-center pb-0',
])

<style>
    /* Scoped override to ensure auth screen is vertically centered without bottom-nav gap */
    .mobile-container {
        padding-bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        min-height: 100dvh !important;
    }
</style>

<!-- Centered Modern Minimal Reset Password Screen (No Header) -->
<div class="w-full px-5 py-8 my-auto space-y-6">

    <!-- Hero Branding Header (Rata Tengah) -->
    <div class="text-center">
        <div
            class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center mx-auto mb-4 overflow-hidden p-2.5 shadow-sm">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AZCLIP Logo" class="w-full h-full object-contain">
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kata Sandi Baru</h1>
        <p class="text-xs text-slate-400 font-medium mt-1.5 max-w-[290px] mx-auto leading-relaxed">
            Silakan buat kata sandi baru untuk akun Anda.
        </p>
    </div>

    <!-- Reset Password Form Panel -->
    <form id="reset-form" method="POST" action="{{ route('password.store') }}"
        class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Field: Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Email
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('email') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                    readonly
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none opacity-70" />
            </div>
            <div id="email-error"
                class="{{ $errors->has('email') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="email-error-text">{{ $errors->first('email') ?? 'Email tidak valid' }}</span>
            </div>
        </div>

        <!-- Field: Password Baru -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Kata Sandi Baru
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('password') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter"
                    required autofocus autocomplete="new-password"
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none pr-11" />
                <button type="button"
                    class="toggle-password absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                    data-target="password" title="Tampilkan / Sembunyikan">
                    <i class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            <div id="password-error"
                class="{{ $errors->has('password') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="password-error-text">{{ $errors->first('password') ?? 'Kata sandi minimal 8 karakter' }}</span>
            </div>
        </div>

        <!-- Field: Konfirmasi Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Konfirmasi Kata Sandi
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('password_confirmation') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru"
                    required autocomplete="new-password"
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none pr-11" />
                <button type="button"
                    class="toggle-password absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                    data-target="password_confirmation" title="Tampilkan / Sembunyikan">
                    <i class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            <div id="password-confirm-error"
                class="{{ $errors->has('password_confirmation') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="password-confirm-error-text">{{ $errors->first('password_confirmation') ?? 'Kata sandi tidak cocok' }}</span>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" id="btn-submit"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span id="btn-text">Simpan Kata Sandi</span>
                <i id="btn-icon" class="fa-solid fa-check text-xs"></i>
            </button>
        </div>

    </form>
</div>

<!-- Interactive Script for Form Validation -->
<script>
    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    const resetForm = document.getElementById('reset-form');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordError = document.getElementById('password-error');
    const passwordErrorText = document.getElementById('password-error-text');
    const passwordConfirmError = document.getElementById('password-confirm-error');
    const passwordConfirmErrorText = document.getElementById('password-confirm-error-text');
    const btnSubmit = document.getElementById('btn-submit');
    const btnText = document.getElementById('btn-text');
    const btnIcon = document.getElementById('btn-icon');

    // Realtime clear errors on input
    passwordInput.addEventListener('input', () => {
        passwordError.classList.add('hidden');
        passwordError.classList.remove('flex');
        passwordInput.closest('div').classList.remove('border-rose-400');
        passwordInput.closest('div').classList.add('border-slate-200');
    });

    passwordConfirmInput.addEventListener('input', () => {
        passwordConfirmError.classList.add('hidden');
        passwordConfirmError.classList.remove('flex');
        passwordConfirmInput.closest('div').classList.remove('border-rose-400');
        passwordConfirmInput.closest('div').classList.add('border-slate-200');
    });

    resetForm.addEventListener('submit', (e) => {
        let isValid = true;
        const passwordVal = passwordInput.value;
        const passwordConfirmVal = passwordConfirmInput.value;

        // Validate Password
        if (!passwordVal || passwordVal.length < 8) {
            passwordErrorText.textContent = 'Kata sandi minimal 8 karakter';
            passwordError.classList.remove('hidden');
            passwordError.classList.add('flex');
            passwordInput.closest('div').classList.add('border-rose-400');
            passwordInput.closest('div').classList.remove('border-slate-200');
            isValid = false;
        }

        // Validate Confirm Password
        if (passwordVal !== passwordConfirmVal) {
            passwordConfirmErrorText.textContent = 'Konfirmasi kata sandi tidak cocok';
            passwordConfirmError.classList.remove('hidden');
            passwordConfirmError.classList.add('flex');
            passwordConfirmInput.closest('div').classList.add('border-rose-400');
            passwordConfirmInput.closest('div').classList.remove('border-slate-200');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Loading State
        btnSubmit.classList.add('pointer-events-none', 'opacity-80');
        btnText.textContent = 'Menyimpan...';
        btnIcon.className = 'fa-solid fa-circle-notch fa-spin text-xs';
    });
</script>

@include('app.partials.vendor-script')
