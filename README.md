# Hasnain Al Ifan — Professional Software Engineering Portfolio

> **Production-Grade Personal Portfolio Website built with PHP 8.2+ and Laravel 11.**  
> Official Visual Identity: **Dark Navy** (`#0B1120`), **Off-White** (`#F5F7FA`), and **Electric Blue** (`#3B82F6`).

---

## 🌟 Executive Summary

This portfolio represents the authentic career, production software development experience, and academic journey of **Hasnain Al Ifan**, Junior Software Engineer at **APPIFLY BD LIMITED** and final-year Computer Science and Engineering student at **Bangladesh University of Business and Technology (BUBT)** in Dhaka, Bangladesh.

It prominently showcases:
1. **Flagship Project — DoctorQR**: An independently engineered doctor-centric digital practice SaaS platform featuring QR prescription booking, serial management, payment state tracking, transactional SMS, and WebRTC video consultations with Picture-in-Picture (PIP) via Agora RTC.
2. **Professional Experience**: Continuous company tenure at APPIFLY BD LIMITED advancing from Software Development Intern (June 2026 – August 2026) to Junior Software Engineer.
3. **Academic & Research Portfolio**: Real university systems spanning mobile development (React Native C2C app), desktop software (C++ & MySQL rental solution), and deep learning computer vision (automated cell nuclei segmentation capstone).

---

## 🎨 Official Brand Identity & Color Palette

The portfolio strictly adheres to three official core colors:

| Color Role | Name | Hex Code | Purpose |
| :--- | :--- | :--- | :--- |
| **Primary Background** | Dark Navy | `#0B1120` | Core backdrop for modern, distraction-free aesthetic |
| **Primary Typography** | Off-White | `#F5F7FA` | High-contrast, readable text, titles, and headers |
| **Accent & Focus** | Electric Blue | `#3B82F6` | Primary action buttons, active navigation, badges, highlights |

Subtle navy variations (`#0F172A`, `#131D31`, `#1E293B`) provide refined card separation and elevation without relying on heavy gradients or neon clutter.

---

## 🛠️ Technology Stack & Architecture

- **Backend**: PHP 8.2+ with Laravel 11.x framework
- **Templating**: Laravel Blade (`resources/views/`)
- **Styling**: Production-grade Vanilla CSS (`public/css/portfolio.css`) with CSS custom properties and responsive grid/flexbox
- **Frontend Logic**: Lightweight, accessible Vanilla JavaScript (`public/js/portfolio.js`)
- **Database**: SQLite default for portability (`database/database.sqlite`), compatible with MySQL
- **SEO & Performance**: JSON-LD Schema.org structured data, Open Graph tags, semantic HTML5, lazy loading, and `robots.txt`/`sitemap.xml`

---

## 📂 Project Structure

```text
portfolio/
├── app/
│   └── Http/
│       └── Controllers/
│           └── PortfolioController.php   # Handles page rendering, AJAX contact, and resume fallback
├── config/
│   └── portfolio.php                    # Centralized typed configuration for all content & projects
├── database/
│   └── database.sqlite                  # Lightweight local database
├── public/
│   ├── css/
│   │   └── portfolio.css                # Official brand-color responsive stylesheet
│   ├── js/
│   │   └── portfolio.js                 # Mobile drawer, scroll spy, tab switcher, AJAX contact
│   ├── images/
│   │   ├── profile.jpg                  # Official portrait photo of Hasnain Al Ifan
│   │   └── projects/                    # Project showcase and architecture assets
│   ├── resume/
│   │   └── Hasnain-Al-Ifan-CV.pdf       # Target directory for the final verified CV
│   ├── robots.txt                       # Search engine crawler instructions
│   └── sitemap.xml                      # Canonical sitemap
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php            # Master layout with SEO meta & Schema.org data
│       ├── partials/
│       │   ├── nav.blade.php            # Sticky header with active scroll spy
│       │   ├── hero.blade.php           # Hero presentation with portrait & CTAs
│       │   ├── highlights.blade.php     # 4 evidence-based factual highlights
│       │   ├── about.blade.php          # Biography & 4 engineering focus cards
│       │   ├── experience.blade.php     # APPIFLY BD LIMITED continuous timeline
│       │   ├── projects.blade.php       # DoctorQR flagship showcase & project grid
│       │   ├── skills.blade.php         # Categorized skills with verified badges
│       │   ├── education.blade.php      # BUBT B.Sc. CSE & secondary degrees
│       │   ├── research.blade.php       # Beyond Application Development interests
│       │   ├── contact.blade.php        # Interactive contact form & direct methods
│       │   └── footer.blade.php         # Monogram & verified copyright
│       ├── errors/
│       │   └── 404.blade.php            # Custom styled 404 error page
│       └── portfolio/
│           └── index.blade.php          # Main assembled portfolio view
├── routes/
│   └── web.php                          # Clean routes for home, contact, and resume
└── tests/
    └── Feature/
        └── ExampleTest.php              # Automated test suite (HTTP 200, contact, resume)
```

---

## 🚀 Quickstart & Local Development

### Prerequisites
- PHP `>= 8.2`
- Composer `>= 2.0`
- Git

### Installation Steps

1. **Clone or open the project directory**:
   ```bash
   cd d:\portfolio
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Initialize Database**:
   ```bash
   php artisan migrate --force
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
   Open your browser and visit: `http://127.0.0.1:8000`

6. **Run Automated Test Suite**:
   ```bash
   php artisan test
   ```

---

## ⚙️ Content Customization Guide

All portfolio content is decoupled from template markup and managed within **`config/portfolio.php`**.

### 1. Replacing / Updating Your Resume (CV)
1. Place your final PDF at: `public/resume/Hasnain-Al-Ifan-CV.pdf`.
2. Open `config/portfolio.php` and set `'is_available' => true`:
   ```php
   'resume' => [
       'path' => '/resume/Hasnain-Al-Ifan-CV.pdf',
       'is_available' => true,
       'status_note' => 'Official CV available for download.',
   ],
   ```
3. The navigation and mobile buttons will immediately switch to active download buttons.

### 2. Updating Profile Photo
- Place your photo at `public/images/profile.jpg`.
- If the image is removed or missing, an accessible fallback monogram (`HI`) automatically displays.

### 3. Adding a New Project
In `config/portfolio.php`, add an array entry to the `'projects'` array:
```php
[
    'id' => 'new-project',
    'title' => 'Project Title',
    'slug' => 'new-project',
    'category' => 'web', // 'professional', 'web', 'mobile', 'desktop', 'ai'
    'category_label' => 'Web Application / Backend',
    'featured' => false,
    'summary' => 'Concise, evidence-based description of what the project does.',
    'role' => 'Backend Developer',
    'context' => 'Production or Academic context.',
    'live_url' => 'https://example.com',
    'repo_url' => 'https://github.com/ifan-001/example',
    'tech_stack' => ['PHP', 'Laravel', 'MySQL'],
    'key_features' => ['Feature 1', 'Feature 2'],
    'contribution_details' => ['Specific contribution 1'],
]
```

---

## 🌐 Production Deployment

### Option A: Traditional VPS (Ubuntu / Nginx / Apache / PHP-FPM)
1. Clone the repository into `/var/www/portfolio`.
2. Point Nginx document root to `/var/www/portfolio/public`.
3. Set permissions:
   ```bash
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```
4. Run `composer install --optimize-autoloader --no-dev`.
5. Run `php artisan config:cache && php artisan route:cache && php artisan view:cache`.

### Option B: Platform as a Service (Railway / Render / Fly.io / Heroku)
- Set build command: `composer install --no-dev --optimize-autoloader`
- Set web command: `php artisan serve --host=0.0.0.0 --port=$PORT`
- Set environment variables (`APP_ENV=production`, `APP_KEY=...`, `APP_DEBUG=false`).

---

## 📄 License & Verification
Designed and developed for **Hasnain Al Ifan**. All rights reserved.
