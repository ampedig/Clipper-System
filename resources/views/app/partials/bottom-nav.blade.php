<nav class="bottom-nav relative z-50">
    <div class="flex justify-between items-center w-full px-2">
        <!-- 1. Beranda (Ringkasan saldo, campaign aktif, statistik clip) -->
        <a href="{{ route('app.home') }}"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'beranda' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-10 h-10 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-house-chimney text-[22px] transition-transform duration-300 {{ isset($active) && $active === 'beranda' ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'beranda' ? 'font-bold' : 'font-medium' }}">Beranda</span>
        </a>

        <!-- 2. Klip (Video yang sudah disubmit + status & views) -->
        <a href="#"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'submission' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-10 h-10 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-clapperboard text-[22px] transition-transform duration-300 {{ isset($active) && $active === 'submission' ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'submission' ? 'font-bold' : 'font-medium' }}">Clip
                Saya</span>
        </a>

        <!-- 3. Campaign (Floating Highlight Menu) -->
        <a href="#"
            class="group flex flex-col items-center justify-center w-[20%] relative transition-all duration-300">
            <!-- Invisible placeholder matching other icons to align text perfectly -->
            <div class="w-10 h-10"></div>

            <!-- Floating Icon -->
            <div
                class="absolute -top-5 left-1/2 -translate-x-1/2 flex items-center justify-center w-14 h-14 rounded-full transition-transform duration-300 group-hover:scale-105 group-active:scale-95 shadow-lg shadow-indigo-600/30 {{ isset($active) && $active === 'campaign' ? 'bg-indigo-700 text-white ring-4 ring-indigo-50' : 'bg-indigo-600 text-white ring-4 ring-white' }}">
                <i class="fa-solid fa-fire-flame-curved text-xl"></i>
            </div>

            <!-- Text label perfectly aligned -->
            <span
                class="text-[10px] tracking-wide mt-0.5 whitespace-nowrap {{ isset($active) && $active === 'campaign' ? 'font-bold text-indigo-600' : 'font-medium text-slate-400 group-hover:text-slate-600' }}">Campaign</span>
        </a>

        <!-- 4. Saldo (Saldo, mutasi, dan withdrawal) -->
        <a href="#"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'saldo' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-10 h-10 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-wallet text-[22px] transition-transform duration-300 {{ isset($active) && $active === 'saldo' ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'saldo' ? 'font-bold' : 'font-medium' }}">Saldo</span>
        </a>

        <!-- 5. Profil (Data akun & pengaturan) -->
        <a href="#"
            class="group flex flex-col items-center justify-center w-[20%] transition-all duration-300 {{ isset($active) && $active === 'profil' ? 'text-indigo-600' : 'text-slate-400 hover:text-slate-600' }}">
            <div class="relative flex items-center justify-center w-10 h-10 rounded-full transition-all duration-300">
                <i
                    class="fa-solid fa-circle-user text-[22px] transition-transform duration-300 {{ isset($active) && $active === 'profil' ? 'scale-110' : '' }}"></i>
            </div>
            <span
                class="text-[10px] tracking-wide mt-0.5 {{ isset($active) && $active === 'profil' ? 'font-bold' : 'font-medium' }}">Profil</span>
        </a>
    </div>
</nav>
