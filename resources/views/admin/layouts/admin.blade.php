<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | Hasnain Al Ifan Portfolio Manager</title>

    <!-- PWA & Mobile Capabilities -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0B1120">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Portfolio Admin">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="icon" type="image/png" href="/icons/icon-192.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
@php
    $currentPillar = 'operations';
    if (request()->routeIs('admin.analytics.*') || request()->routeIs('admin.settings.*')) {
        $currentPillar = 'insights';
    } elseif (request()->routeIs('admin.content*')) {
        $currentPillar = 'content';
    }
    $unreadChatCount = \App\Models\ChatMessage::where('sender', 'visitor')->where('is_read', false)->count();
    $unreadInquiriesCount = \App\Models\ContactMessage::where('status', 'unread')->count();
@endphp
    <div class="admin-shell">
        <!-- Desktop Sidebar -->
        <aside class="admin-sidebar" aria-label="Admin Navigation">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <span style="display:inline-block; width:10px; height:10px; background:var(--accent-blue); border-radius:50%;"></span>
                    <span>IFAN<span style="color:var(--accent-blue);">.</span> ADMIN</span>
                </a>
            </div>

            <!-- 3 Pillars Switcher Section -->
            <div class="sidebar-pillar-menu pillar-nav-switcher">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-pillar-btn pillar-nav-btn {{ $currentPillar === 'operations' ? 'active' : '' }}" title="Core Operations">
                    <span class="pillar-icon-box">⚡</span>
                    <div class="pillar-text-box">
                        <span class="pillar-title">Core Operations</span>
                        <span class="pillar-sub">Dashboard, Chat, Inquiries</span>
                    </div>
                    @if($currentPillar === 'operations')
                        <span class="pillar-badge-indicator">Active</span>
                    @endif
                </a>

                <a href="{{ route('admin.content') }}" class="sidebar-pillar-btn pillar-nav-btn {{ $currentPillar === 'content' ? 'active' : '' }}" title="Content Management (Visual Portfolio Editor)">
                    <span class="pillar-icon-box" style="color:var(--accent-blue);">✦</span>
                    <div class="pillar-text-box">
                        <span class="pillar-title">Content Management</span>
                        <span class="pillar-sub">Live Visual Editor (On Site)</span>
                    </div>
                    <span class="pillar-badge-indicator">Open ↗</span>
                </a>

                <a href="{{ route('admin.analytics.index') }}" class="sidebar-pillar-btn pillar-nav-btn {{ $currentPillar === 'insights' ? 'active' : '' }}" title="System & Insights">
                    <span class="pillar-icon-box">⚙</span>
                    <div class="pillar-text-box">
                        <span class="pillar-title">System &amp; Insights</span>
                        <span class="pillar-sub">Analytics &amp; Platform Settings</span>
                    </div>
                    @if($currentPillar === 'insights')
                        <span class="pillar-badge-indicator">Active</span>
                    @endif
                </a>
            </div>

            <nav class="sidebar-nav">
                @if($currentPillar === 'insights')
                    {{-- Pillar 3: System & Insights --}}
                    <div class="nav-section-title">System &amp; Insights Features</div>
                    <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                        <span>Analytics &amp; Telemetry</span>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>Platform Settings</span>
                    </a>
                @else
                    {{-- Pillar 1: Core Operations (Default) --}}
                    <div class="nav-section-title">Core Operations Features</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        <span>Dashboard Overview</span>
                    </a>

                    <a href="{{ route('admin.chat.index') }}" class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>Live Chat Console</span>
                        @if($unreadChatCount > 0)
                            <span class="nav-badge" style="background:#EF4444;">{{ $unreadChatCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.inquiries.index') }}" class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>Inquiries Inbox</span>
                        @if($unreadInquiriesCount > 0)
                            <span class="nav-badge">{{ $unreadInquiriesCount }}</span>
                        @endif
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm" title="View Public Portfolio">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    <span>View Site</span>
                </a>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" title="Sign Out">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="admin-main">
            <!-- Header Bar -->
            <header class="admin-header">
                <div class="header-title-wrap">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="header-actions">
                    <!-- Live Availability Pill -->
                    @php $liveStatus = \App\Models\ChatStatus::current(); @endphp
                    <form action="{{ route('admin.dashboard.toggle-status') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="status-pill" title="Click to toggle Online/Offline">
                            <span class="status-dot-sm {{ $liveStatus->is_online ? 'online' : 'offline' }}"></span>
                            <span>{{ $liveStatus->is_online ? 'Live / Online' : 'Offline' }}</span>
                        </button>
                    </form>

                    <!-- Public Link -->
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm" style="display:inline-flex;">
                        <span>Preview</span>
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <main class="admin-content">
                @if (session('status'))
                    <div class="alert alert-success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div>
                            <strong>Please review input errors:</strong>
                            <ul style="margin-top:4px; padding-left:18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation (PWA on Samsung Galaxy A54) -->
    <nav class="mobile-bottom-nav" aria-label="Mobile Navigation">
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            <span>Home</span>
        </a>

        <a href="{{ route('admin.chat.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            <span>Chat</span>
            @if(!empty($unreadChatCount) && $unreadChatCount > 0)
                <span class="bottom-nav-badge">{{ $unreadChatCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.inquiries.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <span>Inbox</span>
            @if(!empty($unreadInquiriesCount) && $unreadInquiriesCount > 0)
                <span class="bottom-nav-badge">{{ $unreadInquiriesCount }}</span>
            @endif
        </a>

        <a href="{{ route('admin.content') }}" class="bottom-nav-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            <span>Visual CMS</span>
        </a>

        <a href="{{ route('admin.analytics.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.analytics.*', 'admin.settings.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
            <span>Insights</span>
        </a>
    </nav>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Portfolio PWA Service Worker registered:', reg.scope))
                    .catch(err => console.log('Service Worker registration failed:', err));
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
