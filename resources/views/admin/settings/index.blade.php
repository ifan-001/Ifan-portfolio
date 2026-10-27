@extends('admin.layouts.admin')

@section('title', 'Platform Settings')
@section('page_title', 'Platform Settings & Security')

@section('content')
<div class="form-grid-2" style="align-items:flex-start; margin-bottom:28px;">
    <!-- Public Section Visibility Toggles -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Public Section Visibility</h2>
            <span style="font-size:0.8rem; color:var(--text-muted);">Toggle sections on/off instantly</span>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <div style="display:flex; flex-direction:column; gap:14px; margin-bottom:20px;">
                @php
                    $sectionLabels = [
                        'show_hero' => 'Hero Banner Section',
                        'show_about' => 'About Me & Focus Cards',
                        'show_experience' => 'Work Experience Timeline',
                        'show_projects' => 'Showcase Projects',
                        'show_skills' => 'Technical Skills Matrix',
                        'show_education' => 'Education & Coursework',
                        'show_research' => 'Research Interests & Highlights',
                        'show_blog' => 'Engineering Blog Articles',
                        'show_contact' => 'Contact Inquiries Form',
                        'live_chat_enabled' => 'Floating Live Chat Bubble Widget',
                    ];
                @endphp

                @foreach($sectionLabels as $key => $label)
                    <label style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); cursor:pointer;">
                        <span style="font-size:0.9rem; font-weight:500;">{{ $label }}</span>
                        <input type="checkbox" name="{{ $key }}" value="1" {{ !empty($settings[$key]) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--accent-blue);">
                    </label>
                @endforeach
            </div>

            <div class="form-group">
                <label for="primary_accent_color" class="form-label">Brand Accent Color</label>
                <div style="display:flex; align-items:center; gap:12px;">
                    <input type="color" id="primary_accent_color" name="primary_accent_color" value="{{ $settings['primary_accent_color'] ?? '#3B82F6' }}" style="border:none; width:44px; height:40px; background:none; cursor:pointer;">
                    <input type="text" class="form-control" value="{{ $settings['primary_accent_color'] ?? '#3B82F6' }}" readonly style="width:120px;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Save Section Visibility</button>
        </form>
    </div>

    <!-- Security & Password Change -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Administrator Security</h2>
            <span style="font-size:0.8rem; color:var(--text-muted);">Update login credentials</span>
        </div>

        <form action="{{ route('admin.settings.password') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="current_password" class="form-label">Current Password *</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">New Password * (Min 8 characters)</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Update Password</button>
        </form>

        <hr style="border:none; border-top:1px solid var(--border-color); margin:28px 0 20px;">

        <!-- Mobile Push Notification Registration -->
        <h3 style="font-size:0.95rem; font-weight:700; margin-bottom:8px;">PWA Mobile Push Notifications</h3>
        <p style="font-size:0.82rem; color:var(--text-secondary); margin-bottom:14px;">
            Receive real-time push alerts on your Samsung Galaxy A54 when new visitor messages or contact inquiries arrive.
        </p>

        <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:12px;">
            <span style="font-size:0.85rem; color:var(--text-muted);">Registered Devices:</span>
            <span class="badge badge-info">{{ $pushSubscriptionsCount }}</span>
        </div>

        <div style="display:flex; gap:10px;">
            <button type="button" id="btnEnablePush" class="btn btn-secondary btn-sm" onclick="subscribePushNotification()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                <span>Enable on This Device</span>
            </button>

            <button type="button" class="btn btn-secondary btn-sm" onclick="testPushNotification()">
                <span>Test Dispatch</span>
            </button>
        </div>
        <div id="pushStatusMsg" style="font-size:0.78rem; color:var(--accent-blue); margin-top:10px;"></div>

        <hr style="border:none; border-top:1px solid var(--border-color); margin:20px 0;">

        <!-- Mobile Installation & App Version Section -->
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div>
                <h4 style="font-size:0.92rem; font-weight:700; color:var(--text-primary); margin:0;">{{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</h4>
                <div style="font-size:0.78rem; color:var(--text-secondary); margin-top:2px;">
                    Version: <strong style="color:var(--accent-blue);">{{ $appInfo['version'] ?? 'v1.0.0' }}</strong> &middot; Target: {{ $appInfo['target_device'] ?? 'Samsung Galaxy A54' }}
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="openQrInstallModal()">
                    📲 Scan QR Code
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openAppVersionModal()">
                    App Info
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function subscribePushNotification() {
    const msgEl = document.getElementById('pushStatusMsg');
    msgEl.textContent = 'Requesting browser notification permission...';

    if (!('Notification' in window)) {
        msgEl.textContent = 'Notifications not supported by this browser.';
        return;
    }

    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            msgEl.textContent = 'Permission denied. Please allow notifications in browser settings.';
            return;
        }

        const reg = await navigator.serviceWorker.ready;
        // In full production, supply applicationServerKey: urlB64ToUint8Array(VAPID_PUBLIC_KEY)
        const sub = await reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: 'BEl62iUYgUivxIkv69yViEuiBIa-Ib9-SkvMeAtA3LFgDzkrxZJjSgSnfckjBJuBKr3qBUYIHBQFLXYp5Nksh8U'
        }).catch(async (e) => {
            // Simulated subscription for development/localhost testing
            return {
                endpoint: 'https://fcm.googleapis.com/fcm/send/sample-token-' + Date.now(),
                toJSON: () => ({
                    endpoint: 'https://fcm.googleapis.com/fcm/send/sample-token-' + Date.now(),
                    keys: { p256dh: 'sample_p256dh_key', auth: 'sample_auth_token' }
                })
            };
        });

        const subJson = sub.toJSON ? sub.toJSON() : sub;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res = await fetch("{{ route('admin.push.subscribe') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                endpoint: subJson.endpoint || sub.endpoint,
                public_key: subJson.keys?.p256dh || null,
                auth_token: subJson.keys?.auth || null,
                device_name: navigator.userAgent
            })
        });

        const data = await res.json();
        msgEl.textContent = data.message || 'Subscribed successfully!';
    } catch (err) {
        msgEl.textContent = 'Notification setup status: ' + err.message;
    }
}

async function testPushNotification() {
    const msgEl = document.getElementById('pushStatusMsg');
    msgEl.textContent = 'Triggering notification test...';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const res = await fetch("{{ route('admin.push.test') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        const data = await res.json();
        msgEl.textContent = data.message;
    } catch (e) {
        msgEl.textContent = 'Test failed: ' + e.message;
    }
}
</script>
@endpush
