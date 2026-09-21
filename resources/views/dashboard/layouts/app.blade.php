<!DOCTYPE html>
<html lang="id">

<head>
    @include('dashboard.partials.head')
</head>

<body
    class="bg-slate-50 dark:bg-[#161616] text-slate-600 dark:text-slate-300 font-sans antialiased transition-colors duration-300">

    <!-- Main Wrapper -->
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-[#161616] transition-colors duration-300">

        @include('dashboard.partials.sidebar')

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/20 z-40 hidden lg:hidden"></div>

        <!-- Main Content Wrapper -->
        <div
            class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50 dark:bg-[#161616] transition-colors duration-300">

            @include('dashboard.partials.navbar')

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto flex flex-col">
                    @yield('content')
                @include('dashboard.partials.footer')
            </main>
        </div>
    </div>

    @include('dashboard.partials.vendor-script')
    @stack('scripts')
</body>

</html>
