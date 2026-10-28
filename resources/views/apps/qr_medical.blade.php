@extends('layouts.app')

@section('title', 'DoctorQR Emergency Medical ID Card Generator | Hasnain Al Ifan')
@section('meta_description', 'Generate life-saving emergency medical QR cards with blood group, allergies, and contact details.')

@section('content')
<div class="qr-medical-page" style="min-height:100vh; padding:90px 20px 40px 20px; max-width:1100px; margin:0 auto;">
    
    {{-- Header --}}
    <div class="no-print" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('apps.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; text-decoration:none;">
                <span>&larr;</span>
                <span>Back to Tools Hub</span>
            </a>
            <h1 style="font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0;">
                🩺 DoctorQR Emergency Medical ID Generator
            </h1>
        </div>

        <button type="button" onclick="window.print()" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:700; padding:8px 18px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            <span>Print / Save Card</span>
        </button>
    </div>

    {{-- Main Grid: Form on Left, Digital Medical Card on Right --}}
    <div class="medical-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:28px; align-items:start;">
        
        {{-- Form Column --}}
        <div class="no-print" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:14px; padding:24px; box-shadow:0 4px 16px -2px rgba(0,0,0,0.06);">
            <div style="margin-bottom:16px; border-bottom:1px solid var(--border-card); padding-bottom:10px;">
                <h2 style="font-size:1.05rem; font-weight:700; color:var(--text-primary); margin:0;">Emergency Details</h2>
                <p style="color:var(--text-secondary); font-size:0.825rem; margin:4px 0 0 0;">These critical details encode into the QR code for first responders.</p>
            </div>

            <div style="margin-bottom:12px;">
                <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Full Name</label>
                <input type="text" id="med-name" value="Hasnain Al Ifan" oninput="renderMedicalCard()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Blood Group</label>
                    <select id="med-blood" onchange="renderMedicalCard()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                        <option value="B+" selected>B Positive (B+)</option>
                        <option value="A+">A Positive (A+)</option>
                        <option value="O+">O Positive (O+)</option>
                        <option value="AB+">AB Positive (AB+)</option>
                        <option value="O-">O Negative (O-)</option>
                        <option value="A-">A Negative (A-)</option>
                        <option value="B-">B Negative (B-)</option>
                        <option value="AB-">AB Negative (AB-)</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Emergency Phone</label>
                    <input type="text" id="med-phone" value="+880 1608-564447" oninput="renderMedicalCard()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
            </div>

            <div style="margin-bottom:12px;">
                <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Known Allergies</label>
                <input type="text" id="med-allergies" value="Penicillin (Severe), Peanuts (Mild)" oninput="renderMedicalCard()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:4px;">Emergency Notes / Conditions</label>
                <textarea id="med-notes" rows="3" oninput="renderMedicalCard()" style="width:100%; background:var(--bg-primary); border:1px solid var(--border-card); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem; font-family:inherit;">Asthma patient. Keep inhaler accessible. ICE contact: Family (+880 1700-000000).</textarea>
            </div>
        </div>

        {{-- Card Preview Column --}}
        <div>
            <div id="medical-card-preview" style="background:#0F172A; color:#ffffff; border-radius:16px; border:2px solid #EF4444; padding:24px; box-shadow:0 12px 30px rgba(0,0,0,0.25); position:relative; overflow:hidden;">
                
                {{-- Red Emergency Top Banner --}}
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid rgba(239,68,68,0.4); padding-bottom:12px; margin-bottom:16px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="font-size:1.8rem;">🚨</span>
                        <div>
                            <div style="font-weight:900; font-size:1.1rem; color:#EF4444; letter-spacing:0.05em;">EMERGENCY MEDICAL ID</div>
                            <div style="font-size:0.7rem; color:#94A3B8;">DoctorQR Rapid Clinical Response</div>
                        </div>
                    </div>
                    <div style="background:#EF4444; color:#fff; font-weight:900; font-size:1.1rem; padding:4px 12px; border-radius:8px;" id="card-blood">
                        B+
                    </div>
                </div>

                {{-- Card Content Grid --}}
                <div style="display:grid; grid-template-columns:1fr auto; gap:20px; align-items:center;">
                    <div>
                        <div style="font-size:0.7rem; text-transform:uppercase; color:#94A3B8; margin-bottom:2px;">Patient Name</div>
                        <div style="font-size:1.25rem; font-weight:800; color:#ffffff; margin-bottom:12px;" id="card-name">
                            Hasnain Al Ifan
                        </div>

                        <div style="font-size:0.7rem; text-transform:uppercase; color:#94A3B8; margin-bottom:2px;">Emergency Phone (ICE)</div>
                        <div style="font-size:0.95rem; font-weight:700; color:#38BDF8; margin-bottom:12px;" id="card-phone">
                            +880 1608-564447
                        </div>

                        <div style="font-size:0.7rem; text-transform:uppercase; color:#94A3B8; margin-bottom:2px;">Allergies</div>
                        <div style="font-size:0.85rem; color:#FCA5A5; font-weight:600; margin-bottom:12px;" id="card-allergies">
                            Penicillin (Severe), Peanuts (Mild)
                        </div>

                        <div style="font-size:0.7rem; text-transform:uppercase; color:#94A3B8; margin-bottom:2px;">Clinical Instructions</div>
                        <div style="font-size:0.8rem; color:#CBD5E1; line-height:1.4;" id="card-notes">
                            Asthma patient. Keep inhaler accessible.
                        </div>
                    </div>

                    {{-- QR Code Box --}}
                    <div style="background:#ffffff; padding:10px; border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; width:150px; height:150px; box-shadow:0 4px 10px rgba(0,0,0,0.2);">
                        <div id="medical-qr-container"></div>
                        <span style="font-size:0.6rem; color:#0F172A; font-weight:700; margin-top:4px;">SCAN FOR MEDICAL DATA</span>
                    </div>
                </div>

                <div style="margin-top:20px; padding-top:10px; border-top:1px dashed rgba(255,255,255,0.15); display:flex; justify-content:space-between; font-size:0.7rem; color:#64748B;">
                    <span>Official DoctorQR Architecture Lab Demo</span>
                    <span>Emergency Valid Card</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
let qrCodeInstance = null;

function renderMedicalCard() {
    const name = document.getElementById('med-name').value || 'Patient Name';
    const blood = document.getElementById('med-blood').value || 'O+';
    const phone = document.getElementById('med-phone').value || '';
    const allergies = document.getElementById('med-allergies').value || 'None Reported';
    const notes = document.getElementById('med-notes').value || 'No additional notes.';

    document.getElementById('card-name').textContent = name;
    document.getElementById('card-blood').textContent = blood;
    document.getElementById('card-phone').textContent = phone;
    document.getElementById('card-allergies').textContent = allergies;
    document.getElementById('card-notes').textContent = notes;

    // Construct medical emergency payload
    const payload = `EMERGENCY MEDICAL ID:\nPatient: ${name}\nBlood Group: ${blood}\nEmergency Phone: ${phone}\nAllergies: ${allergies}\nNotes: ${notes}`;

    const container = document.getElementById('medical-qr-container');
    container.innerHTML = '';

    if (typeof QRCode !== 'undefined') {
        qrCodeInstance = new QRCode(container, {
            text: payload,
            width: 120,
            height: 120,
            colorDark: '#0B1120',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    renderMedicalCard();
});
</script>

<style>
@media print {
    body {
        background: #ffffff !important;
    }
    .site-header, .site-footer, .no-print, #chat-widget-container {
        display: none !important;
    }
    .qr-medical-page {
        padding: 0 !important;
        margin: 0 !important;
    }
    .medical-grid {
        display: block !important;
    }
}
@media (max-width: 800px) {
    .medical-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
