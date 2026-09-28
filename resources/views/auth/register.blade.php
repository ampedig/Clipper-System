@include('app.partials.head', [
    'title' => 'Daftar',
    'description' => 'Daftar sebagai Clipper AZCLIP dan mulai hasilkan cuan',
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

<!-- Centered Modern Minimal Register Screen (No Header) -->
<div class="w-full px-5 py-8 my-auto space-y-6">

    <!-- Hero Branding Header (Rata Tengah) -->
    <div class="text-center">
        <div
            class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center mx-auto mb-4 overflow-hidden p-2.5 shadow-sm">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AZCLIP Logo" class="w-full h-full object-contain">
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Akun Baru</h1>
        <p class="text-xs text-slate-400 font-medium mt-1.5 max-w-[300px] mx-auto leading-relaxed">
            Daftar sebagai Clipper dan mulai hasilkan cuan dari konten klip video Anda.
        </p>
    </div>

    <!-- Register Form Panel (Modern Minimal Panel System - No Shadow) -->
    <form id="register-form" method="POST" action="{{ route('register') }}"
        class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4" novalidate>
        @csrf

        <!-- Session Status -->
        <x-auth-session-status class="mb-2" :status="session('status')" />

        <!-- Field 1: Nama Lengkap -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Nama Lengkap
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('name') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso" autocomplete="name" required autofocus
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none" />
            </div>
            <div id="name-error"
                class="{{ $errors->has('name') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="name-error-text">{{ $errors->first('name') ?? 'Nama lengkap tidak boleh kosong' }}</span>
            </div>
        </div>

        <!-- Field 2: Nomor WhatsApp -->
        <div>
            <label for="whatsapp" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Nomor WhatsApp
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('whatsapp') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}"
                    placeholder="Contoh: 081234567890" autocomplete="tel" required
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none" />
            </div>
            <div id="whatsapp-error"
                class="{{ $errors->has('whatsapp') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span
                    id="whatsapp-error-text">{{ $errors->first('whatsapp') ?? 'Nomor WhatsApp tidak valid (minimal 10 digit)' }}</span>
            </div>
        </div>

        <!-- Field 3: Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Email
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('email') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="nama@email.com" autocomplete="email" required
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none" />
            </div>
            <div id="email-error"
                class="{{ $errors->has('email') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="email-error-text">{{ $errors->first('email') ?? 'Email tidak boleh kosong' }}</span>
            </div>
        </div>

        <!-- Field 4: Kata Sandi -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Kata Sandi
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('password') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                    autocomplete="new-password" required
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
                <span
                    id="password-error-text">{{ $errors->first('password') ?? 'Kata sandi minimal 6 karakter' }}</span>
            </div>
        </div>

        <!-- Field 5: Konfirmasi Kata Sandi -->
        <div>
            <label for="password_confirmation"
                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Konfirmasi Kata Sandi
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('password_confirmation') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Ulangi kata sandi" autocomplete="new-password" required
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none pr-11" />
                <button type="button"
                    class="toggle-password absolute right-0 top-0 bottom-0 px-3.5 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors cursor-pointer"
                    data-target="password_confirmation" title="Tampilkan / Sembunyikan">
                    <i class="fa-regular fa-eye text-sm"></i>
                </button>
            </div>
            <div id="confirm-error"
                class="{{ $errors->has('password_confirmation') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span
                    id="confirm-error-text">{{ $errors->first('password_confirmation') ?? 'Konfirmasi kata sandi tidak cocok' }}</span>
            </div>
        </div>

        <!-- Checkbox: Syarat & Ketentuan -->
        <div class="pt-1">
            <label for="terms" class="flex items-start gap-2.5 cursor-pointer select-none group">
                <input type="checkbox" id="terms" name="terms" value="1"
                    {{ old('terms') ? 'checked' : '' }} required
                    class="w-4 h-4 mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-0 accent-indigo-600 cursor-pointer shrink-0" />
                <span class="text-xs text-slate-600 leading-relaxed font-medium">
                    Saya setuju dengan <a href="{{ route('app.policy') }}"
                        class="text-indigo-600 font-bold hover:underline">Ketentuan Layanan dan Kebijakan Privasi</a>.
                </span>
            </label>
            <div id="terms-error"
                class="{{ $errors->has('terms') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="terms-error-text">{{ $errors->first('terms') ?? 'Anda harus menyetujui ketentuan layanan' }}</span>
            </div>
        </div>

        <!-- Submit Button (Daftar Sekarang) -->
        <div class="pt-1">
            <button type="submit" id="btn-submit"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span id="btn-text">Daftar Sekarang</span>
                <i id="btn-icon" class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>

    </form>

    <!-- Login Link Prompt (Rata Tengah) -->
    <div class="text-center">
        <p class="text-xs text-slate-500 font-medium">
            Sudah memiliki akun?
            <a href="{{ route('login') }}"
                class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors ml-1">
                Masuk Sekarang
            </a>
        </p>
    </div>

</div>

<!-- Interactive Script for Form Validation & Registration -->
<script>
    // 1. Toggle Password Visibility for both password fields
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

    // 2. Realtime input error clearing
    const registerForm = document.getElementById('register-form');
    const nameInput = document.getElementById('name');
    const whatsappInput = document.getElementById('whatsapp');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const termsCheckbox = document.getElementById('terms');

    const nameError = document.getElementById('name-error');
    const nameErrorText = document.getElementById('name-error-text');
    const whatsappError = document.getElementById('whatsapp-error');
    const whatsappErrorText = document.getElementById('whatsapp-error-text');
    const emailError = document.getElementById('email-error');
    const emailErrorText = document.getElementById('email-error-text');
    const passwordError = document.getElementById('password-error');
    const passwordErrorText = document.getElementById('password-error-text');
    const confirmError = document.getElementById('confirm-error');
    const confirmErrorText = document.getElementById('confirm-error-text');
    const termsError = document.getElementById('terms-error');
    const termsErrorText = document.getElementById('terms-error-text');

    const btnSubmit = document.getElementById('btn-submit');
    const btnText = document.getElementById('btn-text');
    const btnIcon = document.getElementById('btn-icon');

    function clearError(input, errorElement) {
        if (!errorElement) return;
        errorElement.classList.add('hidden');
        errorElement.classList.remove('flex');
        if (input) {
            const parent = input.closest('div');
            if (parent) {
                parent.classList.remove('border-rose-400');
                parent.classList.add('border-slate-200');
            }
        }
    }

    function setError(input, errorElement, textElement, message) {
        if (textElement) textElement.textContent = message;
        if (errorElement) {
            errorElement.classList.remove('hidden');
            errorElement.classList.add('flex');
        }
        if (input) {
            const parent = input.closest('div');
            if (parent) {
                parent.classList.add('border-rose-400');
                parent.classList.remove('border-slate-200');
            }
        }
    }

    nameInput.addEventListener('input', () => clearError(nameInput, nameError));
    whatsappInput.addEventListener('input', () => clearError(whatsappInput, whatsappError));
    emailInput.addEventListener('input', () => clearError(emailInput, emailError));
    passwordInput.addEventListener('input', () => clearError(passwordInput, passwordError));
    confirmInput.addEventListener('input', () => clearError(confirmInput, confirmError));
    termsCheckbox.addEventListener('change', () => {
        if (termsCheckbox.checked) {
            termsError.classList.add('hidden');
            termsError.classList.remove('flex');
        }
    });

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    registerForm.addEventListener('submit', (e) => {
        let isValid = true;
        const nameVal = nameInput.value.trim();
        const whatsappVal = whatsappInput.value.trim();
        const emailVal = emailInput.value.trim();
        const passwordVal = passwordInput.value;
        const confirmVal = confirmInput.value;

        // Validate Name
        if (!nameVal) {
            setError(nameInput, nameError, nameErrorText, 'Nama lengkap tidak boleh kosong');
            isValid = false;
        } else {
            clearError(nameInput, nameError);
        }

        // Validate WhatsApp
        const cleanPhone = whatsappVal.replace(/[^0-9]/g, '');
        if (!whatsappVal) {
            setError(whatsappInput, whatsappError, whatsappErrorText, 'Nomor WhatsApp tidak boleh kosong');
            isValid = false;
        } else if (cleanPhone.length < 10) {
            setError(whatsappInput, whatsappError, whatsappErrorText, 'Nomor WhatsApp minimal 10 digit angka');
            isValid = false;
        } else {
            clearError(whatsappInput, whatsappError);
        }

        // Validate Email
        if (!emailVal) {
            setError(emailInput, emailError, emailErrorText, 'Email tidak boleh kosong');
            isValid = false;
        } else if (!validateEmail(emailVal)) {
            setError(emailInput, emailError, emailErrorText, 'Format email tidak valid');
            isValid = false;
        } else {
            clearError(emailInput, emailError);
        }

        // Validate Password
        if (!passwordVal) {
            setError(passwordInput, passwordError, passwordErrorText, 'Kata sandi tidak boleh kosong');
            isValid = false;
        } else if (passwordVal.length < 6) {
            setError(passwordInput, passwordError, passwordErrorText, 'Kata sandi minimal 6 karakter');
            isValid = false;
        } else {
            clearError(passwordInput, passwordError);
        }

        // Validate Confirm Password
        if (!confirmVal) {
            setError(confirmInput, confirmError, confirmErrorText, 'Konfirmasi kata sandi wajib diisi');
            isValid = false;
        } else if (confirmVal !== passwordVal) {
            setError(confirmInput, confirmError, confirmErrorText, 'Konfirmasi kata sandi tidak cocok');
            isValid = false;
        } else {
            clearError(confirmInput, confirmError);
        }

        // Validate Terms
        if (!termsCheckbox.checked) {
            if (termsErrorText) termsErrorText.textContent = 'Anda harus menyetujui ketentuan layanan';
            termsError.classList.remove('hidden');
            termsError.classList.add('flex');
            isValid = false;
        } else {
            termsError.classList.add('hidden');
            termsError.classList.remove('flex');
        }

        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Loading State during actual form submission
        btnSubmit.classList.add('pointer-events-none', 'opacity-80');
        btnText.textContent = 'Mendaftarkan...';
        btnIcon.className = 'fa-solid fa-circle-notch fa-spin text-xs';
    });
</script>

@include('app.partials.vendor-script')
