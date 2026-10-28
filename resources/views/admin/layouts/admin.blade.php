<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
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
    $totalInboxUnreadCount = $unreadChatCount + $unreadInquiriesCount;
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

                    <a href="{{ route('admin.feed.index') }}" class="nav-link {{ request()->routeIs('admin.feed.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                        <span>Posts &amp; Feed</span>
                    </a>

                    <a href="{{ route('admin.inbox.index') }}" class="nav-link {{ request()->routeIs('admin.inbox.*', 'admin.inquiries.*', 'admin.chat.*') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span>Unified Inbox</span>
                        @if($totalInboxUnreadCount > 0)
                            <span class="nav-badge" style="background:#EF4444;">{{ $totalInboxUnreadCount }}</span>
                        @endif
                    </a>

                    <button type="button" class="nav-link" onclick="openQrInstallModal()" style="background:none; border:none; text-align:left; width:100%; cursor:pointer; font-family:inherit;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        <span>Install on Phone</span>
                        <span class="nav-badge" style="background:rgba(59,130,246,0.2); color:#3B82F6;">QR</span>
                    </button>
                @endif
            </nav>

            <div style="padding: 10px 16px; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 8px;">
                <button type="button" onclick="openAppVersionModal()" class="app-version-badge" title="View Web App Version Details" style="width:100%; justify-content:space-between;">
                    <span style="display:flex; align-items:center; gap:6px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        <span style="font-size:0.75rem;">⚡</span>
                        <span>{{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</span>
                    </span>
                    <span style="background:var(--accent-blue); color:#fff; padding:1px 5px; border-radius:4px; font-size:0.65rem;">{{ $appInfo['version'] ?? 'v1.0.0' }}</span>
                </button>
            </div>

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
                    <span class="mobile-brand-pill">IFAN.</span>
                    <h1 class="header-page-title">@yield('page_title', 'Dashboard')</h1>
                </div>

                <div class="header-actions">
                    <!-- Live Availability Pill -->
                    @php $liveStatus = \App\Models\ChatStatus::current(); @endphp
                    <form action="{{ route('admin.dashboard.toggle-status') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="status-pill" title="Click to toggle Online/Offline">
                            <span class="status-dot-sm {{ $liveStatus->is_online ? 'online' : 'offline' }}"></span>
                            <span class="status-pill-text">{{ $liveStatus->is_online ? 'Live / Online' : 'Offline' }}</span>
                        </button>
                    </form>

                    <!-- Install on Phone Button (Hidden on Mobile Screens) -->
                    <button type="button" class="btn btn-secondary btn-sm header-install-btn" onclick="openQrInstallModal()" title="Install Web App on Phone via QR Code">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
                        <span>Install on Phone</span>
                    </button>

                    <!-- Sound & Vibrate Alert Toggle -->
                    <button type="button" id="admin-alert-toggle-btn" onclick="toggleAlertSoundAndVibe()" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;" title="Notification Sound & Phone Vibration">
                        <span id="alert-bell-icon">🔔</span>
                        <span id="alert-bell-text" class="header-install-btn-text">Alerts: ON</span>
                    </button>

                    <!-- Public Link -->
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm header-preview-btn" title="View Live Portfolio">
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

        <a href="{{ route('admin.feed.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.feed.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            <span>Feed</span>
        </a>

        <a href="{{ route('admin.inbox.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.inbox.*', 'admin.inquiries.*', 'admin.chat.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <span>Inbox</span>
            @if(!empty($totalInboxUnreadCount) && $totalInboxUnreadCount > 0)
                <span class="bottom-nav-badge">{{ $totalInboxUnreadCount }}</span>
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
    
    <!-- QR Code Generator & Modals -->
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    @include('admin.partials.app-modals')
    
    <!-- Real-Time Incoming Message Alert Sound & Phone Vibration Engine -->
    <div id="admin-floating-toast" style="position:fixed; top:20px; right:20px; z-index:99999; display:none; background:#1E3A8A; color:#FFFFFF; border:2px solid #3B82F6; border-radius:12px; padding:14px 18px; box-shadow:0 10px 30px rgba(0,0,0,0.5); max-width:360px; animation:slideDown 0.3s ease;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
            <div style="font-weight:700; font-size:0.95rem; display:flex; align-items:center; gap:6px;">
                <span>🔔</span>
                <span id="toast-title">New Message Received!</span>
            </div>
            <button type="button" onclick="document.getElementById('admin-floating-toast').style.display='none'" style="background:none; border:none; color:#fff; cursor:pointer; font-size:1rem;">✕</button>
        </div>
        <p id="toast-body" style="font-size:0.85rem; margin:0 0 10px 0; line-height:1.4; color:#E2E8F0;"></p>
        <a href="{{ route('admin.inbox.index') }}" class="btn btn-primary btn-xs" style="width:100%; text-align:center; display:block;">
            Open in Inbox &rarr;
        </a>
    </div>

    <script>
    // Audio Context & Vibration Engine
    let alertAudioContext = null;
    let alertsEnabled = localStorage.getItem('admin_alerts_enabled') !== 'false';
    let lastKnownTotalUnread = {{ $totalInboxUnreadCount ?? 0 }};

    function initAudioContext() {
        if (!alertAudioContext) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) {
                alertAudioContext = new AudioCtx();
            }
        }
        if (alertAudioContext && alertAudioContext.state === 'suspended') {
            alertAudioContext.resume();
        }
    }

    // Melodic double-bell chime (Synthesized via Web Audio API)
    function playNotificationChime() {
        if (!alertsEnabled) return;
        try {
            initAudioContext();
            if (!alertAudioContext) return;

            const t = alertAudioContext.currentTime;

            // Bell 1 (E5 tone)
            const osc1 = alertAudioContext.createOscillator();
            const gain1 = alertAudioContext.createGain();
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(659.25, t);
            gain1.gain.setValueAtTime(0.35, t);
            gain1.gain.exponentialRampToValueAtTime(0.001, t + 0.35);
            osc1.connect(gain1);
            gain1.connect(alertAudioContext.destination);
            osc1.start(t);
            osc1.stop(t + 0.35);

            // Bell 2 (A5 higher harmonious bell)
            const osc2 = alertAudioContext.createOscillator();
            const gain2 = alertAudioContext.createGain();
            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(880, t + 0.12);
            gain2.gain.setValueAtTime(0.4, t + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.001, t + 0.8);
            osc2.connect(gain2);
            gain2.connect(alertAudioContext.destination);
            osc2.start(t + 0.12);
            osc2.stop(t + 0.8);
        } catch (e) {
            console.warn('Audio play failed:', e);
        }
    }

    // Phone vibration (Hardware vibration for Samsung Galaxy A54 & Android devices)
    function triggerPhoneVibration() {
        if (!alertsEnabled) return;
        if ('vibrate' in navigator) {
            try {
                // Distinct double-pulse pattern: 250ms buzz, 100ms pause, 250ms buzz
                navigator.vibrate([250, 100, 250, 100, 350]);
            } catch (e) {
                console.warn('Vibration failed:', e);
            }
        }
    }

    function toggleAlertSoundAndVibe() {
        initAudioContext();
        alertsEnabled = !alertsEnabled;
        localStorage.setItem('admin_alerts_enabled', alertsEnabled ? 'true' : 'false');
        updateAlertToggleUI();

        if (alertsEnabled) {
            // Test tone & test vibration immediately so user feels it
            playNotificationChime();
            triggerPhoneVibration();
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        }
    }

    function updateAlertToggleUI() {
        const btn = document.getElementById('admin-alert-toggle-btn');
        const icon = document.getElementById('alert-bell-icon');
        const text = document.getElementById('alert-bell-text');
        if (!btn) return;
        if (alertsEnabled) {
            icon.textContent = '🔔';
            text.textContent = 'Alerts: ON';
            btn.style.color = '#10B981';
            btn.style.borderColor = '#10B981';
        } else {
            icon.textContent = '🔕';
            text.textContent = 'Alerts: OFF';
            btn.style.color = 'var(--text-secondary)';
            btn.style.borderColor = 'var(--border-color)';
        }
    }

    function showIncomingMessageToast(sender, preview) {
        const toast = document.getElementById('admin-floating-toast');
        const title = document.getElementById('toast-title');
        const body = document.getElementById('toast-body');
        if (toast && title && body) {
            title.textContent = `New message from ${sender || 'Visitor'}`;
            body.textContent = preview ? (preview.length > 80 ? preview.substring(0, 80) + '...' : preview) : 'A new message was received on your portfolio.';
            toast.style.display = 'block';
            setTimeout(() => { toast.style.display = 'none'; }, 8000);
        }

        // Native System Notification
        if ('Notification' in window && Notification.permission === 'granted') {
            try {
                new Notification(`New Message from ${sender || 'Visitor'}`, {
                    body: preview || 'You received a new inquiry or live chat message.',
                    icon: '/images/profile.jpg',
                    vibrate: [250, 100, 250]
                });
            } catch (e) {}
        }
    }

    // Auto-poll for incoming messages every 6 seconds
    function pollIncomingMessages() {
        fetch('{{ route("admin.notifications.check") }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.total_unread > lastKnownTotalUnread) {
                // Trigger Sound, Vibration, and Toast!
                playNotificationChime();
                triggerPhoneVibration();
                showIncomingMessageToast(data.latest_sender, data.latest_preview);
            }
            lastKnownTotalUnread = data.total_unread;
        })
        .catch(err => {})
        .finally(() => {
            setTimeout(pollIncomingMessages, 6000);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateAlertToggleUI();
        // First touch anywhere unlocks audio context
        document.body.addEventListener('click', initAudioContext, { once: true });
        document.body.addEventListener('touchstart', initAudioContext, { once: true });
        // Start polling
        setTimeout(pollIncomingMessages, 4000);
    });
    </script>
    
    @stack('scripts')
</body>
</html>
