<?php

/*
|--------------------------------------------------------------------------
| Project Showcase (case-study data)
|--------------------------------------------------------------------------
| Rich, presentation-only data merged onto projects by slug. Admin-managed
| fields in the database (title, summary, links, thumbnail) always win; this
| file adds galleries, feature lists, stats and status labels. Facts here are
| taken from each project's own documentation and screenshots.
*/

return [
    'order' => ['doctor-qr', 'newsifly', 'employee-management', 'adshield', 'portfolio-platform', 'nuclei-segmentation', 'c2c-web-system', 'c2c-android-app', 'rental-solution'],

    'projects' => [
        'doctor-qr' => [
            'title' => 'DoctorQR',
            'tagline' => 'Digital practice, QR booking and telemedicine platform for doctors in Bangladesh',
            'category' => 'professional',
            'type' => 'SaaS Web Platform · PWA',
            'status' => 'Built at APPIFLY BD LIMITED',
            'year' => '2026',
            'accent' => '#DC2626',
            'icon' => 'qr',
            'role' => 'Software Developer: independently built the web application from company requirements',
            'summary' => 'A multi-tenant digital practice platform. Every doctor gets a branded booking page and a QR code that turns any prescription pad into an appointment. Patients book, pay and join video consultations without creating an account.',
            'problem' => 'Doctors depend on marketplaces and phone calls for serials. DoctorQR gives each doctor their own page, live queue and payments with no commission, and patients never need to install an app or remember a password.',
            'stats' => [
                ['value' => '15', 'label' => 'Domain modules'],
                ['value' => '475', 'label' => 'Passing tests'],
                ['value' => '1,156', 'label' => 'DGDA medicines'],
                ['value' => '0', 'label' => 'Patient logins needed'],
            ],
            'features' => [
                ['title' => 'Zero-login patient PWA', 'desc' => 'Patients scan a QR or open /dr/{doctor} to book a chamber or online slot, upload reports and view prescriptions, all without an account.'],
                ['title' => 'QR-driven booking', 'desc' => 'Dynamic SVG/PNG QR codes printed on pads, cards and posters link straight to the doctor\'s booking flow.'],
                ['title' => 'Live queue & serials', 'desc' => 'Automatic serial numbers, doctor-controlled patient calling and a live queue that syncs every 2.5 seconds with chime and toast alerts.'],
                ['title' => 'Agora video consultation', 'desc' => 'Secure room tokens, in-desk video with circular auto picture-in-picture, draggable snap and fullscreen.'],
                ['title' => '3-column consultation desk', 'desc' => 'Vitals, clinical notes and prescription in one screen, with a 1,156-medicine DGDA library, 695 lab tests and Alt+H history drawer.'],
                ['title' => 'Digital prescriptions & signature', 'desc' => 'Signed digital prescriptions verified by QR, printable or shared as a short link over SMS and WhatsApp.'],
                ['title' => 'Payments & subscriptions', 'desc' => 'bKash payment confirmation, a five-state payment lifecycle and doctor subscription plans with grace periods.'],
                ['title' => 'Admin & verification', 'desc' => 'Super-admin onboarding with BMDC verification, payment checks and a support ticket inbox.'],
            ],
            'tech' => ['Laravel 12', 'PHP 8.4', 'MySQL', 'Modular monolith (nwidart)', 'Blade', 'Vanilla JS', 'Agora RTC', 'WebRTC', 'PWA', 'SMS gateway', 'QR engine', 'PHPUnit / Pest'],
            'gallery' => [
                ['src' => '/images/projects/screens/doctorqr/home.jpg', 'caption' => 'Marketing site: "Your patients, your page, your money."'],
                ['src' => '/images/projects/screens/doctorqr/doctor-dashboard.jpg', 'caption' => 'Doctor portal dashboard'],
                ['src' => '/images/projects/screens/doctorqr/booking-landing.jpg', 'caption' => 'Public doctor booking page'],
                ['src' => '/images/projects/screens/doctorqr/booking-slot.jpg', 'caption' => 'Slot selection'],
                ['src' => '/images/projects/screens/doctorqr/availability.jpg', 'caption' => 'Weekly availability & chambers'],
                ['src' => '/images/projects/screens/doctorqr/qr-code.jpg', 'caption' => 'Personal QR code generator'],
                ['src' => '/images/projects/screens/doctorqr/subscription.jpg', 'caption' => 'Subscription & billing'],
                ['src' => '/images/projects/screens/doctorqr/demo-booking.jpg', 'caption' => 'Interactive booking demo'],
            ],
            'mobile_gallery' => [
                ['src' => '/images/projects/screens/doctorqr/booking-landing-mobile.jpg', 'caption' => 'Doctor page on mobile'],
                ['src' => '/images/projects/screens/doctorqr/booking-slot-mobile.jpg', 'caption' => 'Mobile slot picker'],
                ['src' => '/images/projects/screens/doctorqr/home-mobile.jpg', 'caption' => 'Mobile landing'],
            ],
        ],

        'newsifly' => [
            'title' => 'Newsifly',
            'tagline' => 'Self-hosted, white-label news portal with an AI newsroom and a Flutter app',
            'category' => 'professional',
            'type' => 'News CMS · Web + Mobile App',
            'status' => 'APPIFLY product · In development',
            'year' => '2026',
            'accent' => '#B91C1C',
            'icon' => 'news',
            'role' => 'Developer on the APPIFLY BD LIMITED product team',
            'summary' => 'A complete newsroom product: an admin panel for reporters, editors and admins, a server-rendered public website and a Flutter mobile app, built to be sold as a white-label script.',
            'problem' => 'Small and regional newsrooms need a modern site, an app, push alerts and AI help without paying for a big platform. Newsifly bundles all of it into one install per publisher.',
            'stats' => [
                ['value' => '3', 'label' => 'Editorial roles'],
                ['value' => '2', 'label' => 'Languages (Bangla / English)'],
                ['value' => 'Web + App', 'label' => 'One backend'],
                ['value' => 'AI', 'label' => 'Writing, summary, translate'],
            ],
            'features' => [
                ['title' => 'AI newsroom', 'desc' => 'Reporters generate headlines and full drafts with AI; readers can summarise and translate long stories. Admins control limits and logs.'],
                ['title' => 'Editorial workflow', 'desc' => 'Reporter, editor and admin roles with review, approval, scheduling, revisions and breaking-news strip.'],
                ['title' => 'Rich media', 'desc' => 'Videos, reels, short news cards, photo galleries and a PDF e-paper reader.'],
                ['title' => 'Push notifications', 'desc' => 'Firebase Cloud Messaging alerts from the admin panel straight to phones, with targeting and retry.'],
                ['title' => 'Reader engagement', 'desc' => 'Accounts, bookmarks, comments with moderation, polls and offline reading in the app.'],
                ['title' => 'Monetisation & analytics', 'desc' => 'Ad spaces for web and app, impression tracking, audience analytics and exports.'],
                ['title' => 'White-label branding', 'desc' => 'Logo, colours, pages, maintenance mode, backups and light/dark themes per publisher.'],
                ['title' => 'Flutter mobile app', 'desc' => 'Native Android app with shorts, discover, saved stories, comments and adjustable font size.'],
            ],
            'tech' => ['Laravel 13', 'MariaDB / MySQL', 'Blade', 'REST API', 'Flutter', 'Dart', 'Firebase FCM', 'Gemini AI', 'PWA', 'Dark mode'],
            'gallery' => [
                ['src' => '/images/projects/screens/newsifly/website-home.jpg', 'caption' => 'Public news website'],
                ['src' => '/images/projects/screens/newsifly/admin-dashboard.jpg', 'caption' => 'Newsroom admin dashboard'],
                ['src' => '/images/projects/screens/newsifly/articles.jpg', 'caption' => 'Stories repository'],
                ['src' => '/images/projects/screens/newsifly/ai-settings.jpg', 'caption' => 'AI controls & limits'],
                ['src' => '/images/projects/screens/newsifly/analytics.jpg', 'caption' => 'Audience analytics'],
                ['src' => '/images/projects/screens/newsifly/branding.jpg', 'caption' => 'White-label branding'],
            ],
            'mobile_gallery' => [
                ['src' => '/images/projects/screens/newsifly-app/home_before_scroll.jpg', 'caption' => 'App home'],
                ['src' => '/images/projects/screens/newsifly-app/shorts.jpg', 'caption' => 'Short news'],
                ['src' => '/images/projects/screens/newsifly-app/story_top.jpg', 'caption' => 'Story reader'],
                ['src' => '/images/projects/screens/newsifly-app/discover.jpg', 'caption' => 'Discover'],
                ['src' => '/images/projects/screens/newsifly-app/comments.jpg', 'caption' => 'Comments'],
                ['src' => '/images/projects/screens/newsifly/website-home-mobile.jpg', 'caption' => 'Mobile website'],
            ],
        ],

        'employee-management' => [
            'title' => 'Employee Management System',
            'tagline' => 'Laravel HR records system built during the APPIFLY internship',
            'category' => 'professional',
            'type' => 'Web Application · Laravel',
            'status' => 'Internship project · Completed',
            'year' => '2026',
            'accent' => '#2563EB',
            'icon' => 'users',
            'summary' => 'A secure Laravel application for managing employee records: authentication, full CRUD, profile photos, search, pagination and a statistics dashboard.',
            'problem' => 'Teams that track staff in spreadsheets lose data and history. This system keeps every employee record, photo and salary in one validated, searchable place.',
            'stats' => [
                ['value' => 'CRUD', 'label' => 'Full employee lifecycle'],
                ['value' => 'Auth', 'label' => 'Laravel Breeze'],
                ['value' => 'MVC', 'label' => 'Resource routing'],
            ],
            'features' => [
                ['title' => 'Employee CRUD', 'desc' => 'Add, view, edit, update and delete employee records with validation.'],
                ['title' => 'Profile photos', 'desc' => 'Upload and replace employee avatars with secure storage handling.'],
                ['title' => 'Search & pagination', 'desc' => 'Find employees quickly across large lists.'],
                ['title' => 'Dashboard', 'desc' => 'Total employees, departments, salary totals and recently joined staff.'],
                ['title' => 'Authentication', 'desc' => 'Registration, login and a protected dashboard with Laravel Breeze.'],
            ],
            'tech' => ['Laravel', 'PHP', 'MySQL', 'Laravel Breeze', 'Blade', 'Bootstrap 5'],
            'gallery' => [
                ['src' => '/images/projects/screens/employee-management/dashboard.png', 'caption' => 'Statistics dashboard'],
                ['src' => '/images/projects/screens/employee-management/employee-list.png', 'caption' => 'Employee list'],
                ['src' => '/images/projects/screens/employee-management/add-employee.png', 'caption' => 'Add employee form'],
                ['src' => '/images/projects/screens/employee-management/edit-employee.png', 'caption' => 'Edit employee'],
            ],
        ],

        'adshield' => [
            'title' => 'AdShield',
            'tagline' => 'On-device ad and tracker blocker for Android using a local DNS VPN',
            'category' => 'mobile',
            'type' => 'Android App · Kotlin',
            'status' => 'Personal project · Open source',
            'year' => '2026',
            'accent' => '#E53935',
            'icon' => 'shield',
            'role' => 'Solo developer: architecture, DNS filtering engine, UI and tests',
            'summary' => 'A native Android app that blocks ads and trackers on the device itself. It uses Android\'s VpnService to intercept DNS queries locally and drop advertising domains before they connect.',
            'problem' => 'Most ad blockers send your browsing through someone else\'s server. AdShield makes every filtering decision in local memory, so DNS history never leaves the phone and battery use stays low.',
            'stats' => [
                ['value' => '100%', 'label' => 'On-device filtering'],
                ['value' => 'DNS only', 'label' => 'Port 53, no traffic slowdown'],
                ['value' => '4', 'label' => 'Upstream DNS choices'],
            ],
            'features' => [
                ['title' => 'Local DNS VPN', 'desc' => 'Only DNS packets go through a local TUN loopback; video, downloads and games use the normal network at full speed.'],
                ['title' => 'Curated blocklists', 'desc' => 'AdAway, StevenBlack, OISD, YouTube telemetry and Facebook Audience Network rules built in.'],
                ['title' => 'Custom rules', 'desc' => 'Add your own block or allow domains with live validation and instant matcher updates.'],
                ['title' => 'Upstream DNS choice', 'desc' => 'Cloudflare, Google, Quad9 or AdGuard DNS.'],
                ['title' => 'Privacy by design', 'desc' => 'No external filtering server; honest documentation of what can and cannot be blocked.'],
                ['title' => 'Tested engine', 'desc' => 'JUnit tests for the DNS packet parser, domain matcher and response builder.'],
            ],
            'tech' => ['Kotlin', 'Jetpack Compose', 'Android VpnService', 'DNS packet parsing', 'Coroutines', 'JUnit 4', 'Gradle'],
            'gallery' => [],
        ],

        'portfolio-platform' => [
            'title' => 'Personal Engineering Platform',
            'tagline' => 'This website: a portfolio with a hidden CMS, live chat, AI assistant and mini apps',
            'category' => 'web',
            'type' => 'Laravel Platform · PWA',
            'status' => 'Live · You are using it',
            'year' => '2026',
            'accent' => '#3B82F6',
            'icon' => 'code',
            'role' => 'Designer and full-stack developer',
            'summary' => 'A Laravel platform that powers this portfolio: an in-place visual CMS only the owner can see, a live chat with mobile alerts, a built-in AI assistant, a public feed and a hub of tools and games.',
            'problem' => 'A static portfolio goes stale and cannot talk to visitors. This platform lets the owner update everything from the page itself and answer visitors in real time.',
            'stats' => [
                ['value' => '45+', 'label' => 'Automated tests'],
                ['value' => 'PWA', 'label' => 'Installable admin app'],
                ['value' => '0', 'label' => 'Third-party trackers'],
            ],
            'features' => [
                ['title' => 'Hidden visual CMS', 'desc' => 'Edit text, photos, projects and sections directly on the live page, visible only to the owner.'],
                ['title' => 'Live chat & alerts', 'desc' => 'Visitors message the owner; the admin app plays a chime, vibrates and shows an online green dot.'],
                ['title' => 'Built-in AI assistant', 'desc' => 'Answers questions about projects, features, skills and experience from the portfolio\'s own data.'],
                ['title' => 'Tools & games hub', 'desc' => 'CV builder with templates, developer utilities and LinkedIn-style puzzle games.'],
                ['title' => 'Security hardened', 'desc' => 'Rate limits, CSRF, upload validation, security headers and a privacy-first analytics engine.'],
            ],
            'tech' => ['Laravel 12', 'PHP 8.2', 'SQLite', 'Blade', 'Vanilla JS', 'CSS', 'PWA', 'Service Worker'],
            'gallery' => [],
        ],

        'nuclei-segmentation' => ['accent' => '#8B5CF6', 'icon' => 'brain', 'type' => 'AI · Computer Vision', 'status' => 'Capstone research', 'title' => 'Cell Nuclei Segmentation'],
        'c2c-web-system' => ['accent' => '#0EA5E9', 'icon' => 'cart', 'type' => 'Full-Stack Web', 'status' => 'Academic project'],
        'c2c-android-app' => ['accent' => '#10B981', 'icon' => 'phone', 'type' => 'Mobile · React Native', 'status' => 'Academic project'],
        'rental-solution' => ['accent' => '#F59E0B', 'icon' => 'building', 'type' => 'Desktop · C++', 'status' => 'Academic project'],
    ],
];
