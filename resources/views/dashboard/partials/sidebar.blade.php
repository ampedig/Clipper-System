<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-[#222222] border-r border-[#2e2e2e] transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col nav-text-sm">

    <!-- Logo -->
    <div class="flex items-center justify-between h-20 px-6 border-b border-[#2e2e2e] logo-wrapper">
        <div class="flex items-center gap-3 font-semibold text-lg tracking-tight text-white overflow-hidden logo-group">
            <img src="{{ asset('assets/images/logo.png') }}" alt="AMPEDIG Logo" class="w-10 h-10 rounded-lg shrink-0">
            <span class="logo-text whitespace-nowrap">AZCLIP</span>
        </div>
    </div>

    <!-- Navigation Wrapper -->
    <div id="sidebarHoverArea" class="flex-1 flex flex-col min-h-0 overflow-hidden">
        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto custom-scrollbar">

            <p class="sb-title t-sidebar-title">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high sb-icon"></i>
                <span>Dashboard</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Clip</p>

            <a href="{{ route('admin.clip-campaigns.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.clip-campaigns.*') ? 'active' : '' }}">
                <i class="fa-solid fa-video sb-icon"></i> <span>List Clip</span>
            </a>

            <a href="{{ route('admin.clip-submissions.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.clip-submissions.*') ? 'active' : '' }}">
                <i class="fa-solid fa-video sb-icon"></i> <span>Pengajuan</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Komisi</p>

            <a href="{{ route('admin.riwayat-saldo.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.riwayat-saldo.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cash-register sb-icon"></i> <span>Riwayat</span>
            </a>

            <a href="{{ route('admin.withdrawals.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer sb-icon"></i> <span>Withdraw</span>
            </a>

            <a href="{{ route('admin.withdraw-channels.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.withdraw-channels.*') ? 'active' : '' }}">
                <i class="fa-solid fa-network-wired sb-icon"></i> <span>Metode WD</span>
            </a>


            <p class="sb-title sb-title--spaced t-sidebar-title">Template</p>

            <a href="{{ route('admin.rejection-templates.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.rejection-templates.*') ? 'active' : '' }}">
                <i class="fa-solid fa-message sb-icon"></i>
                <span>Tolak Clip</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Pengguna</p>

            <a href="{{ route('admin.clippers.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.clippers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users sb-icon"></i> <span>Clipper</span>
            </a>

            <a href="{{ route('admin.administrators.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.administrators.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-cog sb-icon"></i> <span>Administrator</span>
            </a>

            <p class="sb-title sb-title--spaced t-sidebar-title">Pengaturan</p>

            <a href="{{ route('admin.settings.index') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders sb-icon"></i>
                <span>Pengaturan</span>
            </a>

            <a href="{{ route('admin.settings.whatsapp') }}"
                class="sb-item t-sidebar {{ request()->routeIs('admin.settings.whatsapp*') ? 'active' : '' }}">
                <i class="fa-brands fa-whatsapp sb-icon"></i>
                <span>WhatsApp</span>
            </a>


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
