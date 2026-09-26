@include('app.partials.head', [
    'title' => 'Pusat Bantuan',
])

<div class="min-h-[100dvh] bg-slate-50 relative pb-12">

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
            <h1 class="text-[17px] font-bold text-slate-900 tracking-tight leading-none">Pusat Bantuan</h1>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="p-4 space-y-4">

        <!-- Hero Card: Support Overview -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5">
            <div class="flex items-center gap-2.5 mb-3">
                <div
                    class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100/80 flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h2 class="text-sm font-extrabold text-slate-900 tracking-tight leading-snug">AZCLIP Customer Care</h2>
            </div>

            <p class="text-xs text-slate-500 font-medium leading-relaxed">
                Punya kendala teknis, pertanyaan campaign, verifikasi video, atau pencairan komisi? Tim dukungan AZCLIP siap membantu Anda setiap hari.
            </p>

            <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px]">
                <span class="text-slate-400 font-medium flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    Jam Operasional
                </span>
                <span class="font-bold text-slate-700">08:00 – 22:00 WIB</span>
            </div>
        </div>

        @if(!empty($settings['cs_whatsapp']) || !empty($settings['cs_telegram']))
        <!-- Section Title: Channel List -->
        <div class="px-1">
            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pilih Saluran Bantuan</h3>
        </div>

        <!-- Channel Buttons Container -->
        <div class="space-y-3">


            @if(!empty($settings['cs_whatsapp']))
            <!-- Channel 1: WhatsApp Support -->
            <a href="https://wa.me/62{{ ltrim($settings['cs_whatsapp'], '0') }}?text=Halo%20Admin%20AZCLIP,%20saya%20butuh%20bantuan"
                target="_blank" rel="noopener noreferrer"
                class="group block bg-white rounded-2xl border border-slate-200 hover:border-emerald-300 p-4 transition-all duration-200 hover:shadow-sm active:scale-[0.99] cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100/80 flex items-center justify-center text-emerald-600 text-2xl shrink-0 group-hover:scale-105 transition-transform">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">
                                CS WhatsApp
                            </h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                +62 {{ $settings['cs_whatsapp'] }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 group-hover:text-emerald-600 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-colors shrink-0">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </div>
                </div>
            </a>
            @endif

            @if(!empty($settings['cs_telegram']))
            <!-- Channel 2: Telegram Support -->
            <a href="https://t.me/{{ $settings['cs_telegram'] }}"
                target="_blank" rel="noopener noreferrer"
                class="group block bg-white rounded-2xl border border-slate-200 hover:border-telegram p-4 transition-all duration-200 hover:shadow-sm active:scale-[0.99] cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div
                            class="w-12 h-12 rounded-2xl bg-telegram-soft border border-telegram-soft flex items-center justify-center text-telegram text-2xl shrink-0 group-hover:scale-105 transition-transform"
                            style="background-color: #E8F5FD; border: 1px solid #CBE7F8; color: #24A1DE;">
                            <i class="fa-brands fa-telegram" style="color: #24A1DE;"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 group-hover:text-telegram transition-colors">
                                CS Telegram
                            </h4>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                {{ '@' . $settings['cs_telegram'] }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200/80 flex items-center justify-center text-slate-400 group-hover:text-telegram group-hover:bg-telegram-soft group-hover:border-telegram-soft transition-colors shrink-0">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </div>
                </div>
            </a>
            @endif

        </div>
        @endif

        <!-- Support Guidelines Info Card -->
        <div class="bg-indigo-50/70 border border-indigo-100/80 rounded-2xl p-4 space-y-2">
            <div class="flex items-center gap-2 text-indigo-700">
                <i class="fa-solid fa-circle-info text-sm"></i>
                <h4 class="text-xs font-bold">Tips Bantuan Lebih Cepat</h4>
            </div>
            <ul class="text-[11px] text-slate-600 space-y-1.5 pl-5 list-disc font-medium leading-relaxed">
                <li>Sertakan alamat <strong>Email akun</strong> atau <strong>Nomor WhatsApp</strong> yang terdaftar.</li>
                <li>Lampirkan <strong>Link submission campaign</strong> atau tangkapan layar (screenshot) jika ada kendala sistem.</li>
                <li>Jelaskan pertanyaan atau kendala Anda secara singkat dan jelas.</li>
            </ul>
        </div>

    </main>

</div>

@include('app.partials.vendor-script')
