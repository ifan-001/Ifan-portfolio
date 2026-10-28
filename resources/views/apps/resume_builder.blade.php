@extends('layouts.app')

@section('title', 'Free Interactive Resume & CV Builder | Hasnain Al Ifan')
@section('meta_description', 'Build, edit, and export an ATS-optimized software engineer CV with instant live preview and 1-click PDF download.')

@section('content')
<div class="resume-builder-page" style="min-height:100vh; padding:90px 20px 40px 20px; max-width:1400px; margin:0 auto;">
    
    {{-- Top Action Bar --}}
    <div class="no-print" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('apps.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; text-decoration:none;">
                <span>&larr;</span>
                <span>Back to Tools Hub</span>
            </a>
            <h1 style="font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0; display:flex; align-items:center; gap:8px;">
                <span>📄 Interactive Resume &amp; CV Builder</span>
            </h1>
        </div>

        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <button type="button" onclick="loadSampleResumeData()" class="btn btn-secondary btn-sm" style="font-weight:600;">
                ⚡ Load Sample Software Engineer Data
            </button>
            <button type="button" onclick="window.print()" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:700; padding:8px 18px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                <span>Print / Save as PDF</span>
            </button>
        </div>
    </div>

    {{-- Main Workspace: Form on Left, Live Preview on Right --}}
    <div class="builder-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:24px; align-items:start;">
        
        {{-- Left Form Column --}}
        <div class="builder-form-col no-print" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:14px; padding:24px; max-height:85vh; overflow-y:auto; box-shadow:0 4px 16px -2px rgba(0,0,0,0.06);">
            <div style="margin-bottom:20px; border-bottom:1px solid var(--border-card); padding-bottom:12px;">
                <h2 style="font-size:1.05rem; font-weight:700; color:var(--text-primary); margin:0;">Personal Details</h2>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Full Name</label>
                    <input type="text" id="input-name" value="Hasnain Al Ifan" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Job Title</label>
                    <input type="text" id="input-title" value="Software Engineer &amp; Distributed Systems" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Email</label>
                    <input type="email" id="input-email" value="hasnainalifan23@gmail.com" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Phone</label>
                    <input type="text" id="input-phone" value="+880 1608-564447" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Location</label>
                    <input type="text" id="input-location" value="Dhaka, Bangladesh" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">LinkedIn / GitHub</label>
                    <input type="text" id="input-links" value="linkedin.com/in/hasnainalifan &bull; github.com/hasnainalifan" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
            </div>

            {{-- Summary --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:4px;">Professional Summary</label>
                <textarea id="input-summary" rows="3" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:10px 12px; border-radius:6px; font-size:0.85rem; line-height:1.5; font-family:inherit;">Results-driven Software Engineer with proven expertise in building high-concurrency Laravel platforms, distributed MySQL/Redis architectures, and real-time medical systems. Passionate about automated testing, sub-100ms response times, and clean code craftsmanship.</textarea>
            </div>

            {{-- Skills --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:4px;">Technical Skills (Comma Separated)</label>
                <input type="text" id="input-skills" value="PHP 8.2+, Laravel 11, MySQL, Redis, RESTful APIs, JavaScript (ES6+), Vue.js, TailwindCSS, Docker, CI/CD, Git, WebSockets" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
            </div>

            {{-- Experience --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:4px;">Work Experience (Company, Role, Duration, Bullet Points)</label>
                <textarea id="input-experience" rows="6" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:10px 12px; border-radius:6px; font-size:0.85rem; line-height:1.5; font-family:monospace;">Software Engineer | DoctorQR & Enterprise Solutions | 2023 - Present
- Architected digital prescription and hospital queuing workflows serving thousands of active clinical records.
- Optimized database indexing and Eloquent query caching, cutting average page load latency by 64%.
- Integrated robust web push notifications and PWA offline fallbacks for mobile field staff.

Backend Developer | Freelance Engineering | 2021 - 2023
- Built 15+ custom web platforms using Laravel and RESTful API integrations.
- Enforced strict automated testing pipelines achieving over 90% code coverage.</textarea>
            </div>

            {{-- Education --}}
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.8rem; font-weight:700; color:var(--text-primary); margin-bottom:4px;">Education</label>
                <textarea id="input-education" rows="3" oninput="updateResumePreview()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:10px 12px; border-radius:6px; font-size:0.85rem; line-height:1.5; font-family:monospace;">B.Sc. in Computer Science & Engineering | Leading University | 2020 - 2024
- Focused on Distributed Systems, Software Engineering, and Database Architecture.</textarea>
            </div>
        </div>

        {{-- Right Live ATS Preview Column --}}
        <div class="resume-preview-col" style="background:#FFFFFF; color:#111827; border-radius:10px; padding:36px 32px; box-shadow:0 8px 30px rgba(0,0,0,0.15); min-height:85vh; font-family:'Inter', Arial, sans-serif;">
            {{-- Resume Header --}}
            <div style="text-align:center; border-bottom:2px solid #1E3A8A; padding-bottom:14px; margin-bottom:18px;">
                <h1 id="preview-name" style="font-size:1.8rem; font-weight:800; color:#0F172A; margin:0 0 4px 0; text-transform:uppercase; letter-spacing:0.04em;">
                    Hasnain Al Ifan
                </h1>
                <div id="preview-title" style="font-size:1rem; font-weight:700; color:#1E3A8A; margin-bottom:6px;">
                    Software Engineer &amp; Distributed Systems
                </div>
                <div id="preview-contact" style="font-size:0.825rem; color:#475569; display:flex; justify-content:center; flex-wrap:wrap; gap:8px;">
                    <span id="preview-email">hasnainalifan23@gmail.com</span> &bull;
                    <span id="preview-phone">+880 1608-564447</span> &bull;
                    <span id="preview-location">Dhaka, Bangladesh</span>
                </div>
                <div id="preview-links" style="font-size:0.8rem; color:#2563EB; margin-top:4px;">
                    linkedin.com/in/hasnainalifan &bull; github.com/hasnainalifan
                </div>
            </div>

            {{-- Summary Section --}}
            <div style="margin-bottom:16px;">
                <h2 style="font-size:0.95rem; font-weight:800; text-transform:uppercase; color:#0F172A; border-bottom:1px solid #CBD5E1; padding-bottom:3px; margin:0 0 6px 0; letter-spacing:0.05em;">
                    Professional Summary
                </h2>
                <p id="preview-summary" style="font-size:0.85rem; line-height:1.55; color:#334155; margin:0;">
                    Results-driven Software Engineer with proven expertise in building high-concurrency Laravel platforms, distributed MySQL/Redis architectures, and real-time medical systems. Passionate about automated testing, sub-100ms response times, and clean code craftsmanship.
                </p>
            </div>

            {{-- Skills Section --}}
            <div style="margin-bottom:16px;">
                <h2 style="font-size:0.95rem; font-weight:800; text-transform:uppercase; color:#0F172A; border-bottom:1px solid #CBD5E1; padding-bottom:3px; margin:0 0 6px 0; letter-spacing:0.05em;">
                    Technical Core Competencies
                </h2>
                <div id="preview-skills" style="font-size:0.85rem; line-height:1.5; color:#334155;">
                    PHP 8.2+, Laravel 11, MySQL, Redis, RESTful APIs, JavaScript (ES6+), Vue.js, TailwindCSS, Docker, CI/CD, Git, WebSockets
                </div>
            </div>

            {{-- Experience Section --}}
            <div style="margin-bottom:16px;">
                <h2 style="font-size:0.95rem; font-weight:800; text-transform:uppercase; color:#0F172A; border-bottom:1px solid #CBD5E1; padding-bottom:3px; margin:0 0 8px 0; letter-spacing:0.05em;">
                    Professional Experience
                </h2>
                <div id="preview-experience" style="display:flex; flex-direction:column; gap:12px;">
                    {{-- Dynamically populated --}}
                </div>
            </div>

            {{-- Education Section --}}
            <div>
                <h2 style="font-size:0.95rem; font-weight:800; text-transform:uppercase; color:#0F172A; border-bottom:1px solid #CBD5E1; padding-bottom:3px; margin:0 0 6px 0; letter-spacing:0.05em;">
                    Education
                </h2>
                <div id="preview-education" style="display:flex; flex-direction:column; gap:8px;">
                    {{-- Dynamically populated --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS for Print & Responsive --}}
<style>
@media print {
    body {
        background: #ffffff !important;
        color: #000000 !important;
    }
    .site-header, .site-footer, .no-print, #chat-widget-container, .admin-top-command-bar {
        display: none !important;
    }
    .resume-builder-page {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }
    .builder-grid {
        display: block !important;
    }
    .resume-preview-col {
        box-shadow: none !important;
        padding: 0 !important;
        border-radius: 0 !important;
        color: #000000 !important;
    }
}

@media (max-width: 900px) {
    .builder-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function updateResumePreview() {
    document.getElementById('preview-name').textContent = document.getElementById('input-name').value || 'Your Name';
    document.getElementById('preview-title').textContent = document.getElementById('input-title').value || 'Software Engineer';
    document.getElementById('preview-email').textContent = document.getElementById('input-email').value || '';
    document.getElementById('preview-phone').textContent = document.getElementById('input-phone').value || '';
    document.getElementById('preview-location').textContent = document.getElementById('input-location').value || '';
    document.getElementById('preview-links').innerHTML = document.getElementById('input-links').value || '';
    document.getElementById('preview-summary').textContent = document.getElementById('input-summary').value || '';
    document.getElementById('preview-skills').textContent = document.getElementById('input-skills').value || '';

    // Parse Experience
    const expText = document.getElementById('input-experience').value;
    const expContainer = document.getElementById('preview-experience');
    expContainer.innerHTML = '';

    const expBlocks = expText.split('\n\n');
    expBlocks.forEach(block => {
        if (!block.trim()) return;
        const lines = block.split('\n');
        const header = lines[0] || '';
        const parts = header.split('|').map(s => s.trim());

        const expItem = document.createElement('div');
        expItem.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:3px;">
                <strong style="color:#0F172A; font-size:0.875rem;">${parts[0] || ''} <span style="font-weight:normal; color:#475569;">${parts[1] ? 'at ' + parts[1] : ''}</span></strong>
                <span style="font-size:0.8rem; color:#64748B; font-weight:600;">${parts[2] || ''}</span>
            </div>
            <ul style="margin:2px 0 0 16px; padding:0; font-size:0.825rem; color:#334155; line-height:1.45;">
                ${lines.slice(1).map(l => `<li>${l.replace(/^[-*]\s*/, '')}</li>`).join('')}
            </ul>
        `;
        expContainer.appendChild(expItem);
    });

    // Parse Education
    const eduText = document.getElementById('input-education').value;
    const eduContainer = document.getElementById('preview-education');
    eduContainer.innerHTML = '';
    const eduBlocks = eduText.split('\n\n');
    eduBlocks.forEach(block => {
        if (!block.trim()) return;
        const lines = block.split('\n');
        const parts = lines[0].split('|').map(s => s.trim());
        const eduItem = document.createElement('div');
        eduItem.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:baseline;">
                <strong style="color:#0F172A; font-size:0.875rem;">${parts[0] || ''} <span style="font-weight:normal; color:#475569;">${parts[1] ? '&bull; ' + parts[1] : ''}</span></strong>
                <span style="font-size:0.8rem; color:#64748B;">${parts[2] || ''}</span>
            </div>
            ${lines.slice(1).map(l => `<div style="font-size:0.825rem; color:#334155; margin-top:2px;">${l.replace(/^[-*]\s*/, '')}</div>`).join('')}
        `;
        eduContainer.appendChild(eduItem);
    });
}

function loadSampleResumeData() {
    updateResumePreview();
}

document.addEventListener('DOMContentLoaded', () => {
    updateResumePreview();
});
</script>
@endsection
