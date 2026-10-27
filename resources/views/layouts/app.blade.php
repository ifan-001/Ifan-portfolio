<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>{{ $portfolio['meta']['title'] ?? 'Hasnain Al Ifan | Junior Software Engineer' }}</title>
    <meta name="description" content="{{ $portfolio['meta']['description'] ?? 'Personal portfolio of Hasnain Al Ifan, Junior Software Engineer at APPIFLY BD LIMITED.' }}">
    <meta name="keywords" content="{{ $portfolio['meta']['keywords'] ?? 'Hasnain Al Ifan, Software Engineer, Laravel, PHP, Web Application' }}">
    <meta name="author" content="{{ $portfolio['brand']['full_name'] }}">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0B1120">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $portfolio['meta']['title'] }}">
    <meta property="og:description" content="{{ $portfolio['meta']['description'] }}">
    <meta property="og:image" content="{{ asset('images/profile.jpg') }}">
    <meta property="og:locale" content="en_US">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $portfolio['meta']['title'] }}">
    <meta name="twitter:description" content="{{ $portfolio['meta']['description'] }}">
    <meta name="twitter:image" content="{{ asset('images/profile.jpg') }}">

    {{-- Favicon Monogram SVG --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='22' fill='%230B1120'/><rect x='2' y='2' width='96' height='96' rx='20' fill='none' stroke='%233B82F6' stroke-width='4'/><text x='50' y='65' font-family='sans-serif' font-size='48' font-weight='900' fill='%23F5F7FA' text-anchor='middle'>I<tspan fill='%233B82F6'>.</tspan></text></svg>">

    {{-- Typography Preconnect --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Production Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">

    {{-- Schema.org JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Person",
        "name": "{{ $portfolio['brand']['full_name'] }}",
        "jobTitle": "{{ $portfolio['brand']['title'] }}",
        "worksFor": {
            "@type": "Organization",
            "name": "{{ $portfolio['experience'][0]['company'] }}"
        },
        "alumniOf": {
            "@type": "CollegeOrUniversity",
            "name": "{{ $portfolio['education'][0]['institution'] }}"
        },
        "email": "{{ $portfolio['brand']['email'] }}",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Dhaka",
            "addressCountry": "Bangladesh"
        },
        "sameAs": [
            "{{ $portfolio['socials']['github']['url'] }}",
            "{{ $portfolio['socials']['linkedin']['url'] }}",
            "{{ $portfolio['socials']['kaggle']['url'] }}"
        ]
    }
    </script>
</head>
<body class="@auth has-admin-bar @endauth">
    @auth
        @include('partials.admin-toolbar')
    @endauth

    @include('partials.nav')

    <main id="main-content">
        @yield('content')
    </main>

    {{-- Floating Live Chat System --}}
    @if($portfolio['settings']['live_chat_enabled'] ?? true)
        @include('partials.chat')
    @endif

    @include('partials.footer')

    {{-- Portfolio Client Script --}}
    <script src="{{ asset('js/portfolio.js') }}"></script>

    @auth
        @include('partials.admin-editor-modals')
        <script src="{{ asset('js/admin-in-place.js') }}"></script>
    @endauth
</body>
</html>
