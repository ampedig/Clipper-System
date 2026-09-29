<nav class="bottom-nav relative z-50">
    <div class="flex justify-between items-center w-full px-2">
        <!-- 1. Beranda (Ringkasan saldo, campaign aktif, statistik clip) -->
        <a href="{{ route('app.home') }}"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'beranda' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-9 h-9 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-house-chimney text-[18px] transition-transform duration-300 {{ isset($active) && $active === 'beranda' ? 'scale-105' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'beranda' ? 'font-bold' : 'font-medium' }}">Beranda</span>
        </a>

        <!-- 2. Klip (Video yang sudah disubmit + status & views) -->
        <a href="{{ route('app.submissions.index') }}"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'submission' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-9 h-9 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-clapperboard text-[18px] transition-transform duration-300 {{ isset($active) && $active === 'submission' ? 'scale-105' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'submission' ? 'font-bold' : 'font-medium' }}">Clip
                Saya</span>
        </a>

        <!-- 3. Campaign (Floating Highlight Menu) -->
        <a href="{{ route('app.campaigns') }}"
            class="group flex flex-col items-center justify-center w-[20%] relative transition-all duration-300">
            <!-- Invisible placeholder matching other icons to align text perfectly -->
            <div class="w-10 h-10"></div>

            <!-- Floating Icon -->
            <div
                class="absolute -top-5 left-1/2 -translate-x-1/2 flex items-center justify-center rounded-full transition-transform duration-300 group-hover:scale-105 group-active:scale-95 shadow-md shadow-indigo-600/30 {{ isset($active) && $active === 'campaign' ? 'bg-indigo-700 text-white ring-4 ring-indigo-50' : 'bg-indigo-600 text-white ring-4 ring-white' }}"
                style="top: -20px; width: 50px; height: 50px;">
                <i class="fa-solid fa-fire-flame-curved" style="font-size: 19px; color: #ffffff;"></i>
            </div>

            <!-- Text label perfectly aligned -->
            <span
                class="text-[10px] tracking-wide mt-0.5 whitespace-nowrap {{ isset($active) && $active === 'campaign' ? 'font-bold text-indigo-600' : 'font-medium text-slate-400 group-hover:text-slate-600' }}">Campaign</span>
        </a>

        <!-- 4. Saldo (Saldo, mutasi, dan withdrawal) -->
        <a href="{{ route('app.wallet.index') }}"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'saldo' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-9 h-9 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-wallet text-[18px] transition-transform duration-300 {{ isset($active) && $active === 'saldo' ? 'scale-105' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'saldo' ? 'font-bold' : 'font-medium' }}">Saldo</span>
        </a>

        <!-- 5. Akun (Data akun & pengaturan) -->
        <a href="{{ route('app.profile') }}"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'akun' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-9 h-9 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-circle-user text-[18px] transition-transform duration-300 {{ isset($active) && $active === 'akun' ? 'scale-105' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'akun' ? 'font-bold' : 'font-medium' }}">Akun</span>
        </a>
    </div>
</nav>
