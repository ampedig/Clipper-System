<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-[#222222] border-r border-[#2e2e2e] transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col nav-text-sm">

    <!-- Logo -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-[#2e2e2e] logo-wrapper">
        <div class="flex items-center gap-3 font-semibold text-lg tracking-tight text-white overflow-hidden logo-group">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AMPEDIG Logo" class="w-10 h-10 rounded-lg shrink-0">
            <span class="logo-text whitespace-nowrap">AMPEDIG</span>
        </div>
    </div>

    <!-- Navigation Wrapper -->
    <div id="sidebarHoverArea" class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto custom-scrollbar">

            <p class="sb-title t-sidebar-title">Main Menu</p>

            <a href="{{ route('dashboard') }}" class="sb-item t-sidebar {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high sb-icon"></i>
                <span>Dashboard</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Clip</p>

            <a href="clipp-campaign.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-video sb-icon"></i> <span>List Clip</span>
            </a>

            <a href="clip-submission.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-video sb-icon"></i> <span>Pengajuan</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Komisi</p>

            <a href="wallet-transactions.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-cash-register sb-icon"></i> <span>Riwayat</span>
            </a>

            <a href="withdraw.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-money-bill-transfer sb-icon"></i> <span>Withdraw</span>
            </a>

            <a href="withdraw-channel.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-network-wired sb-icon"></i> <span>Metode WD</span>
            </a>


            <p class="sb-title sb-title--spaced t-sidebar-title">Pengguna</p>

            <a href="clipper.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-user-cog sb-icon"></i> <span>Clipper</span>
            </a>

            <a href="{{ route('administrators.index') }}" class="sb-item t-sidebar {{ request()->routeIs('administrators.index') ? 'active' : '' }}">
                <i class="fa-solid fa-user-cog sb-icon"></i> <span>Administrator</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Pengaturan</p>

            <a href="settings.html" class="sb-item t-sidebar">
                <i class="fa-solid fa-sliders sb-icon"></i>
                <span>Pengaturan</span>
            </a>

            <!-- Dropdown: Multi Level -->
            <div class="relative group">
                <button type="button" class="sb-dropdown t-sidebar dropdown-toggle" aria-expanded="false">
                    <div class="sb-dropdown-content">
                        <i class="fa-solid fa-folder-tree sb-icon"></i>
                        <span>Multi Level</span>
                    </div>
                    <i class="fa-solid fa-chevron-down sb-chevron chevron-icon"></i>
                </button>
                <div
                    class="submenu-container grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                    <div class="overflow-hidden">
                        <ul class="sb-submenu-line">
                            <li><a href="#" class="sb-sub-item">Level 1 Item</a></li>
                            <li class="relative">
                                <button type="button"
                                    class="sb-sub-item w-full flex items-center justify-between dropdown-toggle bg-transparent border-0 outline-none text-left"
                                    aria-expanded="false">
                                    <span>Level 1 Dropdown</span>
                                    <i
                                        class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 chevron-icon"></i>
                                </button>
                                <div
                                    class="submenu-container grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                                    <div class="overflow-hidden">
                                        <ul class="sb-submenu-line space-y-1">
                                            <li><a href="level-2.html" class="sb-sub-item">Level 2 Item</a></li>
                                            <li class="relative">
                                                <button type="button"
                                                    class="sb-sub-item w-full flex items-center justify-between dropdown-toggle bg-transparent border-0 outline-none text-left"
                                                    aria-expanded="false">
                                                    <span>Level 2 Dropdown</span>
                                                    <i
                                                        class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 chevron-icon"></i>
                                                </button>
                                                <div
                                                    class="submenu-container grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                                                    <div class="overflow-hidden">
                                                        <ul class="sb-submenu-line space-y-1">
                                                            <li><a href="level-3.html" class="sb-sub-item">Level 3
                                                                    Item A</a></li>
                                                            <li><a href="#" class="sb-sub-item">Level 3 Item
                                                                    B</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <p class="sb-title sb-title--spaced t-sidebar-title">System</p>



        </nav>

    </div> <!-- Close Navigation Wrapper -->

</aside>

<style>
    /* Sidebar Collapse Transition & State */
    #sidebar {
        transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    /* Only apply collapsed visual state on desktop screens */
    @media (min-width: 1024px) {
        #sidebar.is-collapsed {
            width: 5.5rem;
            /* approx 88px */
        }

        #sidebar.is-collapsed .logo-text,
        #sidebar.is-collapsed .t-sidebar-title,
        #sidebar.is-collapsed .chevron-icon,
        #sidebar.is-collapsed .sb-item span,
        #sidebar.is-collapsed .sb-dropdown-content span,
        #sidebar.is-collapsed .sb-logout span,
        #sidebar.is-collapsed .submenu-container {
            display: none !important;
        }

        #sidebar.is-collapsed .logo-wrapper {
            padding: 0;
            justify-content: center;
        }

        #sidebar.is-collapsed .sb-item,
        #sidebar.is-collapsed .sb-dropdown,
        #sidebar.is-collapsed .sb-logout {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        #sidebar.is-collapsed .sb-dropdown-content {
            justify-content: center !important;
            width: 100%;
        }

        #sidebar.is-collapsed .sb-item i,
        #sidebar.is-collapsed .sb-dropdown-content i,
        #sidebar.is-collapsed .sb-logout i {
            margin-right: 0 !important;
        }
    }
</style>
