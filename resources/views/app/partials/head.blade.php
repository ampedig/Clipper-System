<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#ff0019">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="manifest" href="{{ asset('assets/images/site.webmanifest') }}">

    <title>{{ isset($title) ? $title . ' - AZCLIP' : 'AZCLIP' }}</title>
    <meta name="description" content="{{ $description ?? 'Platform affiliator & clipper TikTok AZCLIP' }}">
    
    <!-- SEO & Open Graph Meta Tags -->
    <meta property="og:title" content="{{ isset($title) ? $title . ' - AZCLIP' : 'AZCLIP' }}">
    <meta property="og:description" content="{{ $description ?? 'Platform affiliator & clipper TikTok AZCLIP' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('assets/images/logo.png') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title) ? $title . ' - AZCLIP' : 'AZCLIP' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Platform affiliator & clipper TikTok AZCLIP' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('assets/images/logo.png') }}">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">

    <!-- Tailwind & Global Styles -->
    <link href="{{ asset('app/assets/css/input.css') }}" rel="stylesheet">
    <link href="{{ asset('app/assets/css/sweetalert-app.css') }}" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link href="{{ asset('app/assets/libs/fontawesome/css/all.css') }}" rel="stylesheet">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-800 antialiased selection:bg-indigo-100 selection:text-indigo-700 min-h-screen">
    <div class="mobile-container {{ $containerClass ?? 'pb-24' }}">

