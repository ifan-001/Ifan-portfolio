<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\BlogPost;
use App\Models\Education;
use App\Models\Experience;
use App\Models\FocusArea;
use App\Models\Profile;
use App\Models\Project;
use App\Models\ResearchItem;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PortfolioDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cfg = config('portfolio');

        // 1. Seed or Update Admin User
        $adminEmail = $cfg['brand']['email'] ?? 'hasnainalifan23@gmail.com';
        $adminPassword = env('ADMIN_INITIAL_PASSWORD', 'Ifan@Admin2026!');

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $cfg['brand']['full_name'] ?? 'Hasnain Al Ifan',
                'password' => Hash::make($adminPassword),
            ]
        );

        // 2. Profile Singleton
        if (isset($cfg['brand'])) {
            $brand = $cfg['brand'];
            $resume = $cfg['resume'] ?? [];

            Profile::updateOrCreate(
                ['id' => 1],
                [
                    'short_name' => $brand['short_name'] ?? 'IFAN.',
                    'full_name' => $brand['full_name'] ?? 'Hasnain Al Ifan',
                    'title' => $brand['title'] ?? 'Junior Software Engineer',
                    'sub_title' => $brand['sub_title'] ?? 'Web Application Development | Backend Engineering',
                    'location' => $brand['location'] ?? 'Mirpur-2, Dhaka, Bangladesh',
                    'email' => $brand['email'] ?? 'hasnainalifan23@gmail.com',
                    'phone' => $brand['phone'] ?? '+8801608564447',
                    'profile_image' => $brand['profile_image'] ?? '/images/profile.jpg',
                    'avatar_fallback' => $brand['avatar_fallback'] ?? 'HI',
                    'positioning' => $brand['positioning'] ?? '',
                    'hero_headline' => $brand['hero_headline'] ?? 'Building Software That Solves Real Problems.',
                    'hero_subheadline' => $brand['hero_subheadline'] ?? 'Web Applications. Backend Systems. Practical Engineering.',
                    'short_bio' => $brand['short_bio'] ?? '',
                    'detailed_bio' => $brand['detailed_bio'] ?? [],
                    'resume_path' => $resume['path'] ?? '/resume/Hasnain-Al-Ifan-CV.pdf',
                    'resume_available' => $resume['is_available'] ?? true,
                    'resume_status_note' => $resume['status_note'] ?? 'Official CV available for preview and download.',
                ]
            );
        }

        // 3. Focus Areas
        if (!empty($cfg['focus_areas'])) {
            FocusArea::truncate();
            foreach ($cfg['focus_areas'] as $idx => $fa) {
                FocusArea::create([
                    'number' => $fa['number'] ?? sprintf('%02d', $idx + 1),
                    'title' => $fa['title'],
                    'description' => $fa['description'],
                    'order' => $idx,
                ]);
            }
        }

        // 4. Skills
        if (!empty($cfg['skill_categories'])) {
            Skill::truncate();
            $order = 0;
            foreach ($cfg['skill_categories'] as $cat) {
                $categoryTitle = $cat['title'];
                foreach ($cat['skills'] as $skillItem) {
                    Skill::create([
                        'name' => $skillItem['name'],
                        'category' => $categoryTitle,
                        'proficiency' => 85,
                        'icon' => null,
                        'is_featured' => true,
                        'order' => $order++,
                    ]);
                }
            }
        }

        // 5. Work Experiences
        if (!empty($cfg['experience'])) {
            Experience::truncate();
            foreach ($cfg['experience'] as $idx => $exp) {
                $primaryRole = $exp['roles'][0] ?? [];
                Experience::create([
                    'company' => $exp['company'],
                    'location' => $exp['location'] ?? 'Dhaka, Bangladesh',
                    'website' => $exp['website'] ?? null,
                    'designation' => $primaryRole['designation'] ?? 'Junior Software Engineer',
                    'period' => $primaryRole['period'] ?? 'Present',
                    'type' => $primaryRole['type'] ?? 'Full-time',
                    'timeline_label' => $exp['timeline_label'] ?? 'Present',
                    'is_current' => $primaryRole['current'] ?? true,
                    'summary' => $primaryRole['summary'] ?? null,
                    'highlights' => $exp['highlights'] ?? [],
                    'order' => $idx,
                ]);
            }
        }

        // 6. Projects
        if (!empty($cfg['projects'])) {
            Project::truncate();
            foreach ($cfg['projects'] as $idx => $proj) {
                Project::create([
                    'title' => $proj['title'],
                    'slug' => $proj['slug'] ?? Str::slug($proj['title']),
                    'tagline' => $proj['category_label'] ?? null,
                    'summary' => $proj['summary'] ?? null,
                    'description' => implode("\n\n", $proj['contribution_details'] ?? []),
                    'category' => $proj['category'] ?? 'web',
                    'technologies' => $proj['tech_stack'] ?? [],
                    'thumbnail' => null,
                    'featured_image' => null,
                    'live_url' => $proj['live_url'] ?? null,
                    'github_url' => $proj['repo_url'] ?? null,
                    'is_featured' => !empty($proj['featured']),
                    'order' => $idx,
                ]);
            }
        }

        // 7. Educations
        if (!empty($cfg['education'])) {
            Education::truncate();
            foreach ($cfg['education'] as $idx => $edu) {
                Education::create([
                    'institution' => $edu['institution'],
                    'degree' => $edu['degree'],
                    'field' => $edu['group'] ?? 'Computer Science & Engineering',
                    'period' => $edu['passing_year'] ?? 'Expected 2026',
                    'cgpa' => $edu['cgpa'] ?? ($edu['gpa'] ?? '2.86'),
                    'scale' => '4.00',
                    'status' => $edu['status'] ?? 'Completed',
                    'achievements' => $edu['coursework'] ?? [],
                    'order' => $idx,
                ]);
            }
        }

        // 8. Research Items
        if (!empty($cfg['research']['interests'])) {
            ResearchItem::truncate();
            foreach ($cfg['research']['interests'] as $idx => $res) {
                ResearchItem::create([
                    'title' => $res['title'],
                    'type' => 'Research Interest',
                    'domain' => 'Computer Science & AI',
                    'status' => 'Active Investigation',
                    'summary' => $res['description'],
                    'technologies' => ['Python', 'TensorFlow', 'Deep Learning'],
                    'order' => $idx,
                ]);
            }
        }

        // 9. Achievements & Highlights
        if (!empty($cfg['highlights'])) {
            Achievement::truncate();
            foreach ($cfg['highlights'] as $idx => $hl) {
                Achievement::create([
                    'title' => $hl['value'],
                    'category' => $hl['label'],
                    'organization' => $hl['detail'],
                    'year' => '2026',
                    'description' => $hl['detail'],
                    'order' => $idx,
                ]);
            }
        }

        // 10. Social Links
        if (!empty($cfg['socials'])) {
            SocialLink::truncate();
            $order = 0;
            foreach ($cfg['socials'] as $key => $soc) {
                SocialLink::create([
                    'platform' => $key,
                    'name' => $soc['name'],
                    'url' => $soc['url'],
                    'handle' => $soc['handle'] ?? null,
                    'icon' => $soc['icon'] ?? 'link',
                    'is_active' => true,
                    'order' => $order++,
                ]);
            }
        }

        // 11. Initial Blog Posts
        if (BlogPost::count() === 0) {
            BlogPost::create([
                'title' => 'Building DoctorQR: Engineering a Production Doctor Practice Platform in Laravel',
                'slug' => 'building-doctorqr-laravel-production-platform',
                'excerpt' => 'An architectural walkthrough of developing DoctorQR, integrating Agora WebRTC for consultations, automated queueing, and SMS gateways in Laravel.',
                'content' => "## The Vision Behind DoctorQR\n\nDoctorQR was engineered to solve a practical challenge for individual healthcare practitioners in Bangladesh: providing patients with a seamless, zero-friction appointment booking experience while giving doctors full control over queues, video teleconsultations, and digital prescriptions.\n\n### Architectural Foundations\n\n- **Framework**: PHP 8.2+ with Laravel 12\n- **Real-Time Video**: Agora RTC Token Generation and custom Picture-in-Picture (PIP) WebRTC client\n- **Patient Queueing**: Doctor-controlled live patient calling with multi-state payment lifecycles\n- **SMS Notifications**: Automated transactional dispatch via Alpha SMS (`sms_net_bd`)\n\n### Key Lessons\n\n1. Always decouple third-party communication layers behind service interfaces.\n2. In high-stakes medical workflows, state transitions (Pending → Paid → In-Consultation → Completed) must be atomic and audit-logged.",
                'cover_image' => null,
                'tags' => ['Laravel', 'PHP', 'Agora RTC', 'DoctorQR', 'Architecture'],
                'status' => 'published',
                'read_time' => 4,
                'views_count' => 128,
                'published_at' => now()->subDays(3),
            ]);

            BlogPost::create([
                'title' => 'Practical Guide to Picture-in-Picture WebRTC Video in Modern Web Apps',
                'slug' => 'practical-guide-picture-in-picture-webrtc-video',
                'excerpt' => 'How to implement seamless floating video consultations with Picture-in-Picture (PIP) API so doctors can review medical records without interrupting video.',
                'content' => "## Seamless Doctor-Patient Consultations\n\nWhen a doctor conducts an online video consultation, they frequently need to inspect patient history, prescription templates, and lab reports on the same screen.\n\n### The Problem with Fixed Video Windows\n\nTraditional video windows lock the interface or take up the entire viewport. By implementing the HTML5 Picture-in-Picture (PiP) API in combination with WebRTC streams, the consultation stream detaches into a floating window whenever the doctor scrolls down the patient chart.\n\n### Technical Implementation Summary\n\n- Track viewport intersection of the primary video element.\n- Request `videoElement.requestPictureInPicture()` when scrolled out of view.\n- Handle stream sync and mute/unmute events across detached windows.",
                'cover_image' => null,
                'tags' => ['WebRTC', 'JavaScript', 'Video Consultation', 'Frontend'],
                'status' => 'published',
                'read_time' => 5,
                'views_count' => 94,
                'published_at' => now()->subDay(),
            ]);
        }

        // 12. Site Settings Defaults
        $defaultSettings = [
            'show_hero' => true,
            'show_about' => true,
            'show_experience' => true,
            'show_projects' => true,
            'show_skills' => true,
            'show_education' => true,
            'show_research' => true,
            'show_blog' => true,
            'show_contact' => true,
            'primary_accent_color' => '#3B82F6',
            'live_chat_enabled' => true,
            'maintenance_mode' => false,
        ];

        foreach ($defaultSettings as $key => $val) {
            SiteSetting::set($key, $val);
        }
    }
}
