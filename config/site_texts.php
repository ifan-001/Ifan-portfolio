<?php

/*
| Editable site texts (white-label). Defaults live here; the admin panel
| saves overrides in site_settings as "text_<key>". Group => [key => [label, default, type]].
*/

return [
    'Hero' => [
        'hero_status' => ['Status chip', 'Open to software engineering roles', 'text'],
        'hero_typed' => ['Rotating roles (comma separated)', 'Software Engineer, Laravel Backend Developer, Flutter Mobile Developer, SaaS Product Builder, AI & Computer Vision Explorer', 'text'],
    ],
    'Section headings' => [
        'about_title' => ['About: title', 'About me', 'text'],
        'about_lead' => ['About: intro', 'Early-career software engineer who enjoys understanding complete systems, from the database to the last pixel, and shipping software people actually use.', 'textarea'],
        'experience_title' => ['Experience: title', 'Where I work', 'text'],
        'experience_lead' => ['Experience: intro', 'From software development intern to Junior Software Engineer at APPIFLY BD LIMITED, building production web applications.', 'textarea'],
        'projects_title' => ['Projects: title', "Projects I've engineered", 'text'],
        'projects_lead' => ['Projects: intro', 'Production SaaS, a white-label news platform, Android tooling and academic research. Open any project for the full case study with web and app screenshots.', 'textarea'],
        'skills_title' => ['Skills: title', 'Skills & engineering practice', 'text'],
        'skills_lead' => ['Skills: intro', 'Tools I use in production at APPIFLY and across academic projects. Green dots mark production experience.', 'textarea'],
        'education_title' => ['Education: title', 'Education', 'text'],
        'education_lead' => ['Education: intro', 'Computer Science and Engineering at BUBT, built on a strong science foundation.', 'textarea'],
        'research_title' => ['Research: title', 'Research interests', 'text'],
        'research_lead' => ['Research: intro', 'Alongside engineering, I explore how intelligent systems can solve real-world problems.', 'textarea'],
        'contact_title' => ['Contact: title', "Let's build something together", 'text'],
        'contact_lead' => ['Contact: intro', 'Have a role, a project or a question? Send a message, chat live, or ask my AI assistant.', 'textarea'],
    ],
    'Footer & SEO' => [
        'footer_tagline' => ['Footer tagline', 'Software engineer building practical SaaS, mobile and AI-powered products from Dhaka, Bangladesh.', 'textarea'],
        'seo_title' => ['Browser / Google title', 'Hasnain Al Ifan | Software Engineer (Laravel, Flutter)', 'text'],
        'seo_description' => ['Google description', 'Portfolio of Hasnain Al Ifan, Junior Software Engineer at APPIFLY BD LIMITED. Case studies of DoctorQR, Newsifly and more, plus free tools and games.', 'textarea'],
    ],
];
