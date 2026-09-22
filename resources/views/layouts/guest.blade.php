<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AMPEDIG') }}</title>

    <!-- Core Theme Initialization (Immediate to prevent FOUC) -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            const barColor = localStorage.getItem('bar-color') || 'default';
            const colorMap = {
                'midnight': '#1e293b',
                'indigo': '#1e1b4b',
                'plum': '#231034',
                'burgundy': '#3c1220',
                'emerald': '#092c1e',
                'espresso': '#251814',
                'charcoal': '#2d3748'
            };
            if (barColor !== 'default' && colorMap[barColor]) {
                document.documentElement.style.setProperty('--sidebar-bg', colorMap[barColor]);
                document.documentElement.style.setProperty('--sidebar-border', 'rgba(255, 255, 255, 0.08)');
            }

            const primary = localStorage.getItem('primary-color') || 'default';
            const primaryColorMap = {
                'indigo': {
                    '50': '#e0e7ff', '100': '#c7d2fe', '200': '#a5b4fc', '300': '#818cf8', '400': '#6366f1',
                    '500': '#4f46e5', '600': '#4338ca', '700': '#3730a3', '800': '#312e81', '900': '#1e1b4b'
                },
                'purple': {
                    '50': '#f5f3ff', '100': '#ede9fe', '200': '#ddd6fe', '300': '#c4b5fd', '400': '#a78bfa',
                    '500': '#8b5cf6', '600': '#7c3aed', '700': '#6d28d9', '800': '#5b21b6', '900': '#4c1d95'
                },
                'rose': {
                    '50': '#fff1f2', '100': '#ffe4e6', '200': '#fecdd3', '300': '#fda4af', '400': '#fb7185',
                    '500': '#f43f5e', '600': '#e11d48', '700': '#be123c', '800': '#9f1239', '900': '#881337'
                },
                'cyan': {
                    '50': '#ecfeff', '100': '#cffafe', '200': '#a5f3fc', '300': '#67e8f9', '400': '#22d3ee',
                    '500': '#06b6d4', '600': '#0891b2', '700': '#0e7490', '800': '#155e75', '900': '#164e63'
                },
                'emerald': {
                    '50': '#ecfdf5', '100': '#d1fae5', '200': '#a7f3d0', '300': '#6ee7b7', '400': '#34d399',
                    '500': '#10b981', '600': '#059669', '700': '#047857', '800': '#065f46', '900': '#064e3b'
                },
                'amber': {
                    '50': '#fffbeb', '100': '#fef3c7', '200': '#fde68a', '300': '#fcd34d', '400': '#fbbf24',
                    '500': '#f59e0b', '600': '#d97706', '700': '#b45309', '800': '#92400e', '900': '#78350f'
                },
                'orange': {
                    '50': '#fff7ed', '100': '#ffedd5', '200': '#fed7aa', '300': '#fdba74', '400': '#fb923c',
                    '500': '#f97316', '600': '#ea580c', '700': '#c2410c', '800': '#9a3412', '900': '#7c2d12'
                }
            };
            if (primary !== 'default' && primaryColorMap[primary]) {
                const shades = primaryColorMap[primary];
                for (const shade in shades) {
                    document.documentElement.style.setProperty(`--color-brand-${shade}`, shades[shade]);
                }
            }
        })();
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Meta SEO & CDN -->
    <meta name="description" content="Masuk ke Dashboard AMPEDIG">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/form-plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.css') }}">
</head>

<body class="bg-slate-50 dark:bg-[#161616] font-sans antialiased text-slate-600 dark:text-slate-300 transition-colors duration-300">
    <div class="h-screen w-full flex overflow-hidden">
        <div class="hidden lg:flex lg:w-1/2 bg-brand-600 p-12 flex-col justify-between relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl">
            </div>
            <div
                class="absolute bottom-0 left-0 w-80 h-80 bg-white opacity-10 rounded-full translate-y-1/3 -translate-x-1/3 blur-3xl">
            </div>
            <!-- Logo -->
            <div class="relative z-10 flex items-center gap-3 font-semibold text-2xl text-white tracking-tight">
                <div class="flex items-center justify-center w-10 h-10 text-brand-600">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="AMPEDIG Logo"
                        class="h-10 w-auto rounded-xl">
                </div>
                <span>AMPEDIG</span>
            </div>

            <!-- Main Content -->
            <div class="relative z-10 max-w-xl">
                <h2 class="text-5xl font-semibold mb-6 leading-tight text-white">Kelola Bisnis Digital Anda Lebih
                    Mudah.</h2>
                <p class="text-brand-100 text-lg leading-relaxed">Platform all-in-one untuk manajemen
                    agency dan transaksi PPOB yang efisien dan modern.</p>
            </div>
            <div class="relative z-10 text-brand-200 text-sm font-medium">
                &copy; <script>
                    document.write(new Date().getFullYear());
                </script>
                Masum.xyz. All rights reserved.
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white dark:bg-[#161616] p-6 overflow-y-auto transition-colors duration-300">
            <div class="w-full max-w-md ">
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center gap-2 font-semibold text-2xl text-slate-900 dark:text-white">
                        <div class="flex items-center justify-center w-10 h-10 text-white">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="AMPEDIG Logo"
                                class="h-10 w-auto rounded-xl">
                        </div>
                        <span>AMPEDIG</span>
                    </div>
                </div>

                {{ $slot }}

            </div>
        </div>

    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

    <!-- Theme Script -->
    <script src="{{ asset('assets/js/theme.js') }}"></script>

    <!-- Main App Script -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    
    @stack('scripts')
</body>

</html>
