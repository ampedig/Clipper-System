@props(['status'])

@if ($status)
    @php
        // Translasi manual untuk pesan bawaan Laravel
        $displayStatus = $status;
        if ($status === 'We have emailed your password reset link.') {
            $displayStatus = 'Tautan reset kata sandi telah dikirim ke email Anda.';
        } elseif ($status === 'Your password has been reset.') {
            $displayStatus = 'Kata sandi Anda berhasil diatur ulang.';
        }
    @endphp
    
    <div {{ $attributes->merge(['class' => 'flex items-center gap-3 bg-emerald-50 p-4 rounded-xl']) }}>
        <div class="flex-shrink-0 flex items-center justify-center">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
        </div>
        <p class="text-sm font-semibold text-emerald-800 leading-relaxed">
            {{ $displayStatus }}
        </p>
    </div>
@endif
