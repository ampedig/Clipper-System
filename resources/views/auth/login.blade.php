@include('app.partials.head', [
    'title' => 'Masuk',
    'description' => 'Masuk ke akun AZCLIP Anda',
    'containerClass' => 'min-h-[100dvh] flex flex-col justify-center pb-0',
])

<style>
    /* Ensure auth screen is vertically centered without bottom-nav gap */
    .mobile-container {
        padding-bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        min-height: 100dvh !important;
    }
</style>

<!-- Centered Modern Minimal Login Screen (No Top App Bar Heading) -->
<div class="w-full px-5 py-8 my-auto space-y-6">

    <!-- Hero Branding Header (Rata Tengah) -->
    <div class="text-center">
        <div
            class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center mx-auto mb-4 overflow-hidden p-2.5 shadow-sm">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AZCLIP Logo" class="w-full h-full object-contain">
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke Akun</h1>
        <p class="text-xs text-slate-400 font-medium mt-1.5 max-w-[280px] mx-auto leading-relaxed">
            Kelola konten klip dan pantau komisi Anda dengan mudah di Clipper Studio.
        </p>
    </div>

    <!-- Login Form Panel (Modern Minimal Panel System - No Shadow) -->
    <form id="login-form" method="POST" action="{{ route('login') }}"
        class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4" novalidate>
        @csrf

        <!-- Session Status -->
        <x-auth-session-status class="mb-2" :status="session('status')" />

        <!-- Field 1: Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Email
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('email') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="nama@email.com" autocomplete="email" required autofocus
                    class="w-full px-4 py-3.5 bg-transparent text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none" />
            </div>
            <div id="email-error"
                class="{{ $errors->has('email') ? 'flex' : 'hidden' }} mt-1.5 items-center gap-1.5 text-xs font-semibold text-rose-500">
                <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                <span id="email-error-text">{{ $errors->first('email') ?? 'Email tidak boleh kosong' }}</span>
            </div>
        </div>

        <!-- Field 2: Kata Sandi -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Kata Sandi
            </label>
            <div
                class="relative flex items-center rounded-xl bg-slate-50/80 border {{ $errors->has('password') ? 'border-rose-400' : 'border-slate-200' }} focus-within:border-indigo-500 focus-within:bg-white transition-colors overflow-hidden">
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi"
                    autocomplete="current-password" required
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
                <span id="password-error-text">{{ $errors->first('password') ?? 'Kata sandi minimal 6 karakter' }}</span>
            </div>
        </div>

        <!-- Row: Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember-me" class="flex items-center gap-2 cursor-pointer select-none group">
                <input type="checkbox" id="remember-me" name="remember" value="1"
                    {{ old('remember', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-0 accent-indigo-600 cursor-pointer" />
                <span class="text-xs font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button (Masuk) -->
        <div class="pt-1">
            <button type="submit" id="btn-submit"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span id="btn-text">Masuk</span>
                <i id="btn-icon" class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>

    </form>

    <!-- Register Link Prompt (Rata Tengah) -->
    <div class="text-center">
        <p class="text-xs text-slate-500 font-medium">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors ml-1">
                Daftar Sekarang
            </a>
        </p>
    </div>

</div>

<!-- Interactive Script for Login & Form Validation -->
<script>
    // 1. Prefill Remembered Email if available
    const savedEmail = localStorage.getItem('clipper_remember_email');
    if (savedEmail) {
        const emailInput = document.getElementById('email');
        const rememberCheckbox = document.getElementById('remember-me');
        if (emailInput && !emailInput.value) {
            emailInput.value = savedEmail;
        }
        if (rememberCheckbox) {
            rememberCheckbox.checked = true;
        }
    }

    // 2. Toggle Password Visibility
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

    // 3. Form Validation & Submission
    const loginForm = document.getElementById('login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('remember-me');
    const emailError = document.getElementById('email-error');
    const emailErrorText = document.getElementById('email-error-text');
    const passwordError = document.getElementById('password-error');
    const passwordErrorText = document.getElementById('password-error-text');
    const btnSubmit = document.getElementById('btn-submit');
    const btnText = document.getElementById('btn-text');
    const btnIcon = document.getElementById('btn-icon');

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Realtime clear errors on input
    emailInput.addEventListener('input', () => {
        emailError.classList.add('hidden');
        emailError.classList.remove('flex');
        emailInput.closest('div').classList.remove('border-rose-400');
        emailInput.closest('div').classList.add('border-slate-200');
    });

    passwordInput.addEventListener('input', () => {
        passwordError.classList.add('hidden');
        passwordError.classList.remove('flex');
        passwordInput.closest('div').classList.remove('border-rose-400');
        passwordInput.closest('div').classList.add('border-slate-200');
    });

    loginForm.addEventListener('submit', (e) => {
        let isValid = true;
        const emailVal = emailInput.value.trim();
        const passwordVal = passwordInput.value;

        // Validate Email
        if (!emailVal) {
            emailErrorText.textContent = 'Email tidak boleh kosong';
            emailError.classList.remove('hidden');
            emailError.classList.add('flex');
            emailInput.closest('div').classList.add('border-rose-400');
            emailInput.closest('div').classList.remove('border-slate-200');
            isValid = false;
        } else if (!validateEmail(emailVal)) {
            emailErrorText.textContent = 'Format email tidak valid';
            emailError.classList.remove('hidden');
            emailError.classList.add('flex');
            emailInput.closest('div').classList.add('border-rose-400');
            emailInput.closest('div').classList.remove('border-slate-200');
            isValid = false;
        } else {
            emailError.classList.add('hidden');
            emailError.classList.remove('flex');
            emailInput.closest('div').classList.remove('border-rose-400');
            emailInput.closest('div').classList.add('border-slate-200');
        }

        // Validate Password
        if (!passwordVal) {
            passwordErrorText.textContent = 'Kata sandi tidak boleh kosong';
            passwordError.classList.remove('hidden');
            passwordError.classList.add('flex');
            passwordInput.closest('div').classList.add('border-rose-400');
            passwordInput.closest('div').classList.remove('border-slate-200');
            isValid = false;
        } else if (passwordVal.length < 6) {
            passwordErrorText.textContent = 'Kata sandi minimal 6 karakter';
            passwordError.classList.remove('hidden');
            passwordError.classList.add('flex');
            passwordInput.closest('div').classList.add('border-rose-400');
            passwordInput.closest('div').classList.remove('border-slate-200');
            isValid = false;
        } else {
            passwordError.classList.add('hidden');
            passwordError.classList.remove('flex');
            passwordInput.closest('div').classList.remove('border-rose-400');
            passwordInput.closest('div').classList.add('border-slate-200');
        }

        if (!isValid) {
            e.preventDefault();
            return;
        }

        // Save or Clear Remember Me in localStorage
        if (rememberCheckbox.checked) {
            localStorage.setItem('clipper_remember_email', emailVal);
        } else {
            localStorage.removeItem('clipper_remember_email');
        }

        // Loading State
        btnSubmit.classList.add('pointer-events-none', 'opacity-80');
        btnText.textContent = 'Memproses...';
        btnIcon.className = 'fa-solid fa-circle-notch fa-spin text-xs';
    });
</script>

@include('app.partials.vendor-script')

