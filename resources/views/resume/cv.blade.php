@php
    $b = $portfolio['brand'];
    $s = $portfolio['socials'];
    $photo = public_path(ltrim($b['profile_image'], '/'));
    $photoData = is_file($photo) ? 'data:' . (mime_content_type($photo) ?: 'image/jpeg') . ';base64,' . base64_encode(file_get_contents($photo)) : null;
    $edu = $portfolio['education'];
    $exp = $portfolio['experience'][0] ?? null;
    $site = str_contains(config('app.url'), 'localhost') || str_contains(config('app.url'), '127.0.0.1') ? 'hasnainalifan.com' : preg_replace('#^https?://#', '', rtrim(config('app.url'), '/'));
    $short = fn ($u) => preg_replace('#^(https?://)?(www\.)?#', '', rtrim((string) $u, '/'));
    $highlights = array_slice($exp['highlights'] ?? ($exp['roles'][0]['highlights'] ?? []), 0, 4);
    $projects = [
        ['name' => 'DoctorQR', 'kind' => 'Doctor practice & telemedicine SaaS', 'badges' => ['15 modules', '475 tests', 'PWA'], 'link' => 'doctor-qr.appiflybd.com', 'points' => [
            'Independently built a multi-tenant platform: each doctor gets a branded booking page and a QR code that turns any prescription into an appointment; patients book, pay and join video calls with no account.',
            'Live serial queue, bKash payment states, SMS alerts, Agora video with auto picture-in-picture and signed digital prescriptions.',
        ], 'tech' => 'Laravel 12 · PHP · MySQL · Blade · JavaScript · Agora RTC · SMS gateway'],
        ['name' => 'Newsifly', 'kind' => 'White-label news portal + Flutter app (APPIFLY product)', 'badges' => ['Web + App', 'AI newsroom'], 'link' => null, 'points' => [
            'Self-hosted newsroom with reporter, editor and admin roles, AI writing, summaries and translation, push notifications, reels, galleries, e-paper and a Flutter Android app with offline reading.',
        ], 'tech' => 'Laravel 13 · MariaDB · REST API · Flutter · Firebase FCM · Gemini AI'],
    ];
    $more = [
        ['name' => 'Employee Management System', 'kind' => 'Internship', 'text' => 'Laravel CRUD system with authentication, profile photos, search and a statistics dashboard.', 'tech' => 'Laravel · MySQL · Breeze'],
        ['name' => 'AdShield', 'kind' => 'Android', 'text' => 'On-device ad and tracker blocker using a local DNS VPN, curated blocklists and unit-tested DNS parsing.', 'tech' => 'Kotlin · Jetpack Compose'],
        ['name' => 'Nuclei Segmentation', 'kind' => 'Capstone · AI', 'text' => 'Deep-learning segmentation of cell nuclei in biomedical images: preprocessing, training and evaluation.', 'tech' => 'Python · OpenCV · TensorFlow'],
        ['name' => 'C2C Marketplace', 'kind' => 'Web & Android', 'text' => 'Customer-to-customer marketplace on web and React Native with cloud media storage.', 'tech' => 'React · React Native · Node.js · MongoDB'],
        ['name' => 'Rental Solution', 'kind' => 'Desktop', 'text' => 'Property, tenant and rent tracking system.', 'tech' => 'C++ · MySQL'],
    ];
    $skills = [
        'Backend' => ['PHP', 'Laravel', 'REST APIs', 'Node.js'],
        'Frontend' => ['JavaScript', 'Blade', 'React', 'HTML / CSS'],
        'Mobile' => ['Flutter', 'Dart', 'React Native', 'Kotlin', 'PWA'],
        'Data' => ['MySQL', 'MariaDB', 'SQLite', 'MongoDB'],
        'Integrations' => ['Agora / WebRTC', 'SMS gateways', 'bKash', 'Firebase FCM'],
        'AI & Vision' => ['Python', 'OpenCV', 'TensorFlow', 'Keras'],
        'Tools' => ['Git', 'GitHub', 'PHPUnit', 'Postman', 'VS Code'],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>{{ $b['full_name'] }} — CV</title>
<style>
  @page { size: A4; margin: 0; }
  * { box-sizing: border-box; }
  html, body { margin: 0; background: #CBD5E1; }
  body { font-family: 'Segoe UI', 'Inter', Arial, sans-serif; color: #1E293B; font-size: 9pt; line-height: 1.45; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .page { width: 210mm; height: 297mm; margin: 10mm auto; background: #fff; display: grid; grid-template-columns: 66mm 1fr; overflow: hidden; box-shadow: 0 20px 50px rgba(15, 23, 42, .25); position: relative; }
  @media print { html, body { background: #fff; } .page { margin: 0; box-shadow: none; page-break-after: always; } .page:last-child { page-break-after: auto; } }
  h1, h2, h3, p, ul { margin: 0; }
  ul { padding-left: 12px; }
  li { margin: 2px 0; }
  a { color: inherit; text-decoration: none; }
  .side { background: linear-gradient(180deg, #0B1120 0%, #0F1A33 100%); color: #CBD5E1; padding: 12mm 7mm 10mm; display: flex; flex-direction: column; gap: 14px; position: relative; }
  .side::after { content: ""; position: absolute; right: 0; top: 0; bottom: 0; width: 2px; background: linear-gradient(#60A5FA, #8B5CF6); }
  .photo { width: 40mm; height: 40mm; margin: 0 auto 4px; border-radius: 50%; padding: 2.5px; background: conic-gradient(#3B82F6, #22D3EE, #8B5CF6, #3B82F6); }
  .photo img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; object-position: center 18%; display: block; border: 2px solid #0B1120; }
  .side h2 { font-size: 7.6pt; letter-spacing: .2em; text-transform: uppercase; color: #93C5FD; font-weight: 800; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
  .side h2::after { content: ""; flex: 1; height: 1px; background: rgba(148, 163, 184, .3); }
  .contact div { display: flex; gap: 7px; align-items: flex-start; margin-bottom: 5px; font-size: 8.2pt; color: #E2E8F0; word-break: break-word; }
  .contact svg { flex-shrink: 0; margin-top: 2px; color: #60A5FA; }
  .grp { margin-bottom: 7px; }
  .grp b { display: block; font-size: 7.6pt; color: #F1F5F9; margin-bottom: 3px; }
  .chips { display: flex; flex-wrap: wrap; gap: 3px; }
  .chips span { font-size: 7.4pt; padding: 1.5px 6px; border-radius: 4px; background: rgba(59, 130, 246, .16); color: #DBEAFE; border: 1px solid rgba(96, 165, 250, .25); }
  .edu div { margin-bottom: 7px; }
  .edu b { display: block; color: #F1F5F9; font-size: 8.2pt; line-height: 1.3; }
  .edu span { font-size: 7.6pt; color: #94A3B8; }
  .qr { margin-top: auto; display: flex; gap: 9px; align-items: center; padding: 8px; border-radius: 8px; background: rgba(255, 255, 255, .05); border: 1px solid rgba(148, 163, 184, .2); }
  .qr-box { width: 19mm; height: 19mm; background: #fff; border-radius: 4px; padding: 3px; flex-shrink: 0; }
  .qr-box img, .qr-box canvas { width: 100% !important; height: 100% !important; display: block; }
  .qr p { font-size: 7.4pt; color: #CBD5E1; }
  .qr p b { display: block; color: #fff; font-size: 8pt; }
  .main { padding: 12mm 11mm 10mm 10mm; display: flex; flex-direction: column; }
  .name { font-size: 25pt; font-weight: 800; color: #0B1120; letter-spacing: -.02em; line-height: 1.05; }
  .role { font-size: 11pt; font-weight: 700; color: #2563EB; margin-top: 3px; }
  .tag { font-size: 8.8pt; color: #475569; margin-top: 6px; max-width: 120mm; }
  .sec { margin-top: 12px; }
  .h { font-size: 8.8pt; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: #0F172A; margin-bottom: 6px; display: flex; align-items: center; gap: 8px; }
  .h::before { content: ""; width: 14px; height: 3px; border-radius: 2px; background: linear-gradient(90deg, #3B82F6, #8B5CF6); }
  .h::after { content: ""; flex: 1; height: 1px; background: #E2E8F0; }
  .summary { font-size: 9pt; color: #334155; }
  .job { position: relative; padding-left: 12px; margin-bottom: 9px; break-inside: avoid; }
  .job::before { content: ""; position: absolute; left: 0; top: 4px; bottom: 2px; width: 2px; background: #DBEAFE; }
  .job::after { content: ""; position: absolute; left: -3px; top: 3px; width: 8px; height: 8px; border-radius: 50%; background: #3B82F6; }
  .row { display: flex; justify-content: space-between; align-items: baseline; gap: 8px; }
  .t { font-weight: 800; font-size: 10pt; color: #0F172A; }
  .t small { font-weight: 600; color: #64748B; font-size: 8.3pt; }
  .d { font-size: 7.8pt; font-weight: 700; color: #2563EB; background: #EFF6FF; padding: 1px 7px; border-radius: 999px; white-space: nowrap; }
  .proj { padding: 9px 10px; border-radius: 8px; border: 1px solid #E2E8F0; background: linear-gradient(180deg, #FFFFFF, #F8FAFC); margin-bottom: 8px; break-inside: avoid; }
  .badges { display: flex; gap: 4px; flex-wrap: wrap; margin: 3px 0 4px; }
  .badges span { font-size: 7pt; font-weight: 700; padding: 1px 6px; border-radius: 4px; background: #0F172A; color: #fff; }
  .tech { font-size: 7.8pt; color: #2563EB; margin-top: 3px; font-weight: 600; }
  .more { display: grid; grid-template-columns: 1fr 1fr; gap: 7px; }
  .more div { padding: 8px 9px; border-radius: 8px; border: 1px solid #E2E8F0; }
  .more b { font-size: 8.8pt; color: #0F172A; }
  .more i { font-style: normal; font-size: 7.4pt; color: #64748B; }
  .more p { font-size: 8pt; color: #475569; margin-top: 2px; }
  .more em { display: block; font-style: normal; font-size: 7.4pt; color: #2563EB; margin-top: 3px; font-weight: 600; }
  .foot { margin-top: auto; padding-top: 8px; display: flex; justify-content: space-between; font-size: 7pt; color: #94A3B8; }
</style>
</head>
<body>
<section class="page">
  <aside class="side">
    @if($photoData)<div class="photo"><img src="{{ $photoData }}" alt="{{ $b['full_name'] }}"></div>@endif
    <div class="contact">
      <h2>Contact</h2>
      <div><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22,6 12,13 2,6"/></svg>{{ $b['email'] }}</div>
      <div><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5l2.8.7a2 2 0 0 1 1.7 2z"/></svg>{{ $b['phone'] }}</div>
      <div><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>{{ $b['location'] }}</div>
      @foreach(['linkedin', 'github', 'kaggle'] as $k)
        @if(!empty($s[$k]['url']))<div><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/></svg>{{ $short($s[$k]['url']) }}</div>@endif
      @endforeach
    </div>
    <div>
      <h2>Skills</h2>
      @foreach($skills as $g => $list)
        <div class="grp"><b>{{ $g }}</b><div class="chips">@foreach($list as $sk)<span>{{ $sk }}</span>@endforeach</div></div>
      @endforeach
    </div>
    <div class="edu">
      <h2>Education</h2>
      @foreach($edu as $e)
        @php $score = $e['cgpa'] ?? $e['gpa'] ?? null; @endphp
        <div><b>{{ $e['degree'] }}</b><span>{{ \Illuminate\Support\Str::before($e['institution'], ' (') }}{{ $score ? ' · ' . ($loop->first ? 'CGPA ' : 'GPA ') . $score : '' }} · {{ $loop->first ? 'Expected Dec 2026' : ($e['passing_year'] ?? '') }}</span></div>
      @endforeach
    </div>
  </aside>

  <main class="main">
    <h1 class="name">{{ $b['full_name'] }}</h1>
    <div class="role">{{ $b['title'] }} · Laravel &amp; Flutter Developer</div>
    <p class="tag">I build practical SaaS products end to end: database design, secure APIs, real-time features, mobile apps and polished interfaces.</p>

    <section class="sec">
      <h2 class="h">Profile</h2>
      <p class="summary">Junior Software Engineer at APPIFLY BD LIMITED and final-year CSE student at BUBT. I independently built DoctorQR, a production doctor-practice and telemedicine platform, and work across Laravel backends, Flutter mobile apps, real-time integrations and automated testing. Interested in AI, computer vision and cyber security.</p>
    </section>

    <section class="sec">
      <h2 class="h">Experience</h2>
      <div class="job">
        <div class="row"><div class="t">Junior Software Engineer <small>· APPIFLY BD LIMITED, Dhaka</small></div><div class="d">Current role</div></div>
        <ul>@foreach($highlights as $h)<li>{{ $h }}</li>@endforeach</ul>
      </div>
      <div class="job">
        <div class="row"><div class="t">Software Development Intern <small>· APPIFLY BD LIMITED</small></div><div class="d">Jun – Aug 2026</div></div>
        <ul><li>Built an Employee Management System in Laravel with authentication, employee records and file storage; debugging and testing within the team.</li></ul>
      </div>
    </section>

    <section class="sec">
      <h2 class="h">Featured Projects</h2>
      @foreach($projects as $p)
        <div class="proj">
          <div class="row"><div class="t">{{ $p['name'] }} <small>· {{ $p['kind'] }}</small></div>@if($p['link'])<div class="d">{{ $p['link'] }}</div>@endif</div>
          <div class="badges">@foreach($p['badges'] as $bd)<span>{{ $bd }}</span>@endforeach</div>
          <ul>@foreach($p['points'] as $pt)<li>{{ $pt }}</li>@endforeach</ul>
          <div class="tech">{{ $p['tech'] }}</div>
        </div>
      @endforeach
    </section>
    <div class="foot"><span>{{ $b['full_name'] }} · Curriculum Vitae</span><span>1 / 2</span></div>
  </main>
</section>

<section class="page">
  <aside class="side">
    <div>
      <h2>Research interests</h2>
      <div class="chips" style="gap:4px"><span>AI &amp; Deep Learning</span><span>Computer Vision</span><span>Medical Imaging</span><span>Cyber Security</span><span>Digital Forensics</span></div>
    </div>
    <div class="edu">
      <h2>Languages</h2>
      <div><b>Bengali</b><span>Native</span></div>
      <div><b>English</b><span>Fluent</span></div>
    </div>
    <div>
      <h2>Strengths</h2>
      <div class="chips"><span>Problem solving</span><span>Ownership</span><span>Communication</span><span>Teamwork</span><span>Fast learner</span></div>
    </div>
    <div class="qr">
      <div class="qr-box" id="qr"></div>
      <p><b>Live portfolio</b>{{ $site }}<br>Case studies, app screenshots &amp; an AI assistant</p>
    </div>
  </aside>
  <main class="main">
    <section class="sec" style="margin-top:0">
      <h2 class="h">More Projects</h2>
      <div class="more">
        @foreach($more as $m)
          <div><b>{{ $m['name'] }}</b> <i>· {{ $m['kind'] }}</i><p>{{ $m['text'] }}</p><em>{{ $m['tech'] }}</em></div>
        @endforeach
      </div>
    </section>
    <section class="sec">
      <h2 class="h">Engineering Practices</h2>
      <ul>
        <li>Modular Laravel architecture with form validation, authorization and secure file handling.</li>
        <li>Automated testing with PHPUnit, systematic debugging and performance tuning.</li>
        <li>Security hardening: XSS and CSRF protection, rate limiting, security headers and audit logging.</li>
        <li>Real-time UX: live queues, chat with phone alerts, PWAs and mobile-first responsive interfaces.</li>
        <li>Mobile delivery with Flutter: REST integration, push notifications and offline reading.</li>
      </ul>
    </section>
    <section class="sec">
      <h2 class="h">Relevant Coursework</h2>
      <p class="summary">{{ implode(' · ', $edu[0]['coursework'] ?? []) }}</p>
    </section>
    <div class="foot"><span>{{ $b['full_name'] }} · Curriculum Vitae</span><span>2 / 2</span></div>
  </main>
</section>
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
  try { new QRCode(document.getElementById('qr'), { text: 'https://{{ $site }}', width: 200, height: 200, colorDark: '#0B1120', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.M }); } catch (e) {}
</script>
</body>
</html>
