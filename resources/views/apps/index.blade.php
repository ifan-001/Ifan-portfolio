@extends('layouts.app')

@section('title', 'Interactive Projects, Games & Tools | Hasnain Al Ifan')
@section('meta_description', 'Use live interactive tools, build ATS resumes, and play arcade developer games created by Hasnain Al Ifan.')

@section('content')
<div class="apps-hub-wrapper" style="min-height:100vh; padding:100px 20px 60px 20px; max-width:1050px; margin:0 auto;">
    
    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:14px;">
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:8px; border-radius:24px; padding:8px 16px; font-weight:600; text-decoration:none;">
            <span>&larr;</span>
            <span>Back to Portfolio</span>
        </a>

        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:0.85rem; background:rgba(59,130,246,0.15); color:var(--accent-blue); padding:4px 12px; border-radius:12px; font-weight:600;">
                Live Interactive Lab
            </span>
        </div>
    </div>

    {{-- Hero Spotlight --}}
    <div style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:32px 28px; margin-bottom:32px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
            <span style="font-size:2rem;">🚀</span>
            <h1 style="font-size:1.8rem; font-weight:800; color:var(--text-primary); margin:0;">
                Interactive Projects &amp; Tools Hub
            </h1>
        </div>
        <p style="color:var(--text-secondary); font-size:1rem; line-height:1.6; margin:0; max-width:800px;">
            Beyond reading static portfolios, experience real functioning software. Build and export your ATS resume, play an arcade developer game, or generate emergency digital medical IDs. These tools are free and built to deliver real utility.
        </p>
    </div>

    {{-- Apps Grid --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(310px, 1fr)); gap:24px;">
        {{-- App 1: Resume Builder --}}
        <div class="app-card" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:24px; display:flex; flex-direction:column; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 4px 16px -2px rgba(0,0,0,0.06);">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                    <div style="width:52px; height:52px; border-radius:12px; background:linear-gradient(135deg, #1E3A8A, #3B82F6); display:flex; align-items:center; justify-content:center; font-size:1.6rem; color:#fff;">
                        📄
                    </div>
                    <span style="font-size:0.75rem; background:rgba(59,130,246,0.15); color:var(--accent-blue); padding:3px 10px; border-radius:10px; font-weight:700;">
                        CAREER TOOL
                    </span>
                </div>
                <h2 style="font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 8px 0;">
                    Live Resume &amp; CV Builder
                </h2>
                <p style="color:var(--text-secondary); font-size:0.9rem; line-height:1.55; margin:0 0 16px 0;">
                    Create a clean, ATS-compliant software engineer CV with live instant preview, customizable sections, sample data loading, and 1-click PDF download/print.
                </p>
            </div>

            <div style="padding-top:16px; border-top:1px solid var(--border-card); display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.8rem; color:var(--text-secondary);">Free &bull; No Login Required</span>
                <a href="{{ route('apps.resume-builder') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;">
                    <span>Launch Builder</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- App 2: Cyber Defense Game --}}
        <div class="app-card" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:24px; display:flex; flex-direction:column; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 4px 16px -2px rgba(0,0,0,0.06);">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                    <div style="width:52px; height:52px; border-radius:12px; background:linear-gradient(135deg, #7C3AED, #A855F7); display:flex; align-items:center; justify-content:center; font-size:1.6rem; color:#fff;">
                        🎮
                    </div>
                    <span style="font-size:0.75rem; background:rgba(168,85,247,0.15); color:#A855F7; padding:3px 10px; border-radius:10px; font-weight:700;">
                        PLAYABLE GAME
                    </span>
                </div>
                <h2 style="font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 8px 0;">
                    Code Breaker: Cyber Defense
                </h2>
                <p style="color:var(--text-secondary); font-size:0.9rem; line-height:1.55; margin:0 0 16px 0;">
                    Arcade terminal defense game built with HTML5 Canvas. Fire software security patches to neutralize incoming bug waves, with sound effects and touch controls for mobile!
                </p>
            </div>

            <div style="padding-top:16px; border-top:1px solid var(--border-card); display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.8rem; color:var(--text-secondary);">Touch &amp; Keyboard Controls</span>
                <a href="{{ route('apps.cyber-game') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;">
                    <span>Play Now</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- App 3: DoctorQR Emergency ID Card Generator --}}
        <div class="app-card" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:24px; display:flex; flex-direction:column; justify-content:space-between; transition:transform 0.2s, box-shadow 0.2s; box-shadow:0 4px 16px -2px rgba(0,0,0,0.06);">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                    <div style="width:52px; height:52px; border-radius:12px; background:linear-gradient(135deg, #047857, #10B981); display:flex; align-items:center; justify-content:center; font-size:1.6rem; color:#fff;">
                        🩺
                    </div>
                    <span style="font-size:0.75rem; background:rgba(16,185,129,0.15); color:#10B981; padding:3px 10px; border-radius:10px; font-weight:700;">
                        DOCTORQR LAB
                    </span>
                </div>
                <h2 style="font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 8px 0;">
                    DoctorQR Medical ID Card Generator
                </h2>
                <p style="color:var(--text-secondary); font-size:0.9rem; line-height:1.55; margin:0 0 16px 0;">
                    Generate and test life-saving emergency medical QR cards with blood group, emergency contact, and critical medical alerts inspired by the DoctorQR ecosystem.
                </p>
            </div>

            <div style="padding-top:16px; border-top:1px solid var(--border-card); display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.8rem; color:var(--text-secondary);">Instant QR Export</span>
                <a href="{{ route('apps.qr-medical') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600;">
                    <span>Generate ID</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        {{-- App 4: Future R&D Spotlight --}}
        <div class="app-card" style="background:var(--bg-card); border:1px dashed var(--border-card); border-radius:16px; padding:24px; display:flex; flex-direction:column; justify-content:space-between; opacity:0.85;">
            <div>
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
                    <div style="width:52px; height:52px; border-radius:12px; background:rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:center; font-size:1.6rem;">
                        ⚡
                    </div>
                    <span style="font-size:0.75rem; background:rgba(255,255,255,0.08); color:var(--text-secondary); padding:3px 10px; border-radius:10px; font-weight:600;">
                        COMING SOON
                    </span>
                </div>
                <h2 style="font-size:1.25rem; font-weight:700; color:var(--text-primary); margin:0 0 8px 0;">
                    Distributed Cache &amp; Queue Visualizer
                </h2>
                <p style="color:var(--text-secondary); font-size:0.9rem; line-height:1.55; margin:0 0 16px 0;">
                    Interactive visualization of high-throughput Redis clustering and dead-letter queue architectures for software engineers.
                </p>
            </div>

            <div style="padding-top:16px; border-top:1px dashed var(--border-card); display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:0.8rem; color:var(--text-secondary);">In Active Development</span>
                <span class="btn btn-secondary btn-sm" style="opacity:0.6; cursor:not-allowed;">Coming Soon</span>
            </div>
        </div>
    </div>
</div>

<style>
.app-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px -4px rgba(0,0,0,0.12);
    border-color: var(--accent-blue) !important;
}
</style>
@endsection
