@extends('admin.layouts.admin')

@section('title', 'Analytics')
@section('page_title', 'Privacy-Conscious Telemetry & Insights')

@section('content')
<!-- KPI Row -->
<div class="kpi-grid">
    <div class="kpi-card">
        <span class="kpi-label">Total Page Views</span>
        <span class="kpi-value">{{ number_format($totalViews) }}</span>
        <span class="kpi-sub">Aggregated across all visits</span>
    </div>

    <div class="kpi-card">
        <span class="kpi-label">Unique Visitors</span>
        <span class="kpi-value">{{ number_format($uniqueVisitors) }}</span>
        <span class="kpi-sub">Daily hashed salt without cookies</span>
    </div>

    <div class="kpi-card">
        <span class="kpi-label">Resume Downloads</span>
        <span class="kpi-value" style="color:var(--accent-blue);">{{ number_format($resumeDownloads) }}</span>
        <span class="kpi-sub">CV previews and downloads</span>
    </div>

    <div class="kpi-card">
        <span class="kpi-label">Project Demo Clicks</span>
        <span class="kpi-value" style="color:var(--status-online);">{{ number_format($projectClicks) }}</span>
        <span class="kpi-sub">Outbound GitHub &amp; Live Demo visits</span>
    </div>
</div>

<!-- Privacy Compliance Notice -->
<div class="card" style="margin-bottom:28px; background:rgba(59, 130, 246, 0.05); border-color:rgba(59, 130, 246, 0.2);">
    <div style="display:flex; align-items:center; gap:12px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--accent-blue); flex-shrink:0;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        <div style="font-size:0.85rem; color:var(--text-secondary);">
            <strong>Zero Tracking Cookies &middot; GDPR &amp; Privacy First:</strong> IP addresses are anonymized using one-way SHA-256 cryptographic salts rotated daily. No personal identifiers or third-party ad networks are used.
        </div>
    </div>
</div>

<div class="form-grid-2" style="margin-bottom:28px;">
    <!-- 14-Day Traffic Overview -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent 14-Day Traffic</h3>
        </div>

        @if(empty($dailyViews))
            <p style="color:var(--text-muted); font-size:0.88rem; padding:16px 0;">Traffic history will appear as visitors arrive.</p>
        @else
            <div style="display:flex; align-items:flex-end; gap:8px; height:180px; padding:20px 0 10px;">
                @php $maxVal = max(1, max($dailyViews)); @endphp
                @foreach($dailyViews as $date => $count)
                    @php $heightPercent = round(($count / $maxVal) * 100); @endphp
                    <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:6px; height:100%; justify-content:flex-end;">
                        <span style="font-size:0.7rem; color:var(--text-muted);">{{ $count }}</span>
                        <div style="width:100%; height:{{ max(8, $heightPercent) }}%; background:var(--accent-blue); border-radius:4px 4px 0 0; min-height:8px;" title="{{ $date }}: {{ $count }} views"></div>
                        <span style="font-size:0.65rem; color:var(--text-muted); writing-mode:vertical-rl; transform:rotate(180deg); margin-top:4px;">{{ substr($date, 5) }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Device Distribution -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Visitor Device Breakdown</h3>
        </div>

        @if(empty($devices))
            <p style="color:var(--text-muted); font-size:0.88rem; padding:16px 0;">No device statistics recorded yet.</p>
        @else
            @php $totalDeviceViews = max(1, array_sum($devices)); @endphp
            <div style="display:flex; flex-direction:column; gap:16px; padding:10px 0;">
                @foreach($devices as $dev => $cnt)
                    @php $pct = round(($cnt / $totalDeviceViews) * 100); @endphp
                    <div>
                        <div style="display:flex; justify-content:space-between; font-size:0.88rem; margin-bottom:6px;">
                            <span style="font-weight:600; text-transform:capitalize;">{{ $dev }}</span>
                            <span style="color:var(--text-muted);">{{ $cnt }} visits ({{ $pct }}%)</span>
                        </div>
                        <div style="height:8px; background:rgba(255,255,255,0.06); border-radius:var(--radius-full); overflow:hidden;">
                            <div style="width:{{ $pct }}%; height:100%; background:{{ $dev === 'mobile' ? 'var(--status-online)' : 'var(--accent-blue)' }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Top Targets & Recent Activity -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Top Engaged Targets (Projects &amp; Actions)</h3>
    </div>

    @if($topTargets->isEmpty())
        <p style="color:var(--text-muted); font-size:0.88rem; padding:16px 0;">No target interactions recorded yet.</p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Target / Destination</th>
                        <th>Event Type</th>
                        <th>Total Engagement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topTargets as $tt)
                        <tr>
                            <td style="font-weight:600; font-size:0.9rem;">{{ $tt->target }}</td>
                            <td><span class="badge badge-info">{{ $tt->event_type }}</span></td>
                            <td style="font-weight:700;">{{ number_format($tt->count) }} clicks</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
