<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Built-in portfolio assistant. Answers visitor questions from the portfolio's
 * own data (projects, features, skills, experience, education, contact) with
 * keyword intent detection. No external AI service, no API key, no cost.
 * Replies in Bangla when the question is in Bangla script or Banglish.
 */
class PortfolioAssistant
{
    protected array $data;
    protected string $lang = 'en';

    /** Intent keywords: English, Banglish and Bangla. */
    protected const INTENTS = [
        'greeting' => ['hi', 'hello', 'hey', 'salam', 'assalamu', 'hola', 'yo', 'হ্যালো', 'হাই', 'সালাম', 'আসসালামু'],
        'thanks' => ['thanks', 'thank', 'thx', 'dhonnobad', 'ধন্যবাদ', 'shukriya'],
        'projects' => ['project', 'projects', 'projekt', 'portfolio', 'built', 'build', 'made', 'work', 'works', 'apps', 'kaj', 'প্রজেক্ট', 'প্রকল্প', 'কাজ', 'বানিয়েছ', 'বানিয়েছেন'],
        'features' => ['feature', 'features', 'function', 'functionality', 'does', 'can', 'kore', 'korte', 'ফিচার', 'বৈশিষ্ট্য', 'কি করে', 'কী করে'],
        'tech' => ['tech', 'stack', 'technology', 'technologies', 'framework', 'language', 'built with', 'tools', 'প্রযুক্তি', 'টেক'],
        'link' => ['link', 'live', 'demo', 'url', 'github', 'repo', 'source', 'visit', 'লিংক', 'লিঙ্ক'],
        'skills' => ['skill', 'skills', 'expertise', 'know', 'jane', 'jano', 'proficient', 'good at', 'দক্ষতা', 'স্কিল', 'জানে', 'জানেন'],
        'experience' => ['experience', 'job', 'company', 'appifly', 'intern', 'internship', 'career', 'working', 'chakri', 'অভিজ্ঞতা', 'চাকরি', 'কোম্পানি'],
        'education' => ['education', 'study', 'university', 'bubt', 'cgpa', 'gpa', 'degree', 'hsc', 'ssc', 'college', 'school', 'porashona', 'পড়াশোনা', 'শিক্ষা', 'বিশ্ববিদ্যালয়'],
        'contact' => ['contact', 'email', 'mail', 'phone', 'number', 'reach', 'linkedin', 'jogajog', 'যোগাযোগ', 'ইমেইল', 'ফোন'],
        'hire' => ['hire', 'hiring', 'available', 'freelance', 'collaborate', 'opportunity', 'offer', 'নিয়োগ', 'ফ্রিল্যান্স'],
        'resume' => ['cv', 'resume', 'biodata', 'সিভি', 'রেজুমে'],
        'about' => ['who', 'about', 'yourself', 'introduce', 'hasnain', 'ifan', 'ke', 'porichoy', 'পরিচয়', 'কে'],
        'research' => ['research', 'interest', 'ai', 'machine learning', 'deep learning', 'computer vision', 'security', 'gobeshona', 'গবেষণা'],
        'apps' => ['tool', 'tools', 'game', 'games', 'resume builder', 'cv builder', 'play', 'খেলা', 'গেম', 'টুল'],
        'feed' => ['feed', 'post', 'update', 'updates', 'blog', 'ফিড', 'পোস্ট'],
        'location' => ['location', 'where', 'dhaka', 'thake', 'kothay', 'কোথায়', 'থাকেন'],
    ];

    /** Aliases so visitors can refer to projects loosely. */
    protected const PROJECT_ALIASES = [
        'doctor-qr' => ['doctorqr', 'doctor qr', 'doctor-qr', 'doctor', 'telemedicine', 'prescription', 'ডক্টর', 'ডাক্তার'],
        'newsifly' => ['newsifly', 'news', 'newspaper', 'নিউজ', 'সংবাদ'],
        'employee-management' => ['employee', 'ems', 'hr', 'staff', 'এমপ্লয়ি'],
        'adshield' => ['adshield', 'ad shield', 'adblock', 'ad block', 'ad blocker', 'dns', 'vpn'],
        'portfolio-platform' => ['this website', 'this site', 'portfolio platform', 'your website', 'ei website', 'এই ওয়েবসাইট'],
        'nuclei-segmentation' => ['nuclei', 'segmentation', 'capstone', 'cell', 'medical image'],
        'c2c-web-system' => ['c2c web', 'ecommerce', 'e-commerce'],
        'c2c-android-app' => ['c2c android', 'react native', 'marketplace app', 'android app'],
        'rental-solution' => ['rental', 'tenant', 'property', 'c++'],
    ];

    protected const BANGLISH = ['ki', 'kemon', 'ache', 'achhe', 'acho', 'koto', 'kothay', 'tumi', 'apni', 'apnar', 'tomar', 'bolo', 'bolun', 'kore', 'korte', 'gulo', 'shomporke', 'somporke', 'jante', 'chai', 'amake', 'koro', 'keno', 'kivabe', 'kibhabe', 'naki', 'ar', 'er', 'theke', 'hoy', 'dao', 'den'];

    public function __construct(PortfolioDataService $service)
    {
        $this->data = $service->getPortfolioData();
    }

    public function answer(string $question): array
    {
        $raw = trim(Str::limit($question, 500, ''));
        $q = mb_strtolower($raw);
        $this->lang = $this->detectLanguage($q);

        $project = $this->matchProject($q);
        $scores = $this->scoreIntents($q);

        // A named project always wins; sub-intent decides which part to describe.
        if ($project) {
            return $this->projectAnswer($project, $scores, $q);
        }

        // Try a feature search before falling back to generic intents
        $top = $this->topIntent($scores);

        return match ($top) {
            'greeting' => $this->greeting(),
            'thanks' => $this->reply($this->t('Happy to help! Ask me anything else about Hasnain\'s work.', 'খুশি হলাম! Hasnain এর কাজ নিয়ে আর কিছু জানতে চাইলে জিজ্ঞেস করুন।'), $this->defaultSuggestions()),
            'projects' => $this->projectsList(),
            'skills', 'tech' => $this->skills(),
            'experience' => $this->experience(),
            'education' => $this->education(),
            'contact', 'hire' => $this->contact($top === 'hire'),
            'resume' => $this->resume(),
            'about' => $this->about(),
            'research' => $this->research(),
            'apps' => $this->apps(),
            'feed' => $this->feed(),
            'location' => $this->reply($this->t('Hasnain is based in ' . $this->brand('location') . '.', 'Hasnain থাকেন ' . $this->brand('location') . ' এ।'), $this->defaultSuggestions()),
            default => $this->techSearch($q) ?? $this->featureSearch($q) ?? $this->fallback(),
        };
    }

    // ------------------------------------------------------------------ intents

    protected function detectLanguage(string $q): string
    {
        if (preg_match('/[\x{0980}-\x{09FF}]/u', $q)) {
            return 'bn';
        }
        $words = preg_split('/[^a-z]+/', $q, -1, PREG_SPLIT_NO_EMPTY);
        $hits = count(array_intersect($words, self::BANGLISH));

        return $hits >= 2 ? 'bn' : 'en';
    }

    protected function scoreIntents(string $q): array
    {
        $scores = [];
        foreach (self::INTENTS as $intent => $words) {
            $score = 0;
            foreach ($words as $w) {
                if ($this->contains($q, $w)) {
                    $score += mb_strlen($w) > 4 ? 2 : 1;
                }
            }
            if ($score) {
                $scores[$intent] = $score;
            }
        }

        return $scores;
    }

    protected function topIntent(array $scores): ?string
    {
        if (! $scores) {
            return null;
        }
        arsort($scores);

        return array_key_first($scores);
    }

    protected function contains(string $haystack, string $needle): bool
    {
        if (preg_match('/[\x{0980}-\x{09FF}]/u', $needle) || str_contains($needle, ' ')) {
            return str_contains($haystack, $needle);
        }

        return (bool) preg_match('/(?<![a-z])' . preg_quote($needle, '/') . '(?![a-z])/u', $haystack);
    }

    protected function matchProject(string $q): ?array
    {
        $best = null;
        $bestLen = 0;
        foreach ($this->projects() as $p) {
            $slug = $p['slug'];
            $names = array_merge(self::PROJECT_ALIASES[$slug] ?? [], [mb_strtolower($p['showcase']['name'] ?? ''), $slug]);
            foreach (array_filter($names) as $alias) {
                if ($this->contains($q, $alias) && mb_strlen($alias) > $bestLen) {
                    $best = $p;
                    $bestLen = mb_strlen($alias);
                }
            }
        }

        return $best;
    }

    // ------------------------------------------------------------------ answers

    protected function projectAnswer(array $p, array $scores, string $q): array
    {
        $s = $p['showcase'];
        $name = $s['name'];
        $links = $this->projectLinks($p);

        // Specific feature mentioned inside this project?
        $feature = $this->bestFeature([$p], $q, 2);
        if ($feature && ! isset($scores['tech']) && ! isset($scores['link'])) {
            [$proj, $f] = $feature;

            return $this->reply(
                $this->t("**{$name} — {$f['title']}**\n{$f['desc']}", "**{$name} — {$f['title']}**\n{$f['desc']}"),
                [$this->t("All features of {$name}", "{$name} এর সব ফিচার"), $this->t("{$name} tech stack", "{$name} এর টেক স্ট্যাক")],
                $links
            );
        }

        if (isset($scores['tech'])) {
            $tech = implode(', ', $s['tech']);

            return $this->reply(
                $this->t("**{$name}** is built with: {$tech}.", "**{$name}** তৈরি হয়েছে এগুলো দিয়ে: {$tech}।"),
                [$this->t("What does {$name} do?", "{$name} কী করে?"), $this->t('Show all projects', 'সব প্রজেক্ট দেখাও')],
                $links
            );
        }

        if (isset($scores['link'])) {
            $text = $links
                ? $this->t("Here are the links for **{$name}**:", "**{$name}** এর লিংক:")
                : $this->t("**{$name}** has no public link yet, but you can see screenshots in the Projects section.", "**{$name}** এর এখনো কোনো পাবলিক লিংক নেই, তবে Projects সেকশনে স্ক্রিনশট দেখতে পারেন।");

            return $this->reply($text, [$this->t("Features of {$name}", "{$name} এর ফিচার")], array_merge($links, [['label' => 'View case study', 'url' => '#project-' . $p['slug']]]));
        }

        $features = array_slice($s['features'], 0, isset($scores['features']) ? 8 : 5);
        $list = implode("\n", array_map(fn ($f) => '• **' . $f['title'] . '**' . ($f['desc'] ? ' — ' . $f['desc'] : ''), $features));
        $intro = $s['tagline'] ? $s['tagline'] . '.' : '';
        $summary = $p['summary'] ?? '';
        $role = $s['role'] ? $this->t("\n\n**Hasnain's role:** ", "\n\n**Hasnain এর ভূমিকা:** ") . $s['role'] : '';

        $text = $this->t(
            "**{$name}** — {$intro}\n{$summary}\n\n**Key features:**\n{$list}{$role}",
            "**{$name}** — {$intro}\n{$summary}\n\n**প্রধান ফিচারগুলো:**\n{$list}{$role}"
        );

        return $this->reply($text, [
            $this->t("{$name} tech stack", "{$name} এর টেক স্ট্যাক"),
            $this->t("{$name} live link", "{$name} এর লিংক"),
            $this->t('Other projects', 'অন্য প্রজেক্টগুলো'),
        ], array_merge([['label' => 'Open case study', 'url' => '#project-' . $p['slug']]], $links));
    }

    protected function projectsList(): array
    {
        $lines = array_map(function ($p) {
            $s = $p['showcase'];

            return '• **' . $s['name'] . '** — ' . ($s['tagline'] ?: $s['type']);
        }, $this->projects());

        $text = $this->t(
            "Hasnain has built " . count($lines) . " projects:\n" . implode("\n", $lines) . "\n\nAsk about any of them, for example \"What are DoctorQR's features?\"",
            "Hasnain " . count($lines) . "টি প্রজেক্ট তৈরি করেছেন:\n" . implode("\n", $lines) . "\n\nযেকোনো একটা নিয়ে জিজ্ঞেস করুন, যেমন \"DoctorQR এর ফিচার কী?\""
        );

        $names = array_slice(array_map(fn ($p) => $p['showcase']['name'], $this->projects()), 0, 3);

        return $this->reply($text, array_map(fn ($n) => $this->t("Tell me about {$n}", "{$n} সম্পর্কে বলো"), $names), [['label' => 'Go to Projects', 'url' => '#projects']]);
    }

    /** "Does he know Laravel?" style questions: find a technology in skills and project stacks. */
    protected function techSearch(string $q): ?array
    {
        $known = [];
        foreach ($this->data['skill_categories'] ?? [] as $cat) {
            foreach ($cat['skills'] ?? [] as $sk) {
                $known[] = is_array($sk) ? ($sk['name'] ?? '') : $sk;
            }
        }
        foreach ($this->projects() as $p) {
            $known = array_merge($known, $p['showcase']['tech']);
        }

        $match = null;
        foreach (array_unique(array_filter($known)) as $tech) {
            $base = mb_strtolower(trim(preg_replace('/[\d.+]+$|\(.*\)/', '', $tech)));
            if (mb_strlen($base) >= 2 && $this->contains($q, $base) && (! $match || mb_strlen($base) > mb_strlen($match))) {
                $match = $base;
            }
        }
        if (! $match) {
            return null;
        }

        $used = array_values(array_map(fn ($p) => $p['showcase']['name'], array_filter($this->projects(), function ($p) use ($match) {
            return (bool) array_filter($p['showcase']['tech'], fn ($t) => str_contains(mb_strtolower($t), $match));
        })));
        $label = ucwords($match);
        $where = $used ? implode(', ', $used) : null;

        return $this->reply(
            $this->t(
                "Yes, **{$label}** is part of Hasnain's skill set." . ($where ? "\nHe has used it in: {$where}." : ''),
                "হ্যাঁ, **{$label}** Hasnain এর দক্ষতার মধ্যে আছে।" . ($where ? "\nব্যবহার করেছেন: {$where}।" : '')
            ),
            array_merge(array_map(fn ($n) => $this->t("Tell me about {$n}", "{$n} সম্পর্কে বলো"), array_slice($used, 0, 2)), [$this->t('All skills', 'সব দক্ষতা')]),
            [['label' => 'View skills', 'url' => '#skills']]
        );
    }

    protected function featureSearch(string $q): ?array
    {
        $hit = $this->bestFeature($this->projects(), $q, 3);
        if (! $hit) {
            return null;
        }
        [$p, $f] = $hit;
        $name = $p['showcase']['name'];

        return $this->reply(
            $this->t("That's part of **{$name}**:\n**{$f['title']}** — {$f['desc']}", "এটা **{$name}** প্রজেক্টের অংশ:\n**{$f['title']}** — {$f['desc']}"),
            [$this->t("Tell me about {$name}", "{$name} সম্পর্কে বলো"), $this->t('Show all projects', 'সব প্রজেক্ট দেখাও')],
            array_merge([['label' => 'Open case study', 'url' => '#project-' . $p['slug']]], $this->projectLinks($p))
        );
    }

    /** Score features by word overlap with the question. */
    protected function bestFeature(array $projects, string $q, int $minScore): ?array
    {
        $stop = ['the', 'and', 'for', 'with', 'what', 'does', 'how', 'can', 'are', 'about', 'tell', 'his', 'your', 'this', 'that', 'feature', 'features', 'project', 'from', 'into', 'any', 'ki', 'kore', 'ache'];
        $words = array_diff(array_filter(preg_split('/[^\p{L}\p{N}]+/u', $q), fn ($w) => mb_strlen($w) > 2), $stop);
        if (! $words) {
            return null;
        }

        $best = null;
        $bestScore = 0;
        foreach ($projects as $p) {
            foreach ($p['showcase']['features'] as $f) {
                $hay = mb_strtolower($f['title'] . ' ' . $f['desc']);
                $title = mb_strtolower($f['title']);
                $score = 0;
                foreach ($words as $w) {
                    if (str_contains($title, $w)) {
                        $score += 3;
                    } elseif (str_contains($hay, $w)) {
                        $score += 1;
                    }
                }
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = [$p, $f];
                }
            }
        }

        return $bestScore >= $minScore ? $best : null;
    }

    protected function skills(): array
    {
        $lines = array_map(function ($cat) {
            $names = array_map(fn ($s) => is_array($s) ? ($s['name'] ?? '') : $s, $cat['skills'] ?? []);

            return '• **' . $cat['title'] . ':** ' . implode(', ', array_slice(array_filter($names), 0, 8));
        }, array_slice($this->data['skill_categories'] ?? [], 0, 7));

        return $this->reply(
            $this->t("Hasnain's technical skills:\n" . implode("\n", $lines), "Hasnain এর টেকনিক্যাল দক্ষতা:\n" . implode("\n", $lines)),
            [$this->t('Which projects use Laravel?', 'কোন প্রজেক্টে Laravel ব্যবহার হয়েছে?'), $this->t('Work experience', 'কাজের অভিজ্ঞতা')],
            [['label' => 'View skills', 'url' => '#skills']]
        );
    }

    protected function experience(): array
    {
        $lines = [];
        foreach ($this->data['experience'] ?? [] as $exp) {
            foreach ($exp['roles'] ?? [] as $role) {
                $lines[] = '• **' . $role['designation'] . '** at ' . $exp['company'] . ' (' . $role['period'] . ')' . (! empty($role['summary']) ? "\n  " . $role['summary'] : '');
            }
        }

        return $this->reply(
            $this->t("Professional experience:\n" . implode("\n", $lines), "পেশাগত অভিজ্ঞতা:\n" . implode("\n", $lines)),
            [$this->t('What did he build there?', 'সেখানে কী তৈরি করেছেন?'), $this->t('Education', 'পড়াশোনা')],
            [['label' => 'View experience', 'url' => '#experience']]
        );
    }

    protected function education(): array
    {
        $lines = array_map(function ($e) {
            $score = $e['cgpa'] ?? $e['gpa'] ?? null;

            return '• **' . $e['degree'] . '** — ' . $e['institution'] . ($score ? ' (' . $score . ')' : '') . (! empty($e['status']) ? ' · ' . $e['status'] : '');
        }, $this->data['education'] ?? []);

        return $this->reply(
            $this->t("Education:\n" . implode("\n", $lines), "শিক্ষাগত যোগ্যতা:\n" . implode("\n", $lines)),
            [$this->t('Research interests', 'গবেষণার আগ্রহ'), $this->t('Skills', 'দক্ষতা')],
            [['label' => 'View education', 'url' => '#education']]
        );
    }

    protected function contact(bool $hire): array
    {
        $email = $this->brand('email');
        $phone = $this->brand('phone');
        $linkedin = $this->data['socials']['linkedin']['url'] ?? null;

        $intro = $hire
            ? $this->t("Hasnain is open to software engineering opportunities and collaborations.", "Hasnain সফটওয়্যার ইঞ্জিনিয়ারিং সুযোগ ও কোলাবোরেশনের জন্য আগ্রহী।")
            : $this->t("You can reach Hasnain here:", "Hasnain এর সাথে যোগাযোগ:");

        $text = $intro . "\n• Email: {$email}\n• Phone: {$phone}" . ($linkedin ? "\n• LinkedIn: {$linkedin}" : '')
            . $this->t("\n\nOr switch to the **Message Hasnain** tab to chat with him directly.", "\n\nঅথবা **Message Hasnain** ট্যাবে গিয়ে সরাসরি চ্যাট করুন।");

        $links = [['label' => 'Send email', 'url' => 'mailto:' . $email], ['label' => 'Contact form', 'url' => '#contact']];
        if ($linkedin) {
            $links[] = ['label' => 'LinkedIn', 'url' => $linkedin];
        }

        return $this->reply($text, [$this->t('Download CV', 'সিভি ডাউনলোড')], $links, ['open_live_chat' => true]);
    }

    protected function resume(): array
    {
        return $this->reply(
            $this->t('You can open or download Hasnain\'s CV as a PDF.', 'Hasnain এর সিভি PDF হিসেবে খুলতে বা ডাউনলোড করতে পারেন।'),
            [$this->t('Want to build your own CV?', 'নিজের সিভি বানাতে চান?')],
            [['label' => 'Open CV (PDF)', 'url' => route('resume.download')], ['label' => 'CV Builder', 'url' => route('apps.resume-builder')]]
        );
    }

    protected function about(): array
    {
        $b = $this->data['brand'];
        $text = $this->t(
            "**{$b['full_name']}** is a {$b['title']} at " . ($this->data['experience'][0]['company'] ?? '') . ", based in {$b['location']}.\n" . ($b['short_bio'] ?? ''),
            "**{$b['full_name']}** একজন {$b['title']}, কাজ করছেন " . ($this->data['experience'][0]['company'] ?? '') . " এ। থাকেন {$b['location']}।\n" . ($b['short_bio'] ?? '')
        );

        return $this->reply($text, $this->defaultSuggestions(), [['label' => 'About section', 'url' => '#about']]);
    }

    protected function research(): array
    {
        $items = array_map(fn ($r) => '• **' . $r['title'] . '** — ' . ($r['description'] ?? ''), $this->data['research']['interests'] ?? []);

        return $this->reply(
            $this->t("Research interests:\n" . implode("\n", $items), "গবেষণার আগ্রহ:\n" . implode("\n", $items)),
            [$this->t('Tell me about the nuclei segmentation capstone', 'Nuclei segmentation প্রজেক্ট সম্পর্কে বলো')],
            [['label' => 'Research section', 'url' => '#research']]
        );
    }

    protected function apps(): array
    {
        return $this->reply(
            $this->t(
                "The **Apps & Tools** hub has free utilities and games:\n• CV Builder with professional templates and PDF download\n• Developer tools: JSON formatter, password generator, QR code maker and more\n• Puzzle games like Queens, Tango, Zip, Memory and 2048",
                "**Apps & Tools** হাবে ফ্রি টুল আর গেম আছে:\n• প্রফেশনাল টেমপ্লেটসহ CV Builder, PDF ডাউনলোড\n• ডেভেলপার টুল: JSON formatter, password generator, QR code maker আরও অনেক\n• পাজল গেম: Queens, Tango, Zip, Memory, 2048"
            ),
            [],
            [['label' => 'Open Apps & Tools', 'url' => route('apps.index')]]
        );
    }

    protected function feed(): array
    {
        return $this->reply(
            $this->t('Hasnain shares project updates, milestones and engineering notes on his Feed. You can like and comment there.', 'Hasnain তার Feed এ প্রজেক্ট আপডেট আর ইঞ্জিনিয়ারিং নোট শেয়ার করেন। সেখানে লাইক ও কমেন্ট করতে পারবেন।'),
            [],
            [['label' => 'Open Feed', 'url' => route('feed.index')]]
        );
    }

    protected function greeting(): array
    {
        return $this->reply(
            $this->t(
                "Hi! I'm Hasnain's portfolio assistant. Ask me about his projects, features, skills, experience or how to contact him.",
                "হ্যালো! আমি Hasnain এর পোর্টফোলিও অ্যাসিস্ট্যান্ট। তার প্রজেক্ট, ফিচার, দক্ষতা, অভিজ্ঞতা বা যোগাযোগ নিয়ে জিজ্ঞেস করুন।"
            ),
            $this->defaultSuggestions()
        );
    }

    protected function fallback(): array
    {
        return $this->reply(
            $this->t(
                "I'm not sure about that one. I can tell you about Hasnain's projects and their features, his skills, experience, education, CV or contact details. You can also ask him directly in the **Message Hasnain** tab.",
                "এটা ঠিক বুঝতে পারিনি। আমি Hasnain এর প্রজেক্ট ও ফিচার, দক্ষতা, অভিজ্ঞতা, পড়াশোনা, সিভি বা যোগাযোগ নিয়ে বলতে পারি। চাইলে **Message Hasnain** ট্যাবে সরাসরি তাকে জিজ্ঞেস করুন।"
            ),
            $this->defaultSuggestions()
        );
    }

    // ------------------------------------------------------------------ helpers

    protected function defaultSuggestions(): array
    {
        return [
            $this->t('What projects has he built?', 'কী কী প্রজেক্ট আছে?'),
            $this->t('DoctorQR features', 'DoctorQR এর ফিচার'),
            $this->t('What are his skills?', 'দক্ষতা কী কী?'),
            $this->t('How can I contact him?', 'কীভাবে যোগাযোগ করব?'),
        ];
    }

    protected function projects(): array
    {
        return array_values(array_filter($this->data['projects'] ?? [], fn ($p) => ! empty($p['showcase'])));
    }

    protected function projectLinks(array $p): array
    {
        $links = [];
        if (! empty($p['live_url'])) {
            $links[] = ['label' => 'Live site', 'url' => $p['live_url']];
        }
        if (! empty($p['repo_url'])) {
            $links[] = ['label' => 'GitHub', 'url' => $p['repo_url']];
        }

        return $links;
    }

    protected function brand(string $key): string
    {
        return (string) ($this->data['brand'][$key] ?? '');
    }

    protected function t(string $en, string $bn): string
    {
        return $this->lang === 'bn' ? $bn : $en;
    }

    protected function reply(string $text, array $suggestions = [], array $links = [], array $extra = []): array
    {
        return array_merge([
            'answer' => $text,
            'lang' => $this->lang,
            'suggestions' => array_values(array_slice(array_filter($suggestions), 0, 4)),
            'links' => array_values(array_slice($links, 0, 4)),
        ], $extra);
    }
}
