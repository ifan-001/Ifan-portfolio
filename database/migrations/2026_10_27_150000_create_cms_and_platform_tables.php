<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Profile Settings (Personal Brand Info)
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('short_name')->default('IFAN.');
            $table->string('full_name')->default('Hasnain Al Ifan');
            $table->string('title')->default('Junior Software Engineer');
            $table->string('sub_title')->nullable()->default('Web Application Development | Backend Engineering');
            $table->string('location')->nullable()->default('Mirpur-2, Dhaka, Bangladesh');
            $table->string('email')->default('hasnainalifan23@gmail.com');
            $table->string('phone')->nullable()->default('+8801608564447');
            $table->string('profile_image')->default('/images/profile.jpg');
            $table->string('avatar_fallback')->default('HI');
            $table->text('positioning')->nullable();
            $table->string('hero_headline')->nullable()->default('Building Software That Solves Real Problems.');
            $table->string('hero_subheadline')->nullable()->default('Web Applications. Backend Systems. Practical Engineering.');
            $table->text('short_bio')->nullable();
            $table->json('detailed_bio')->nullable();
            $table->string('resume_path')->default('/resume/Hasnain-Al-Ifan-CV.pdf');
            $table->boolean('resume_available')->default(true);
            $table->string('resume_status_note')->nullable()->default('Official CV available for preview and download.');
            $table->timestamps();
        });

        // 2. Focus Areas (Hero / About)
        Schema::create('focus_areas', function (Blueprint $table) {
            $table->id();
            $table->string('number', 10)->default('01');
            $table->string('title');
            $table->text('description');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Skills
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('Backend'); // Backend, Frontend, Database, Tools, AI & ML, etc.
            $table->integer('proficiency')->default(85); // 1-100
            $table->string('icon')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 4. Experiences
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('designation');
            $table->string('period');
            $table->string('type')->default('Full-time');
            $table->string('timeline_label')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('summary')->nullable();
            $table->json('highlights')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 5. Projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->default('Web Application');
            $table->json('technologies')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('live_url')->nullable();
            $table->string('github_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 6. Educations
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('institution');
            $table->string('degree');
            $table->string('field')->nullable();
            $table->string('period');
            $table->string('cgpa')->nullable();
            $table->string('scale')->default('4.00');
            $table->string('status')->default('Final Year');
            $table->json('achievements')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 7. Research Items
        Schema::create('research_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('Research Topic'); // Research Topic, Publication, Pre-print
            $table->string('domain')->default('Deep Learning & Biomedical Imaging');
            $table->string('status')->default('In Progress');
            $table->text('summary')->nullable();
            $table->json('technologies')->nullable();
            $table->string('link')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 8. Achievements
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('Certification');
            $table->string('organization')->nullable();
            $table->string('year')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 9. Blog Posts
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->json('tags')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->integer('read_time')->default(3); // minutes
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // 10. Social Links
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // github, linkedin, kaggle, email, twitter, etc.
            $table->string('name');
            $table->string('url');
            $table->string('handle')->nullable();
            $table->string('icon')->default('link');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 11. Site Settings (Section Visibility, Appearance, etc.)
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // 12. Privacy-Conscious Analytics Events
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type')->index(); // page_view, project_click, resume_download
            $table->string('page_url')->nullable();
            $table->string('target')->nullable(); // project slug or action
            $table->string('referrer')->nullable();
            $table->string('ip_hash', 64)->nullable()->index(); // SHA256 hashed, anonymized
            $table->string('device_type', 20)->default('desktop'); // desktop, mobile, tablet
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 13. Push Subscriptions (For PWA Web Push Notifications)
        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('endpoint');
            $table->string('public_key')->nullable();
            $table->string('auth_token')->nullable();
            $table->string('device_name')->nullable();
            $table->timestamps();
        });

        // 14. Add status column to contact_messages if not exists
        if (Schema::hasTable('contact_messages') && !Schema::hasColumn('contact_messages', 'status')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->string('status')->default('unread'); // unread, read, replied, archived
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_subscriptions');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('research_items');
        Schema::dropIfExists('educations');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('focus_areas');
        Schema::dropIfExists('profiles');
    }
};
