<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | Hasnain Al Ifan Portfolio Manager</title>

    <!-- PWA & Mobile Capabilities -->
    <link rel="manifest" href="/js/pwa-manifest.js?v={{ @filemtime(public_path('js/pwa-manifest.js')) ?: 1 }}" crossorigin="use-credentials">
    <meta name="theme-color" content="#0B1120">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ifan Admin">
    <link rel="apple-touch-icon" href="/icons/app-180.png?v={{ @filemtime(public_path('icons/app-180.png')) ?: 1 }}">
        <link rel="icon" type="image/png" href="/icons/app-192.png?v={{ @filemtime(public_path('icons/app-192.png')) ?: 1 }}">

    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-premium.css') }}?v={{ filemtime(public_path('css/admin-premium.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ filemtime(public_path('css/admin-dashboard.css')) }}">
    @stack('styles')
</head>
<body>
<script>try { if (localStorage.getItem('ad_sidebar_collapsed') === '1' && window.innerWidth > 900) document.body.classList.add('ad-collapsed'); } catch (e) {}</script>
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
        <div class="ad-drawer-backdrop" onclick="document.body.classList.remove('ad-drawer-open')"></div>
        <aside class="admin-sidebar ad-sidebar" aria-label="Admin navigation">
            <div class="sidebar-header ad-brand">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <span class="ad-logo-mark">IF</span>
                    <span class="ad-logo-text"><b>{{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</b><small>Admin panel</small></span>
                </a>
                <button type="button" class="ad-collapse-btn" id="ad-collapse-btn" aria-label="Collapse sidebar" title="Collapse / expand sidebar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="3"/><line x1="9" y1="4" x2="9" y2="20"/><polyline points="15 10 13 12 15 14"/></svg>
                </button>
            </div>

            <a href="{{ route('admin.content') }}" class="ad-visual-btn" title="Open visual editor">
                <span>✦</span> Open visual editor <em>↗</em>
            </a>

            <nav class="sidebar-nav ad-nav">
                <div class="nav-section-title">Overview</div>
                <a href="{{ route('admin.dashboard') }}" title="Dashboard" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg><span>Dashboard</span></a>
                <a href="{{ route('admin.inbox.index') }}" title="Inbox & live chat" class="nav-link {{ request()->routeIs('admin.inbox.*', 'admin.inquiries.*', 'admin.chat.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg><span>Inbox & live chat</span>@if($totalInboxUnreadCount > 0)<span class="nav-badge">{{ $totalInboxUnreadCount }}</span>@endif</a>
                <a href="{{ route('admin.feed.index') }}" title="Feed posts" class="nav-link {{ request()->routeIs('admin.feed.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg><span>Feed posts</span></a>
                <div class="nav-section-title">Portfolio content</div>
                <a href="{{ route('admin.projects.index') }}" title="Projects" class="nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg><span>Projects</span></a>
                <a href="{{ route('admin.profile.edit') }}" title="Profile & bio" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><span>Profile & bio</span></a>
                <a href="{{ route('admin.site-content') }}" title="Texts, cards & links" class="nav-link {{ request()->routeIs('admin.site-content*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7V4h16v3"/><path d="M9 20h6"/><path d="M12 4v16"/></svg><span>Texts, cards & links</span></a>
                <a href="{{ route('admin.skills.index') }}" title="Skills" class="nav-link {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg><span>Skills</span></a>
                <a href="{{ route('admin.experience.index') }}" title="Experience" class="nav-link {{ request()->routeIs('admin.experience.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg><span>Experience</span></a>
                <a href="{{ route('admin.education.index') }}" title="Education" class="nav-link {{ request()->routeIs('admin.education.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg><span>Education</span></a>
                <a href="{{ route('admin.research.index') }}" title="Research" class="nav-link {{ request()->routeIs('admin.research.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><span>Research</span></a>
                <a href="{{ route('admin.blog.index') }}" title="Blog articles" class="nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg><span>Blog articles</span></a>
                <a href="{{ route('admin.resume.index') }}" title="Resume / CV" class="nav-link {{ request()->routeIs('admin.resume.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><span>Resume / CV</span></a>
                <div class="nav-section-title">Site</div>
                <a href="{{ route('admin.settings.index') }}" title="Settings & sections" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 1v3M12 20v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M1 12h3M20 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg><span>Settings & sections</span></a>
                <a href="{{ route('admin.analytics.index') }}" title="Analytics" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg><span>Analytics</span></a>
                <button type="button" title="Install on phone" class="nav-link" onclick="openQrInstallModal()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg><span>Install on phone</span><span class="nav-badge nav-badge-soft">QR</span></button>
            </nav>

            <div class="ad-side-foot">
                <button type="button" onclick="openAppVersionModal()" class="app-version-badge" title="Web app version">
                    <span>⚡ {{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</span>
                    <span class="ad-ver">{{ $appInfo['version'] ?? 'v1.0.0' }}</span>
                </button>
                <div class="sidebar-footer">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm" title="View public portfolio">View site ↗</a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm" title="Sign out">Sign out</button>
                    </form>
                </div>
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
    <nav class="mobile-bottom-nav" aria-label="Mobile navigation">
        <a href="{{ route('admin.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
            <span>Home</span>
        </a>
        <a href="{{ route('admin.inbox.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.inbox.*', 'admin.inquiries.*', 'admin.chat.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Inbox</span>
            @if(!empty($totalInboxUnreadCount) && $totalInboxUnreadCount > 0)
                <span class="bottom-nav-badge">{{ $totalInboxUnreadCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.projects.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            <span>Projects</span>
        </a>
        <a href="{{ route('admin.feed.index') }}" class="bottom-nav-item {{ request()->routeIs('admin.feed.*') ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
            <span>Feed</span>
        </a>
        <button type="button" class="bottom-nav-item" onclick="document.body.classList.toggle('ad-drawer-open')" aria-label="Open full menu">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <span>Menu</span>
        </button>
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
            <button type="button" class="ui-close ui-close-sm" onclick="document.getElementById('admin-floating-toast').style.display='none'" aria-label="Dismiss"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
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

    // Keep the unread counters in the sidebar and bottom bar live
    function syncInboxBadges(total) {
        document.querySelectorAll('a[href$="/admin/inbox"]').forEach(function (a) {
            const bottom = a.classList.contains('bottom-nav-item');
            let b = a.querySelector(bottom ? '.bottom-nav-badge' : '.nav-badge:not(.nav-badge-soft)');
            if (total > 0) {
                if (!b) { b = document.createElement('span'); b.className = bottom ? 'bottom-nav-badge' : 'nav-badge'; a.appendChild(b); }
                b.textContent = total;
                b.style.display = '';
            } else if (b) {
                b.style.display = 'none';
            }
        });
    }

    // Auto-poll for incoming messages every 6 seconds
    function pollIncomingMessages() {
        fetch('{{ route("admin.notifications.check") }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.total_unread > lastKnownTotalUnread && !window.__openChatLive) {
                // Trigger Sound, Vibration, and Toast!  (the open conversation handles its own live updates)
                playNotificationChime();
                triggerPhoneVibration();
                showIncomingMessageToast(data.latest_sender, data.latest_preview);
            }
            lastKnownTotalUnread = data.total_unread;
            syncInboxBadges(data.total_unread);
        })
        .catch(err => {})
        .finally(() => {
            setTimeout(pollIncomingMessages, document.hidden ? 12000 : 4000);
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
    
    <script>
    (function () {
        var nav = document.querySelector('.ad-nav');
        var btn = document.getElementById('ad-collapse-btn');
        var syncBtn = function () {
            if (!btn) return;
            var label = document.body.classList.contains('ad-collapsed') ? 'Expand sidebar' : 'Collapse sidebar';
            btn.setAttribute('aria-label', label);
            btn.setAttribute('data-tip', label);
            btn.removeAttribute('title');
        };
        syncBtn();
        if (btn) btn.addEventListener('click', function () {
            var on = document.body.classList.toggle('ad-collapsed');
            try { localStorage.setItem('ad_sidebar_collapsed', on ? '1' : '0'); } catch (e) {}
            syncBtn();
            hideTip();
        });

        // Instant tooltips: the name shows the moment the cursor touches a button
        var tip = document.createElement('div');
        tip.className = 'ad-tip';
        tip.setAttribute('role', 'tooltip');
        document.body.appendChild(tip);
        var tipFor = null;
        function hideTip() { tip.classList.remove('show'); tipFor = null; }
        function labelOf(el) {
            if (el.hasAttribute('title')) {
                var t = el.getAttribute('title');
                el.removeAttribute('title');
                if (t) el.setAttribute('data-tip', t);
                if (t && !el.hasAttribute('aria-label') && !el.textContent.trim()) el.setAttribute('aria-label', t);
            }
            return el.getAttribute('data-tip') || '';
        }
        function showTip(el) {
            var text = labelOf(el);
            if (!text) return hideTip();
            var inSide = !!el.closest('.ad-sidebar');
            // In the open sidebar the label is already visible, so skip the tip there
            var rail = document.body.classList.contains('ad-collapsed') || window.matchMedia('(min-width: 769px) and (max-width: 900px), (min-width: 560px) and (max-width: 768px) and (hover: hover) and (pointer: fine)').matches;
            if (inSide && !rail && el !== btn && el.textContent.trim()) return hideTip();
            tipFor = el;
            tip.textContent = text;
            tip.className = 'ad-tip';
            var r = el.getBoundingClientRect(), tw = tip.offsetWidth, th = tip.offsetHeight, x, y;
            if (inSide) {
                tip.classList.add('is-right');
                x = r.right + 12; y = r.top + r.height / 2 - th / 2;
            } else if (r.bottom + th + 12 < window.innerHeight) {
                tip.classList.add('is-below');
                x = r.left + r.width / 2 - tw / 2; y = r.bottom + 10;
            } else {
                tip.classList.add('is-above');
                x = r.left + r.width / 2 - tw / 2; y = r.top - th - 10;
            }
            x = Math.max(8, Math.min(x, window.innerWidth - tw - 8));
            tip.style.left = x + 'px';
            tip.style.top = Math.max(8, y) + 'px';
            tip.classList.add('show');
        }
        if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
            document.addEventListener('mouseover', function (e) {
                var el = e.target.closest('[title], [data-tip]');
                if (el === tipFor) return;
                if (el) showTip(el); else hideTip();
            });
            document.addEventListener('focusin', function (e) {
                var el = e.target.closest('[title], [data-tip]');
                if (el && e.target.matches(':focus-visible')) showTip(el);
            });
            document.addEventListener('focusout', hideTip);
            document.addEventListener('mousedown', hideTip);
            window.addEventListener('scroll', hideTip, true);
        }
        if (!nav) return;
        // Keep the sidebar where it was when you clicked an item lower down the list
        try {
            var saved = sessionStorage.getItem('ad_nav_scroll');
            if (saved !== null) nav.scrollTop = parseInt(saved, 10) || 0;
        } catch (e) {}
        var active = nav.querySelector('.nav-link.active');
        if (active) {
            var r = active.getBoundingClientRect(), nr = nav.getBoundingClientRect();
            if (r.top < nr.top || r.bottom > nr.bottom) active.scrollIntoView({ block: 'center' });
        }
        var remember = function () { try { sessionStorage.setItem('ad_nav_scroll', nav.scrollTop); } catch (e) {} };
        nav.addEventListener('scroll', remember, { passive: true });
        nav.addEventListener('click', remember);
    })();
    </script>
    @stack('scripts')
</body>
</html>
