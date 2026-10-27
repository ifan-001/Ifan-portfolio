{{-- 1. QR CODE MOBILE INSTALLATION MODAL FOR SAMSUNG GALAXY A54 --}}
<div id="qrInstallModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="qrModalTitle">
    <div class="app-qr-modal">
        <div class="app-qr-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:8px; background:rgba(59,130,246,0.15); display:flex; align-items:center; justify-content:center; color:var(--accent-blue);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </div>
                <div>
                    <h3 id="qrModalTitle" style="font-size:1.1rem; font-weight:700; color:var(--text-primary); margin:0;">Install Web App on Phone</h3>
                    <p style="font-size:0.78rem; color:var(--text-secondary); margin:0;">Samsung Galaxy A54 Companion Setup</p>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeQrInstallModal()" style="padding:4px 10px; font-size:1rem;" aria-label="Close Modal">&times;</button>
        </div>

        <div class="app-qr-body">
            <!-- QR Code Presentation Card -->
            <div style="text-align:center; margin-bottom:20px;">
                <div class="qr-canvas-frame">
                    <div id="qrcodeContainer" style="width:180px; height:180px; display:flex; align-items:center; justify-content:center;"></div>
                </div>
                <div style="margin-top:12px;">
                    <span class="badge badge-primary" style="font-size:0.75rem; padding:4px 10px;">
                        Scan with Samsung Galaxy A54 Camera
                    </span>
                </div>
            </div>

            <!-- Target URL & Presets Switcher -->
            <div style="margin-bottom:20px;">
                <label style="font-size:0.82rem; font-weight:700; color:var(--text-secondary); display:block; margin-bottom:6px;">
                    Target Web App URL
                </label>
                
                <!-- Quick Network Presets -->
                <div class="qr-preset-tabs">
                    <button type="button" class="qr-preset-btn active" id="presetLanBtn" onclick="setQrPreset('lan')" title="Use your Local Wi-Fi IP so your phone can connect">
                        📶 Local Wi-Fi (Phone)
                    </button>
                    <button type="button" class="qr-preset-btn" id="presetLocalhostBtn" onclick="setQrPreset('localhost')" title="Localhost for testing on PC">
                        💻 Localhost
                    </button>
                    <button type="button" class="qr-preset-btn" id="presetProdBtn" onclick="setQrPreset('prod')" title="Production URL when site is hosted online">
                        🌐 Production Domain
                    </button>
                </div>

                <div style="display:flex; gap:8px;">
                    <input type="text" id="qrTargetUrlInput" class="form-control" value="http://{{ $appInfo['local_ip'] ?? '192.168.0.103' }}:{{ $appInfo['port'] ?? 8000 }}/portal/login" style="font-size:0.82rem; font-family:monospace;" oninput="handleCustomQrUrl(this.value)">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="copyQrTargetUrl()" id="qrCopyBtn" title="Copy URL to clipboard" style="white-space:nowrap;">
                        Copy Link
                    </button>
                    <a id="qrOpenLinkBtn" href="http://{{ $appInfo['local_ip'] ?? '192.168.0.103' }}:{{ $appInfo['port'] ?? 8000 }}/portal/login" target="_blank" class="btn btn-secondary btn-sm" title="Open directly in new tab">
                        Open ↗
                    </a>
                </div>
                <div id="qrCopySuccessNotice" style="display:none; font-size:0.75rem; color:#10B981; margin-top:4px;">
                    ✓ Copied to clipboard!
                </div>
            </div>

            <!-- Step by Step Installation Guide for Samsung Galaxy A54 -->
            <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:16px; margin-bottom:16px;">
                <h4 style="font-size:0.88rem; font-weight:700; color:var(--text-primary); margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                    <span>📱</span>
                    <span>Samsung Galaxy A54-এ ইনস্টল করার সহজ নিয়ম</span>
                </h4>

                <div class="instruction-step">
                    <span class="step-number">১</span>
                    <div style="font-size:0.82rem; color:var(--text-secondary); line-height:1.45;">
                        <strong style="color:var(--text-primary);">QR কোড স্ক্যান করুন:</strong> ফোনের ক্যামেরা অন করে উপরের কোডটি স্ক্যান করুন (অথবা Samsung Internet / Chrome ব্রাউজারে লিংকটি খুলুন)।
                    </div>
                </div>

                <div class="instruction-step">
                    <span class="step-number">২</span>
                    <div style="font-size:0.82rem; color:var(--text-secondary); line-height:1.45;">
                        <strong style="color:var(--text-primary);">লগইন করুন:</strong> ইমেইল <code>hasnainalifan23@gmail.com</code> এবং আপনার পাসওয়ার্ড দিয়ে অ্যাডমিন প্যানেলে প্রবেশ করুন।
                    </div>
                </div>

                <div class="instruction-step">
                    <span class="step-number">৩</span>
                    <div style="font-size:0.82rem; color:var(--text-secondary); line-height:1.45;">
                        <strong style="color:var(--text-primary);">হোমস্ক্রিনে যুক্ত করুন (Install):</strong><br>
                        &bull; <em>Samsung Internet-এ:</em> নিচে ডানে মেনু <strong>(☰)</strong> চাপুন &rarr; <strong>Add page to</strong> চাপুন &rarr; <strong>App screen</strong> বা <strong>Home screen</strong> সিলেক্ট করুন।<br>
                        &bull; <em>Chrome-এ:</em> উপরে ডানে থ্রি-ডট <strong>(⋮)</strong> চাপুন &rarr; <strong>Install app</strong> বা <strong>Add to Home screen</strong> চাপুন।
                    </div>
                </div>

                <div class="instruction-step" style="margin-bottom:0;">
                    <span class="step-number">৪</span>
                    <div style="font-size:0.82rem; color:var(--text-secondary); line-height:1.45;">
                        <strong style="color:var(--text-primary);">অ্যাপ হিসেবে ব্যবহার:</strong> আপনার ফোনের হোমস্ক্রিন ও অ্যাপ ড্রয়ারে <strong>Hasnain Portfolio Manager</strong> আইকনটি চলে আসবে। এরপর থেকে প্রতিবার ব্রাউজার ছাড়া ১ ক্লিকেই সরাসরি অ্যাডমিন প্যানেল খুলবে।
                    </div>
                </div>
            </div>

            <!-- Future Online Hosting Note -->
            <div style="background:rgba(59,130,246,0.06); border:1px solid rgba(59,130,246,0.25); border-radius:var(--radius-md); padding:12px; font-size:0.8rem; color:var(--text-secondary); line-height:1.45;">
                <strong style="color:#60A5FA;">🌐 ভবিষ্যতে অনলাইনে হোস্ট করার প্রস্তুতি:</strong><br>
                বর্তমানে ফোন ও কম্পিউটার একই Wi-Fi-তে থাকলে লোকাল আইপি দিয়ে এটি সরাসরি কাজ করবে। ভবিষ্যতে ওয়েবসাইটটি কোনো ডোমেইনে হোস্ট করলে কোনো কোড পরিবর্তন ছাড়াই শুধু উপরে <strong>"Production Domain"</strong> বাটনে ক্লিক করলেই অনলাইন QR কোড তৈরি হবে, যা মোবাইল ডাটা বা যেকোনো ইন্টারনেট সংযোগ দিয়ে ব্যবহার করা যাবে।
            </div>
        </div>

        <div style="padding:16px 24px; border-top:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; background:rgba(0,0,0,0.15);">
            <button type="button" id="pwaDirectInstallBtn" class="btn btn-primary btn-sm" style="display:none;" onclick="triggerPwaInstallPrompt()">
                📲 Install on this Browser
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeQrInstallModal()" style="margin-left:auto;">
                Close
            </button>
        </div>
    </div>
</div>

{{-- 2. APP VERSION INFORMATION MODAL --}}
<div id="appVersionModal" class="admin-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="versionModalTitle">
    <div class="app-qr-modal">
        <div class="app-qr-header">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:8px; background:rgba(59,130,246,0.15); display:flex; align-items:center; justify-content:center; color:var(--accent-blue);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                </div>
                <div>
                    <h3 id="versionModalTitle" style="font-size:1.1rem; font-weight:700; color:var(--text-primary); margin:0;">App Version Information</h3>
                    <p style="font-size:0.78rem; color:var(--text-secondary); margin:0;">Personal Admin Web App &amp; System Metadata</p>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAppVersionModal()" style="padding:4px 10px; font-size:1rem;" aria-label="Close Modal">&times;</button>
        </div>

        <div class="app-qr-body">
            <!-- App Identity Header -->
            <div style="display:flex; align-items:center; gap:16px; padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:20px;">
                <div style="width:60px; height:60px; border-radius:14px; background:var(--bg-navy); border:2px solid var(--accent-blue); overflow:hidden; flex-shrink:0; box-shadow:0 0 15px rgba(59,130,246,0.25);">
                    <img src="/icons/icon-192.png" alt="Hasnain Portfolio Manager" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <h4 style="font-size:1.15rem; font-weight:800; color:var(--text-primary); margin:0;">{{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</h4>
                        <span class="badge badge-primary" style="font-size:0.72rem; padding:2px 6px;">{{ $appInfo['version'] ?? 'v1.0.0' }}</span>
                    </div>
                    <p style="font-size:0.8rem; color:var(--text-secondary); margin-top:3px;">
                        Personal Admin Web App for Samsung Galaxy A54 &amp; Modern Browsers
                    </p>
                </div>
            </div>

            <!-- Structured Metadata Table -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-sm);">
                    <span style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block;">App Name</span>
                    <span style="font-size:0.88rem; font-weight:700; color:var(--text-primary);">{{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</span>
                </div>

                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-sm);">
                    <span style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block;">Current Version</span>
                    <span style="font-size:0.88rem; font-weight:700; color:var(--accent-blue);">{{ $appInfo['version'] ?? 'v1.0.0' }}</span>
                </div>

                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-sm);">
                    <span style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block;">Platform</span>
                    <span style="font-size:0.88rem; font-weight:700; color:var(--text-primary);">{{ $appInfo['platform'] ?? 'Web App / PWA' }}</span>
                </div>

                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-sm);">
                    <span style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block;">Target Device</span>
                    <span style="font-size:0.88rem; font-weight:700; color:var(--text-primary);">{{ $appInfo['target_device'] ?? 'Samsung Galaxy A54' }}</span>
                </div>

                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-sm);">
                    <span style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block;">Backend Engine</span>
                    <span style="font-size:0.88rem; font-weight:700; color:var(--text-primary);">{{ $appInfo['engine'] ?? 'Laravel 12 / PHP 8.2' }}</span>
                </div>

                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-sm);">
                    <span style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; display:block;">Update Status</span>
                    <span style="font-size:0.88rem; font-weight:700; color:#10B981;">✓ Latest Stable Release</span>
                </div>
            </div>

            <!-- Version Release Notes & Capabilities -->
            <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:16px; margin-bottom:16px;">
                <h4 style="font-size:0.85rem; font-weight:700; color:var(--text-primary); margin-bottom:8px;">Release Capabilities &amp; Update Notes:</h4>
                <ul style="font-size:0.8rem; color:var(--text-secondary); line-height:1.6; padding-left:20px; margin:0;">
                    <li><strong>PWA Mobile Shell:</strong> Samsung Galaxy A54 ও আধুনিক অ্যান্ড্রয়েড ডিভাইসে হোমস্ক্রিন অ্যাপ হিসেবে ইনস্টলেশন সমর্থন।</li>
                    <li><strong>QR Code Deployer:</strong> অ্যাডমিন প্যানেল থেকে এক ক্লিকে লোকাল ও প্রোডাকশন QR কোড জেনারেট করে ফোনে সহজে অ্যাক্সেস।</li>
                    <li><strong>Core Operations:</strong> লাইভ চ্যাট কনসোল, কন্টাক্ট ইনকোয়ারিজ ইনবক্স ও লাইভ স্ট্যাটাস সুইচ।</li>
                    <li><strong>In-Place CMS:</strong> পোর্টফোলিওর যেকোনো কনটেন্ট সরাসরি ওয়েবসাইট ভিউ থেকে তাৎক্ষণিক এডিট ও সেভ করার সুবিধা।</li>
                </ul>
            </div>

            <div style="font-size:0.78rem; color:var(--text-muted); line-height:1.5;">
                ℹ <em>সেন্ট্রাল ম্যানেজমেন্ট:</em> এই ভার্সনটি <code>config/portfolio.php</code> এবং <code>.env</code> ফাইলের <code>ADMIN_APP_VERSION</code> কনফিগারেশন থেকে স্বয়ংক্রিয়ভাবে লোড হয়। ভবিষ্যতে নতুন ফিচার যোগ করলে কোডের বিভিন্ন জায়গায় পরিবর্তন ছাড়াই সেন্ট্রাল ফাইল থেকে এক ক্লিকে ভার্সন আপডেট করা যাবে।
            </div>
        </div>

        <div style="padding:16px 24px; border-top:1px solid var(--border-color); display:flex; justify-content:flex-end; background:rgba(0,0,0,0.15);">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAppVersionModal()">
                Close
            </button>
        </div>
    </div>
</div>

{{-- QR CODE & PWA SCRIPT ENGINE --}}
<script>
    let qrcodeInstance = null;
    let deferredPwaPrompt = null;

    const qrUrls = {
        lan: "http://{{ $appInfo['local_ip'] ?? '192.168.0.103' }}:{{ $appInfo['port'] ?? 8000 }}/portal/login",
        localhost: "http://127.0.0.1:{{ $appInfo['port'] ?? 8000 }}/portal/login",
        prod: "{{ url('/portal/login') }}"
    };

    function renderQr(url) {
        const container = document.getElementById('qrcodeContainer');
        if (!container) return;
        container.innerHTML = '';
        try {
            if (typeof QRCode !== 'undefined') {
                qrcodeInstance = new QRCode(container, {
                    text: url,
                    width: 180,
                    height: 180,
                    colorDark: "#0B1120",
                    colorLight: "#FFFFFF",
                    correctLevel: QRCode.CorrectLevel.M
                });
            } else {
                container.innerHTML = '<div style="color:#0B1120; font-size:11px; padding:10px; word-break:break-all;">' + url + '</div>';
            }
        } catch (e) {
            console.error('QR code render error:', e);
        }
    }

    function openQrInstallModal(preset = 'lan') {
        const modal = document.getElementById('qrInstallModal');
        if (modal) {
            modal.classList.add('active');
            setQrPreset(preset);
        }
    }

    function closeQrInstallModal() {
        const modal = document.getElementById('qrInstallModal');
        if (modal) modal.classList.remove('active');
    }

    function openAppVersionModal() {
        const modal = document.getElementById('appVersionModal');
        if (modal) modal.classList.add('active');
    }

    function closeAppVersionModal() {
        const modal = document.getElementById('appVersionModal');
        if (modal) modal.classList.remove('active');
    }

    function setQrPreset(key) {
        document.querySelectorAll('.qr-preset-btn').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById('preset' + key.charAt(0).toUpperCase() + key.slice(1) + 'Btn');
        if (activeBtn) activeBtn.classList.add('active');

        const targetUrl = qrUrls[key] || qrUrls.lan;
        const input = document.getElementById('qrTargetUrlInput');
        const openLink = document.getElementById('qrOpenLinkBtn');
        if (input) input.value = targetUrl;
        if (openLink) openLink.href = targetUrl;
        renderQr(targetUrl);
    }

    function handleCustomQrUrl(customVal) {
        document.querySelectorAll('.qr-preset-btn').forEach(btn => btn.classList.remove('active'));
        const openLink = document.getElementById('qrOpenLinkBtn');
        if (openLink) openLink.href = customVal;
        renderQr(customVal);
    }

    function copyQrTargetUrl() {
        const input = document.getElementById('qrTargetUrlInput');
        if (!input) return;
        input.select();
        navigator.clipboard.writeText(input.value).then(() => {
            const notice = document.getElementById('qrCopySuccessNotice');
            if (notice) {
                notice.style.display = 'block';
                setTimeout(() => notice.style.display = 'none', 3000);
            }
        }).catch(err => {
            document.execCommand('copy');
        });
    }

    // Native PWA Install Prompt Handler
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPwaPrompt = e;
        const installBtn = document.getElementById('pwaDirectInstallBtn');
        if (installBtn) installBtn.style.display = 'inline-flex';
    });

    function triggerPwaInstallPrompt() {
        if (!deferredPwaPrompt) return;
        deferredPwaPrompt.prompt();
        deferredPwaPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User installed the Admin PWA');
            }
            deferredPwaPrompt = null;
            const installBtn = document.getElementById('pwaDirectInstallBtn');
            if (installBtn) installBtn.style.display = 'none';
        });
    }

    // Modal click-outside and Escape key dismissal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQrInstallModal();
            closeAppVersionModal();
        }
    });

    document.querySelectorAll('.admin-modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                closeQrInstallModal();
                closeAppVersionModal();
            }
        });
    });
</script>
