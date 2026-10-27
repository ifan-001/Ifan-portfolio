<?php

namespace App\Services;

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
use Illuminate\Support\Facades\Cache;

class PortfolioDataService
{
    /**
     * Get the consolidated portfolio data for public display.
     */
    public function getPortfolioData(): array
    {
        $cfg = config('portfolio');
        $profile = Profile::first();

        // 1. Brand & Personal Info
        $brand = [
            'short_name' => $profile?->short_name ?: ($cfg['brand']['short_name'] ?? 'IFAN.'),
            'full_name' => $profile?->full_name ?: ($cfg['brand']['full_name'] ?? 'Hasnain Al Ifan'),
            'title' => $profile?->title ?: ($cfg['brand']['title'] ?? 'Junior Software Engineer'),
            'sub_title' => $profile?->sub_title ?: ($cfg['brand']['sub_title'] ?? 'Web Application Development | Backend Engineering'),
            'location' => $profile?->location ?: ($cfg['brand']['location'] ?? 'Mirpur-2, Dhaka, Bangladesh'),
            'email' => $profile?->email ?: ($cfg['brand']['email'] ?? 'hasnainalifan23@gmail.com'),
            'phone' => $profile?->phone ?: ($cfg['brand']['phone'] ?? '+8801608564447'),
            'profile_image' => $profile?->profile_image ?: ($cfg['brand']['profile_image'] ?? '/images/profile.jpg'),
            'avatar_fallback' => $profile?->avatar_fallback ?: ($cfg['brand']['avatar_fallback'] ?? 'HI'),
            'positioning' => $profile?->positioning ?: ($cfg['brand']['positioning'] ?? ''),
            'hero_headline' => $profile?->hero_headline ?: ($cfg['brand']['hero_headline'] ?? 'Building Software That Solves Real Problems.'),
            'hero_subheadline' => $profile?->hero_subheadline ?: ($cfg['brand']['hero_subheadline'] ?? 'Web Applications. Backend Systems. Practical Engineering.'),
            'short_bio' => $profile?->short_bio ?: ($cfg['brand']['short_bio'] ?? ''),
            'detailed_bio' => !empty($profile?->detailed_bio) ? $profile->detailed_bio : ($cfg['brand']['detailed_bio'] ?? []),
        ];

        // 2. Resume
        $resume = [
            'path' => $profile?->resume_path ?: ($cfg['resume']['path'] ?? '/resume/Hasnain-Al-Ifan-CV.pdf'),
            'is_available' => $profile ? (bool) $profile->resume_available : ($cfg['resume']['is_available'] ?? true),
            'status_note' => $profile?->resume_status_note ?: ($cfg['resume']['status_note'] ?? 'Official CV available for preview and download.'),
        ];

        // 3. Focus Areas
        $dbFocusAreas = FocusArea::orderBy('order')->get();
        if ($dbFocusAreas->isNotEmpty()) {
            $focusAreas = $dbFocusAreas->map(function ($fa) {
                return [
                    'number' => $fa->number,
                    'title' => $fa->title,
                    'description' => $fa->description,
                ];
            })->toArray();
        } else {
            $focusAreas = $cfg['focus_areas'] ?? [];
        }

        // 4. Skills & Skill Categories
        $dbSkills = Skill::orderBy('order')->get();
        if ($dbSkills->isNotEmpty()) {
            $grouped = $dbSkills->groupBy('category');
            $skillCategories = [];
            foreach ($grouped as $catTitle => $skillsInCat) {
                $skillCategories[] = [
                    'title' => $catTitle,
                    'description' => 'Technologies and methodologies applied in production and academic projects',
                    'skills' => $skillsInCat->map(function ($s) {
                        return [
                            'id' => $s->id,
                            'name' => $s->name,
                            'context' => $s->category,
                            'proficiency' => $s->proficiency,
                        ];
                    })->toArray(),
                ];
            }
        } else {
            $skillCategories = $cfg['skill_categories'] ?? [];
        }

        // 5. Work Experience
        $dbExp = Experience::orderBy('order')->get();
        if ($dbExp->isNotEmpty()) {
            $experience = $dbExp->map(function ($exp) {
                return [
                    'id' => $exp->id,
                    'company' => $exp->company,
                    'location' => $exp->location,
                    'website' => $exp->website,
                    'roles' => [
                        [
                            'designation' => $exp->designation,
                            'period' => $exp->period,
                            'type' => $exp->type,
                            'current' => (bool) $exp->is_current,
                            'summary' => $exp->summary,
                        ],
                    ],
                    'timeline_label' => $exp->timeline_label ?: $exp->period,
                    'highlights' => $exp->highlights ?: [],
                ];
            })->toArray();
        } else {
            $experience = $cfg['experience'] ?? [];
        }

        // 6. Projects
        $dbProjects = Project::orderBy('order')->get();
        if ($dbProjects->isNotEmpty()) {
            $projects = $dbProjects->map(function ($p) {
                $workflows = [];
                // If it's doctor-qr and config has workflows, merge workflows
                if ($p->slug === 'doctor-qr' && !empty(config('portfolio.projects.0.workflows'))) {
                    $workflows = config('portfolio.projects.0.workflows');
                }

                return [
                    'id' => $p->slug,
                    'db_id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug,
                    'category' => $p->category,
                    'category_label' => $p->tagline ?: ucfirst($p->category),
                    'featured' => (bool) $p->is_featured,
                    'summary' => $p->summary,
                    'role' => $p->tagline ?: 'Software Developer',
                    'context' => $p->summary,
                    'live_url' => $p->live_url,
                    'repo_url' => $p->github_url,
                    'tech_stack' => $p->technologies ?: [],
                    'workflows' => $workflows,
                    'payment_states' => ['Pending', 'Partial Paid', 'Paid', 'Failed', 'Refunded'],
                    'contribution_details' => !empty($p->description) ? explode("\n\n", $p->description) : [],
                    'key_features' => !empty($p->description) ? explode("\n\n", $p->description) : [],
                ];
            })->toArray();
        } else {
            $projects = $cfg['projects'] ?? [];
        }

        // 7. Education
        $dbEdu = Education::orderBy('order')->get();
        if ($dbEdu->isNotEmpty()) {
            $education = $dbEdu->map(function ($e, $idx) {
                return [
                    'id' => $e->id,
                    'degree' => $e->degree,
                    'institution' => $e->institution,
                    'location' => 'Dhaka, Bangladesh',
                    'status' => $e->status,
                    'cgpa' => $e->cgpa ? "{$e->cgpa} / {$e->scale}" : null,
                    'gpa' => $e->cgpa ? "{$e->cgpa} / {$e->scale}" : '4.50',
                    'group' => $e->field ?: 'Science',
                    'passing_year' => $e->period,
                    'expected_graduation' => $e->period,
                    'is_primary' => $idx === 0,
                    'coursework' => $e->achievements ?: [],
                ];
            })->toArray();
        } else {
            $education = $cfg['education'] ?? [];
        }

        // 8. Research
        $dbRes = ResearchItem::orderBy('order')->get();
        if ($dbRes->isNotEmpty()) {
            $research = [
                'intro' => $cfg['research']['intro'] ?? "Alongside software engineering, I'm interested in exploring how intelligent technologies can be applied to complex real-world problems.",
                'note' => $cfg['research']['note'] ?? 'These reflect academic curiosity and capstone exploration.',
                'interests' => $dbRes->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'title' => $r->title,
                        'icon' => 'cpu',
                        'description' => $r->summary,
                    ];
                })->toArray(),
            ];
        } else {
            $research = $cfg['research'] ?? [];
        }

        // 9. Highlights & Achievements
        $dbAch = Achievement::orderBy('order')->get();
        if ($dbAch->isNotEmpty()) {
            $highlights = $dbAch->map(function ($a) {
                return [
                    'id' => $a->id,
                    'label' => $a->category,
                    'value' => $a->title,
                    'detail' => $a->description,
                ];
            })->toArray();
        } else {
            $highlights = $cfg['highlights'] ?? [];
        }

        // 10. Social Links
        $dbSocial = SocialLink::where('is_active', true)->orderBy('order')->get();
        if ($dbSocial->isNotEmpty()) {
            $socials = [];
            foreach ($dbSocial as $s) {
                $socials[$s->platform] = [
                    'name' => $s->name,
                    'url' => $s->url,
                    'handle' => $s->handle ?: $s->name,
                    'icon' => $s->icon ?: $s->platform,
                ];
            }
        } else {
            $socials = $cfg['socials'] ?? [];
        }

        // 11. Recent Published Blog Posts
        $recentPosts = BlogPost::published()->take(3)->get();

        // 12. Site Settings
        $settings = [
            'show_hero' => (bool) SiteSetting::get('show_hero', true),
            'show_about' => (bool) SiteSetting::get('show_about', true),
            'show_experience' => (bool) SiteSetting::get('show_experience', true),
            'show_projects' => (bool) SiteSetting::get('show_projects', true),
            'show_skills' => (bool) SiteSetting::get('show_skills', true),
            'show_education' => (bool) SiteSetting::get('show_education', true),
            'show_research' => (bool) SiteSetting::get('show_research', true),
            'show_blog' => (bool) SiteSetting::get('show_blog', true),
            'show_contact' => (bool) SiteSetting::get('show_contact', true),
            'primary_accent_color' => SiteSetting::get('primary_accent_color', '#3B82F6'),
            'live_chat_enabled' => (bool) SiteSetting::get('live_chat_enabled', true),
        ];

        return [
            'brand' => $brand,
            'resume' => $resume,
            'focus_areas' => $focusAreas,
            'skill_categories' => $skillCategories,
            'experience' => $experience,
            'projects' => $projects,
            'education' => $education,
            'research' => $research,
            'highlights' => $highlights,
            'socials' => $socials,
            'blog_posts' => $recentPosts,
            'settings' => $settings,
            'meta' => $cfg['meta'] ?? [],
        ];
    }
}
