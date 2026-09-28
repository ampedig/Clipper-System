@include('app.partials.head', [
    'title' => 'Lupa Kata Sandi',
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

<!-- Centered Modern Minimal Forgot Password Screen (No Header) -->
<div class="w-full px-5 py-8 my-auto space-y-6">

    <!-- Hero Branding Header (Rata Tengah) -->
    <div class="text-center">
        <div
            class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center mx-auto mb-4 overflow-hidden p-2.5 shadow-sm">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AZCLIP Logo" class="w-full h-full object-contain">
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lupa Kata Sandi?</h1>
        <p class="text-xs text-slate-400 font-medium mt-1.5 max-w-[290px] mx-auto leading-relaxed">
            Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>
    </div>

    <!-- Reset Password Form Panel (Modern Minimal Panel System - No Shadow) -->
    <form id="forgot-form" method="POST" action="{{ route('password.email') }}"
        class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4" novalidate>
        @csrf

        <!-- Session Status Alert -->
        <x-auth-session-status class="mb-2" :status="session('status')" />

        <!-- Field: Email Terdaftar -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Email Terdaftar
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

        <!-- Submit Button (Kirim Tautan) -->
        <div class="pt-1">
            <button type="submit" id="btn-submit"
                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer">
                <span id="btn-text">Kirim Tautan Reset</span>
                <i id="btn-icon" class="fa-solid fa-paper-plane text-xs"></i>
            </button>
        </div>

    </form>

    <!-- Back to Login Link Prompt (Rata Tengah) -->
    <div class="text-center">
        <p class="text-xs text-slate-500 font-medium">
            Sudah ingat kata sandi Anda?
            <a href="{{ route('login') }}"
                class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline transition-colors ml-1">
                Kembali ke Masuk
            </a>
        </p>
    </div>

</div>

<!-- Interactive Script for Form Validation & Submission -->
<script>
    const forgotForm = document.getElementById('forgot-form');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const emailErrorText = document.getElementById('email-error-text');
    const btnSubmit = document.getElementById('btn-submit');
    const btnText = document.getElementById('btn-text');
    const btnIcon = document.getElementById('btn-icon');

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Clear error when typing
    emailInput.addEventListener('input', () => {
        emailError.classList.add('hidden');
        emailError.classList.remove('flex');
        emailInput.closest('div').classList.remove('border-rose-400');
        emailInput.closest('div').classList.add('border-slate-200');
    });

    forgotForm.addEventListener('submit', (e) => {
        const emailVal = emailInput.value.trim();

        if (!emailVal) {
            e.preventDefault();
            emailErrorText.textContent = 'Email tidak boleh kosong';
            emailError.classList.remove('hidden');
            emailError.classList.add('flex');
            emailInput.closest('div').classList.add('border-rose-400');
            emailInput.closest('div').classList.remove('border-slate-200');
            return;
        }

        if (!validateEmail(emailVal)) {
            e.preventDefault();
            emailErrorText.textContent = 'Format email tidak valid';
            emailError.classList.remove('hidden');
            emailError.classList.add('flex');
            emailInput.closest('div').classList.add('border-rose-400');
            emailInput.closest('div').classList.remove('border-slate-200');
            return;
        }

        emailError.classList.add('hidden');
        emailError.classList.remove('flex');
        emailInput.closest('div').classList.remove('border-rose-400');
        emailInput.closest('div').classList.add('border-slate-200');

        // Loading State
        btnSubmit.classList.add('pointer-events-none', 'opacity-80');
        btnText.textContent = 'Mengirim...';
        btnIcon.className = 'fa-solid fa-circle-notch fa-spin text-xs';
    });
</script>

@include('app.partials.vendor-script')
