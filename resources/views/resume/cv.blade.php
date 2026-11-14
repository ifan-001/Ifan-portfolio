@php
    $b = $portfolio['brand'];
    $s = $portfolio['socials'];
    $show = config('showcase.projects');
    $photo = public_path(ltrim($b['profile_image'], '/'));
    $photoData = is_file($photo) ? 'data:' . (mime_content_type($photo) ?: 'image/jpeg') . ';base64,' . base64_encode(file_get_contents($photo)) : null;
    $edu = $portfolio['education'];
    $exp = $portfolio['experience'][0] ?? null;
    $projects = collect($portfolio['projects'])->keyBy('slug');
    $cvProjects = [
        ['slug' => 'doctor-qr', 'name' => 'DoctorQR', 'kind' => 'Doctor digital practice & telemedicine SaaS', 'points' => [
            'Multi-tenant platform: each doctor gets a branded booking page and a QR code that turns any prescription into an appointment; patients book, pay and join video calls with no account.',
            'Live serial queue, bKash payment states, SMS alerts, Agora video with auto picture-in-picture and signed digital prescriptions.',
        ], 'tech' => 'Laravel 12, PHP, MySQL, Blade, JavaScript, Agora RTC, SMS gateway, QR engine', 'link' => 'doctor-qr.appiflybd.com'],
        ['slug' => 'newsifly', 'name' => 'Newsifly', 'kind' => 'White-label news portal with AI newsroom and Flutter app', 'points' => [
            'Self-hosted news product with reporter, editor and admin roles, an AI writing assistant, summaries and translation, push notifications, reels, galleries and an e-paper.',
        ], 'tech' => 'Laravel 13, MariaDB, REST API, Flutter, Firebase FCM, Gemini AI', 'link' => null],
        ['slug' => 'employee-management', 'name' => 'Employee Management System', 'kind' => 'Internship project', 'points' => [
            'Laravel system with authentication, employee CRUD, profile photo storage, search, pagination and a statistics dashboard.',
        ], 'tech' => 'Laravel, PHP, MySQL, Laravel Breeze, Bootstrap 5', 'link' => 'github.com/ifan-001/employee-management-laravel'],
        ['slug' => 'adshield', 'name' => 'AdShield', 'kind' => 'Android ad & tracker blocker', 'points' => [
            'Native Android app that filters ads and trackers on the device with a local DNS VPN, curated blocklists, custom rules and unit-tested DNS packet parsing.',
        ], 'tech' => 'Kotlin, Jetpack Compose, Android VpnService, JUnit', 'link' => null],
        ['slug' => 'nuclei-segmentation', 'name' => 'Nuclei Segmentation (Capstone)', 'kind' => 'AI & computer vision', 'points' => [
            'Automated cell nuclei segmentation for biomedical images: preprocessing, deep-learning model training and evaluation.',
        ], 'tech' => 'Python, OpenCV, TensorFlow, Keras', 'link' => null],
        ['slug' => 'c2c', 'name' => 'C2C Marketplace (Web & Android)', 'kind' => 'Academic projects', 'points' => [
            'Customer-to-customer marketplace on web (React, Node.js, MongoDB) and Android (React Native with Cloudinary media storage).',
        ], 'tech' => 'React, React Native, Node.js, MongoDB, Cloudinary', 'link' => null],
        ['slug' => 'rental-solution', 'name' => 'Rental Solution', 'kind' => 'Desktop application', 'points' => [
            'Desktop rental management system for properties, tenant records and rent tracking.',
        ], 'tech' => 'C++, MySQL', 'link' => null],
    ];
    $skillGroups = [
        'Languages' => 'PHP, JavaScript, Python, Java, Kotlin, C, C++, SQL',
        'Backend & Web' => 'Laravel, REST APIs, Blade, Node.js, React, HTML5, CSS3',
        'Mobile' => 'React Native, Expo, Kotlin, Jetpack Compose, PWA',
        'Data' => 'MySQL, MariaDB, SQLite, MongoDB',
        'Integrations' => 'Agora RTC / WebRTC, SMS gateways, bKash, Cloudinary, Firebase FCM',
        'Tools' => 'Git, GitHub, VS Code, XAMPP, Postman, PHPUnit',
        'AI & Vision' => 'OpenCV, TensorFlow, Keras, Deep Learning',
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
  html, body { margin: 0; background: #E5E7EB; }
  body { font-family: 'Segoe UI', 'Inter', Arial, sans-serif; color: #1F2937; font-size: 9.2pt; line-height: 1.42; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  .page { width: 210mm; height: 297mm; margin: 10mm auto; background: #fff; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,.15); }
  .page + .page { margin-top: 0; }
  @media print { html, body { background: #fff; } .page { margin: 0; box-shadow: none; page-break-after: always; } .page:last-child { page-break-after: auto; } }
  a { color: inherit; text-decoration: none; }
  h1, h2, h3, p, ul { margin: 0; }
  ul { padding-left: 13px; }
  li { margin: 1.5px 0; }

  .head { background: #0B1120; color: #F5F7FA; padding: 13mm 15mm 11mm; display: flex; gap: 9mm; align-items: center; position: relative; }
  .head::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 3px; background: linear-gradient(90deg, #60A5FA, #3B82F6, #8B5CF6); }
  .photo { width: 31mm; height: 31mm; border-radius: 50%; overflow: hidden; flex-shrink: 0; border: 2.5px solid #3B82F6; box-shadow: 0 0 0 4px rgba(59,130,246,.18); }
  .photo img { width: 100%; height: 100%; object-fit: cover; object-position: center 18%; display: block; }
  .name { font-size: 25pt; font-weight: 800; letter-spacing: -.02em; line-height: 1.05; }
  .role { font-size: 11.5pt; font-weight: 600; color: #93C5FD; margin-top: 3px; }
  .tagline { color: #CBD5E1; margin-top: 5px; font-size: 9.3pt; max-width: 125mm; }
  .contact { display: grid; grid-template-columns: repeat(3, auto); gap: 3px 16px; margin-top: 8px; font-size: 8.5pt; color: #E2E8F0; justify-content: start; }
  .contact span::before { content: "▸ "; color: #60A5FA; }

  .body { display: grid; grid-template-columns: 1fr 62mm; gap: 8mm; padding: 9mm 15mm 10mm; }
  .sec { margin-bottom: 11px; }
  .h { font-size: 9.6pt; font-weight: 800; text-transform: uppercase; letter-spacing: .14em; color: #1D4ED8; padding-bottom: 3px; border-bottom: 1.4px solid #BFDBFE; margin-bottom: 6px; display: flex; align-items: center; gap: 7px; }
  .h::before { content: ""; width: 7px; height: 7px; border-radius: 2px; background: linear-gradient(135deg, #3B82F6, #8B5CF6); }
  .entry { margin-bottom: 8px; break-inside: avoid; }
  .row { display: flex; justify-content: space-between; gap: 8px; align-items: baseline; }
  .t { font-weight: 700; color: #0F172A; font-size: 10.2pt; }
  .t small { font-weight: 600; color: #475569; font-size: 8.6pt; }
  .d { font-size: 8.3pt; color: #64748B; white-space: nowrap; font-weight: 600; }
  .sub { color: #475569; font-size: 8.8pt; }
  .tech { font-size: 8.3pt; color: #1E40AF; margin-top: 2px; }
  .tech b { color: #0F172A; }
  .side .sec { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 9px 10px; }
  .side .h { border: 0; margin-bottom: 4px; font-size: 8.8pt; }
  .kv { margin-bottom: 5px; }
  .kv b { display: block; font-size: 8.2pt; color: #0F172A; }
  .kv span { font-size: 8.4pt; color: #475569; }
  .chips { display: flex; flex-wrap: wrap; gap: 4px; }
  .chips span { font-size: 7.9pt; padding: 1.5px 7px; border-radius: 999px; background: #EFF6FF; color: #1E40AF; border: 1px solid #DBEAFE; font-weight: 600; }
  .summary { font-size: 9.5pt; color: #334155; }
  .foot { position: absolute; left: 15mm; right: 15mm; bottom: 6mm; display: flex; justify-content: space-between; font-size: 7.5pt; color: #94A3B8; }
</style>
</head>
<body>
<section class="page">
  <header class="head">
    @if($photoData)<div class="photo"><img src="{{ $photoData }}" alt="{{ $b['full_name'] }}"></div>@endif
    <div>
      <h1 class="name">{{ $b['full_name'] }}</h1>
      <div class="role">{{ $b['title'] }} · Laravel &amp; Full-Stack Developer</div>
      <p class="tagline">Building practical SaaS products end to end, from database design and secure APIs to real-time features and polished interfaces.</p>
      <div class="contact">
        <span>{{ $b['email'] }}</span>
        <span>{{ $b['phone'] }}</span>
        <span>{{ $b['location'] }}</span>
        <span>{{ preg_replace('#^https?://(www\.)?#', '', rtrim($s['linkedin']['url'], '/')) }}</span>
        <span>{{ preg_replace('#^https?://(www\.)?#', '', rtrim($s['github']['url'], '/')) }}</span>
        <span>{{ preg_replace('#^https?://(www\.)?#', '', rtrim($s['kaggle']['url'], '/')) }}</span>
      </div>
    </div>
  </header>

  <div class="body">
    <main>
      <section class="sec">
        <h2 class="h">Profile</h2>
        <p class="summary">Junior Software Engineer at APPIFLY BD LIMITED and final-year CSE student at BUBT. I independently built DoctorQR, a production doctor-practice and telemedicine platform, and work across Laravel backends, real-time integrations, mobile apps and testing. Interested in AI, computer vision, medical image analysis and cyber security.</p>
      </section>

      <section class="sec">
        <h2 class="h">Experience</h2>
        <div class="entry">
          <div class="row"><div class="t">Junior Software Engineer <small>· APPIFLY BD LIMITED, Dhaka</small></div><div class="d">Current role</div></div>
          <ul>
            @foreach(array_slice($exp['highlights'] ?? ($exp['roles'][0]['highlights'] ?? []), 0, 4) as $h)
              <li>{{ $h }}</li>
            @endforeach
          </ul>
        </div>
        <div class="entry">
          <div class="row"><div class="t">Software Development Intern <small>· APPIFLY BD LIMITED, Dhaka</small></div><div class="d">20 Jun 2026 – 20 Aug 2026</div></div>
          <ul>
            <li>Built an Employee Management System in Laravel with authentication, employee records and file storage.</li>
            <li>Worked on practical Laravel features, debugging and testing within the company team.</li>
          </ul>
        </div>
      </section>

      <section class="sec">
        <h2 class="h">Selected Projects</h2>
        @foreach(array_slice($cvProjects, 0, 2) as $p)
          <div class="entry">
            <div class="t">{{ $p['name'] }} <small>· {{ $p['kind'] }}</small></div>
            <ul>@foreach($p['points'] as $pt)<li>{{ $pt }}</li>@endforeach</ul>
            <div class="tech"><b>Tech:</b> {{ $p['tech'] }}@if($p['link']) · <b>Link:</b> {{ $p['link'] }}@endif</div>
          </div>
        @endforeach
      </section>
    </main>

    <aside class="side">
      <section class="sec">
        <h2 class="h">Education</h2>
        @foreach($edu as $e)
          <div class="kv">
            <b>{{ $e['degree'] }}</b>
            @php $score = $e['cgpa'] ?? $e['gpa'] ?? null; @endphp
            <span>{{ $e['institution'] }}<br>{{ $score ? ($loop->first ? 'CGPA ' : 'GPA ') . $score . ' · ' : '' }}{{ $loop->first ? 'Expected Dec 2026' : ($e['passing_year'] ?? '') }}</span>
          </div>
        @endforeach
      </section>

      <section class="sec">
        <h2 class="h">Skills</h2>
        @foreach($skillGroups as $g => $list)
          <div class="kv"><b>{{ $g }}</b></div>
          <div class="chips" style="margin-bottom:6px">@foreach(explode(', ', $list) as $sk)<span>{{ $sk }}</span>@endforeach</div>
        @endforeach
      </section>
    </aside>
  </div>
  <div class="foot"><span>{{ $b['full_name'] }} · Curriculum Vitae</span><span>Page 1 of 2</span></div>
</section>

<section class="page">
  <div class="body" style="padding-top:13mm">
    <main>
      <section class="sec">
        <h2 class="h">More Projects</h2>
        @foreach(array_slice($cvProjects, 2) as $p)
          <div class="entry">
            <div class="t">{{ $p['name'] }} <small>· {{ $p['kind'] }}</small></div>
            <ul>@foreach($p['points'] as $pt)<li>{{ $pt }}</li>@endforeach</ul>
            <div class="tech"><b>Tech:</b> {{ $p['tech'] }}@if($p['link']) · <b>Link:</b> {{ $p['link'] }}@endif</div>
          </div>
        @endforeach
      </section>

      <section class="sec">
        <h2 class="h">Engineering Practices</h2>
        <ul>
          <li>Modular Laravel architecture, form validation, authorization and secure file handling.</li>
          <li>Automated testing with PHPUnit, systematic debugging and performance tuning.</li>
          <li>Security hardening: XSS and CSRF protection, rate limiting, security headers, audit logging.</li>
          <li>Real-time UX with polling, audio and toast alerts, PWAs and responsive, mobile-first interfaces.</li>
        </ul>
      </section>

      <section class="sec">
        <h2 class="h">Relevant Coursework</h2>
        <div class="chips">@foreach($edu[0]['coursework'] ?? $edu[0]['relevant_coursework'] ?? [] as $c)<span>{{ $c }}</span>@endforeach</div>
      </section>
    </main>

    <aside class="side">
      <section class="sec">
        <h2 class="h">Research Interests</h2>
        <ul>
          <li>Artificial Intelligence &amp; Deep Learning</li>
          <li>Computer Vision</li>
          <li>Medical Image Analysis</li>
          <li>Cyber Security &amp; Digital Forensics</li>
        </ul>
      </section>
      <section class="sec">
        <h2 class="h">Languages</h2>
        <div class="kv"><b>Bengali</b><span>Native</span></div>
        <div class="kv"><b>English</b><span>Fluent</span></div>
      </section>
      <section class="sec">
        <h2 class="h">Portfolio</h2>
        <p style="font-size:8.6pt;color:#475569">Live case studies, screenshots and an AI assistant that answers questions about my work:</p>
        <p style="font-size:9pt;font-weight:700;color:#1D4ED8;margin-top:4px">{{ str_contains(config('app.url'), 'localhost') || str_contains(config('app.url'), '127.0.0.1') ? 'hasnainalifan.com' : preg_replace('#^https?://#', '', rtrim(config('app.url'), '/')) }}</p>
      </section>
      <section class="sec">
        <h2 class="h">Strengths</h2>
        <div class="chips"><span>Problem solving</span><span>Ownership</span><span>Communication</span><span>Teamwork</span><span>Fast learner</span></div>
      </section>
    </aside>
  </div>
  <div class="foot"><span>{{ $b['full_name'] }} · Curriculum Vitae</span><span>Page 2 of 2</span></div>
</section>
</body>
</html>
