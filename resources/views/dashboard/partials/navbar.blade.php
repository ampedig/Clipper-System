<header
    class="flex items-center justify-between px-4 md:px-8 h-20 bg-white dark:bg-[#222222] border-b border-slate-200 dark:border-[#2e2e2e] sticky top-0 z-20 shrink-0">
    <div class="flex items-center gap-2 md:gap-4">
        <button id="sidebarToggle"
            class="p-2.5 text-slate-400 hover:text-brand-500 dark:text-slate-500 dark:hover:text-brand-400 rounded-2xl transition-all">
            <i class="fa-solid fa-bars-staggered text-xl"></i>
        </button>

        <div class="flex items-center relative group">
            <i
                class="fa-solid fa-magnifying-glass absolute left-3.5 md:left-4 text-slate-400 dark:text-slate-500 text-lg group-focus-within:text-brand-500 transition-colors"></i>
            <input type="text" placeholder="Cari..."
                class="pl-10 md:pl-12 pr-4 py-2.5 md:py-3 bg-slate-50 dark:bg-[#161616] border border-slate-200 dark:border-[#2e2e2e] focus:bg-white dark:focus:bg-[#1f1f1f] focus:border-brand-500 focus:ring-0 focus:outline-none rounded-full text-sm text-slate-700 dark:text-slate-200 w-48 sm:w-64 md:w-80 transition-all placeholder-slate-400 dark:placeholder-slate-500 font-medium">
        </div>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-3">
        <!-- Quick Dark Theme Toggle (Desktop Only) -->
        <button id="darkThemeToggle"
            class="p-2.5 text-slate-400 hover:text-brand-500 dark:text-slate-500 dark:hover:text-brand-400 rounded-2xl transition-all cursor-pointer hidden md:block"
            title="Toggle Dark Theme">
            <i id="themeIcon" class="theme-icon fa-solid fa-moon text-xl" style="font-weight: 900;"></i>
        </button>
        <button id="fullscreenToggle"
            class="p-2.5 text-slate-400 hover:text-brand-500 dark:text-slate-500 dark:hover:text-brand-400 rounded-2xl transition-all hidden md:block cursor-pointer"
            title="Toggle Fullscreen">
            <i id="fullscreenIcon" class="fa-solid fa-expand text-xl" style="font-weight: 900;"></i>
        </button>
        <!-- Theme Settings Toggle (Visible on Mobile & Desktop) -->
        <button id="themeSettingsToggle"
            class="p-2 sm:p-2.5 text-slate-400 hover:text-brand-500 dark:text-slate-500 dark:hover:text-brand-400 rounded-2xl transition-all cursor-pointer"
            title="Theme Settings">
            <i class="fa-solid fa-gear text-lg sm:text-xl" style="font-weight: 900;"></i>
        </button>
        <div class="h-8 w-px bg-slate-200 dark:bg-[#2e2e2e] mx-1 sm:mx-2 hidden md:block"></div>
        <div x-data="{ open: false }" @click.away="open = false" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-2 sm:gap-3 hover:bg-slate-50 dark:hover:bg-[#2e2e2e] p-1 sm:p-1.5 sm:pr-2 rounded-2xl transition-colors focus:outline-none cursor-pointer">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Moh Ma'sum</p>
                    <p class="text-xs font-medium text-brand-600 dark:text-brand-400">Admin</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=Moh+Masum&background=3b82f6&color=fff&bold=true"
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-white dark:border-[#2e2e2e] hover:opacity-90 transition">
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-2xl p-2 z-50 shadow-none"
                style="display: none;">

                <div class="px-3 py-2 border-b border-slate-100 dark:border-[#2e2e2e] mb-1">
                    <p class="text-xs font-semibold text-slate-800 dark:text-white">Moh Ma'sum</p>
                    <p class="text-[11px] font-medium text-brand-600 dark:text-brand-400">Admin</p>
                </div>

                <!-- Theme Settings Trigger Button -->
                <button @click="open = false; $dispatch('open-theme-settings')" type="button"
                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#2e2e2e] transition-colors text-left cursor-pointer">
                    <i class="fa-solid fa-palette w-4 text-center text-slate-400 dark:text-slate-500"></i>
                    <span>Pengaturan Tema</span>
                </button>

                <a href="profil.html"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#2e2e2e] transition-colors">
                    <i class="fa-regular fa-user w-4 text-center text-slate-400 dark:text-slate-500"></i>
                    <span>Profil</span>
                </a>
                <a href="settings.html"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-[#2e2e2e] transition-colors">
                    <i class="fa-solid fa-sliders w-4 text-center text-slate-400 dark:text-slate-500"></i>
                    <span>Settings</span>
                </a>
                <hr class="border-slate-100 dark:border-[#2e2e2e] my-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Theme Settings Drawer (Off-canvas) -->
    <div x-data="{ open: false }" @open-theme-settings.window="open = true" @keydown.escape.window="open = false"
        x-show="open" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">

        <!-- Backdrop -->
        <div x-show="open" x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in-out duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="open = false"
            class="absolute inset-0 bg-slate-950/40 backdrop-blur-xs"></div>

        <!-- Drawer container -->
        <div class="absolute inset-y-0 right-0 max-w-full flex">
            <div x-show="open" x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="w-full sm:w-80 bg-white dark:bg-[#222222] border-l border-slate-200 dark:border-[#2e2e2e] p-6 flex flex-col justify-between shadow-2xl">

                <div>
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between pb-5 border-b border-slate-100 dark:border-[#2e2e2e] mb-6">
                        <h3 class="font-semibold text-slate-800 dark:text-white text-base">Pengaturan Tema</h3>
                        <button @click="open = false"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-lg">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Theme Switcher Options -->
                    <div class="space-y-6">
                        <!-- Color Scheme -->
                        <div>
                            <span
                                class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-3">Color
                                Scheme</span>
                            <div class="grid grid-cols-2 gap-3">
                                <button onclick="setThemeScheme('light')" id="scheme-light-btn"
                                    class="flex flex-col items-center gap-2 p-3 bg-slate-50 dark:bg-[#161616] border-2 border-transparent rounded-2xl hover:bg-slate-100 dark:hover:bg-[#1f1f1f] transition text-slate-700 dark:text-slate-300 focus:outline-none cursor-pointer">
                                    <i class="fa-solid fa-sun text-lg"></i>
                                    <span class="text-xs font-semibold">Light</span>
                                </button>
                                <button onclick="setThemeScheme('dark')" id="scheme-dark-btn"
                                    class="flex flex-col items-center gap-2 p-3 bg-slate-50 dark:bg-[#161616] border-2 border-transparent rounded-2xl hover:bg-slate-100 dark:hover:bg-[#1f1f1f] transition text-slate-700 dark:text-slate-300 focus:outline-none cursor-pointer">
                                    <i class="fa-solid fa-moon text-lg"></i>
                                    <span class="text-xs font-semibold">Dark</span>
                                </button>
                            </div>
                        </div>

                        <!-- Warna Sidebar -->
                        <div>
                            <span
                                class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Warna
                                Sidebar</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 block mb-3">Pilih salah satu
                                warna background sidebar</span>
                            <div class="flex flex-wrap gap-2.5 justify-start">
                                <!-- Default / Putih -->
                                <button onclick="setBarColor('default')" id="bar-default-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Default / Putih">
                                    <span
                                        class="w-7 h-7 bg-white dark:bg-[#222222] border border-slate-200 dark:border-[#2e2e2e] rounded-full"></span>
                                </button>
                                <!-- Midnight Navy -->
                                <button onclick="setBarColor('midnight')" id="bar-midnight-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Midnight Navy">
                                    <span class="w-7 h-7 bg-[#1e293b] border border-slate-700/20 rounded-full"></span>
                                </button>
                                <!-- Deep Indigo -->
                                <button onclick="setBarColor('indigo')" id="bar-indigo-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Deep Indigo">
                                    <span class="w-7 h-7 bg-[#1e1b4b] border border-indigo-950/20 rounded-full"></span>
                                </button>
                                <!-- Deep Plum -->
                                <button onclick="setBarColor('plum')" id="bar-plum-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Deep Plum">
                                    <span class="w-7 h-7 bg-[#231034] border border-purple-950/20 rounded-full"></span>
                                </button>
                                <!-- Burgundy -->
                                <button onclick="setBarColor('burgundy')" id="bar-burgundy-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Burgundy">
                                    <span class="w-7 h-7 bg-[#3c1220] border border-red-950/20 rounded-full"></span>
                                </button>
                                <!-- Dark Emerald -->
                                <button onclick="setBarColor('emerald')" id="bar-emerald-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Dark Emerald">
                                    <span
                                        class="w-7 h-7 bg-[#092c1e] border border-emerald-950/20 rounded-full"></span>
                                </button>
                                <!-- Dark Espresso -->
                                <button onclick="setBarColor('espresso')" id="bar-espresso-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Dark Espresso">
                                    <span class="w-7 h-7 bg-[#251814] border border-yellow-950/20 rounded-full"></span>
                                </button>
                                <!-- Charcoal Gray -->
                                <button onclick="setBarColor('charcoal')" id="bar-charcoal-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Charcoal Gray">
                                    <span class="w-7 h-7 bg-[#2d3748] border border-slate-700/20 rounded-full"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Warna Primary -->
                        <div>
                            <span
                                class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Warna
                                Primary</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 block mb-3">Pilih warna aksen
                                utama dashboard</span>
                            <div class="flex flex-wrap gap-2.5 justify-start">
                                <!-- Default Blue -->
                                <button onclick="setPrimaryColor('default')" id="primary-default-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Classic Blue">
                                    <span class="w-7 h-7 bg-[#3b82f6] border border-blue-600/20 rounded-full"></span>
                                </button>
                                <!-- Indigo -->
                                <button onclick="setPrimaryColor('indigo')" id="primary-indigo-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Royal Indigo">
                                    <span class="w-7 h-7 bg-[#4f46e5] border border-indigo-600/20 rounded-full"></span>
                                </button>
                                <!-- Purple -->
                                <button onclick="setPrimaryColor('purple')" id="primary-purple-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Plum Purple">
                                    <span class="w-7 h-7 bg-[#8b5cf6] border border-purple-600/20 rounded-full"></span>
                                </button>
                                <!-- Rose -->
                                <button onclick="setPrimaryColor('rose')" id="primary-rose-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Creative Rose">
                                    <span class="w-7 h-7 bg-[#f43f5e] border border-rose-600/20 rounded-full"></span>
                                </button>
                                <!-- Cyan -->
                                <button onclick="setPrimaryColor('cyan')" id="primary-cyan-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Modern Cyan">
                                    <span class="w-7 h-7 bg-[#06b6d4] border border-cyan-600/20 rounded-full"></span>
                                </button>
                                <!-- Emerald -->
                                <button onclick="setPrimaryColor('emerald')" id="primary-emerald-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Fresh Jade">
                                    <span
                                        class="w-7 h-7 bg-[#10b981] border border-emerald-600/20 rounded-full"></span>
                                </button>
                                <!-- Amber -->
                                <button onclick="setPrimaryColor('amber')" id="primary-amber-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Energetic Amber">
                                    <span class="w-7 h-7 bg-[#f59e0b] border border-yellow-600/20 rounded-full"></span>
                                </button>
                                <!-- Orange -->
                                <button onclick="setPrimaryColor('orange')" id="primary-orange-btn"
                                    class="w-10 h-10 border-2 border-transparent rounded-full flex items-center justify-center cursor-pointer transition focus:outline-none hover:scale-105"
                                    title="Earthy Terracotta">
                                    <span class="w-7 h-7 bg-[#f97316] border border-orange-600/20 rounded-full"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer / Reset Button -->
                <div class="pt-6 border-t border-slate-100 dark:border-[#2e2e2e]">
                    <button onclick="resetThemeCustomizer()"
                        class="btn btn-primary w-full py-2.5 rounded-xl text-xs font-semibold">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Reset Default
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>
