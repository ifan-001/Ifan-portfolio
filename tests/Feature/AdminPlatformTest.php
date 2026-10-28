<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ContactMessage;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPlatformTest extends TestCase
{
    use DatabaseTransactions;
    protected function getAdminUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'hasnainalifan23@gmail.com'],
            [
                'name' => 'Hasnain Al Ifan',
                'password' => Hash::make('Ifan@Admin2026!'),
            ]
        );
    }

    /**
     * 1. Security: Unauthorized visitors cannot access admin pages.
     */
    public function test_unauthorized_users_are_redirected_to_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/portal/login');

        $response = $this->get('/admin/profile');
        $response->assertRedirect('/portal/login');

        $response = $this->get('/admin/inquiries');
        $response->assertRedirect('/portal/login');
    }

    /**
     * 2. Security: Authorized administrators can log in and log out.
     */
    public function test_admin_authentication_and_logout(): void
    {
        $admin = $this->getAdminUser();

        // Failed attempt with wrong password
        $failResponse = $this->post('/portal/login', [
            'email' => $admin->email,
            'password' => 'WrongPassword123!',
        ]);
        $failResponse->assertSessionHasErrors('email');
        $this->assertGuest();

        // Successful authentication
        $loginResponse = $this->post('/portal/login', [
            'email' => $admin->email,
            'password' => 'Ifan@Admin2026!',
        ]);
        $loginResponse->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        // Access dashboard as authenticated admin
        $dashResponse = $this->actingAs($admin)->get('/admin');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Portfolio Control Center');

        // Logout
        $logoutResponse = $this->actingAs($admin)->post('/portal/logout');
        $logoutResponse->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * 3. CMS: Portfolio content can be created and updated.
     */
    public function test_portfolio_profile_update(): void
    {
        $admin = $this->getAdminUser();

        $updateResponse = $this->actingAs($admin)->post('/admin/profile', [
            'full_name' => 'Hasnain Al Ifan',
            'short_name' => 'IFAN.',
            'title' => 'Software Engineer II',
            'sub_title' => 'Advanced Laravel & Distributed Systems',
            'email' => 'hasnainalifan23@gmail.com',
            'phone' => '+8801608564447',
            'hero_headline' => 'Engineering Resilient Web Systems.',
            'hero_subheadline' => 'Practical Backend & Distributed Architecture.',
            'short_bio' => 'Updated bio for testing purposes.',
            'detailed_bio_text' => 'First paragraph of detailed bio.\n\nSecond paragraph of detailed bio.',
            'positioning' => 'Lead Software Engineer.',
        ]);

        $updateResponse->assertRedirect(route('admin.profile.edit'));
        $updateResponse->assertSessionHas('status');

        $this->assertDatabaseHas('profiles', [
            'title' => 'Software Engineer II',
            'hero_headline' => 'Engineering Resilient Web Systems.',
        ]);

        // Verify changes immediately appear on public homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Software Engineer II');
        $homeResponse->assertSee('Engineering Resilient Web Systems.');
    }

    /**
     * 4. CMS: Skills CRUD operations.
     */
    public function test_skills_crud_operations(): void
    {
        $admin = $this->getAdminUser();

        // Create skill
        $createResponse = $this->actingAs($admin)->post('/admin/skills', [
            'name' => 'GraphQL API',
            'category' => 'Backend & Web Development',
            'proficiency' => 90,
            'is_featured' => 1,
        ]);
        $createResponse->assertRedirect(route('admin.skills.index'));
        $this->assertDatabaseHas('skills', ['name' => 'GraphQL API']);

        $skill = Skill::where('name', 'GraphQL API')->firstOrFail();

        // Update skill
        $updateResponse = $this->actingAs($admin)->put("/admin/skills/{$skill->id}", [
            'name' => 'GraphQL & Apollo',
            'category' => 'Backend & Web Development',
            'proficiency' => 95,
            'is_featured' => 1,
        ]);
        $updateResponse->assertRedirect(route('admin.skills.index'));
        $this->assertDatabaseHas('skills', ['name' => 'GraphQL & Apollo', 'proficiency' => 95]);

        // Delete skill
        $deleteResponse = $this->actingAs($admin)->delete("/admin/skills/{$skill->id}");
        $deleteResponse->assertRedirect(route('admin.skills.index'));
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    /**
     * 5. CMS: Projects CRUD operations.
     */
    public function test_projects_crud_operations(): void
    {
        $admin = $this->getAdminUser();
        Project::where('slug', 'cloud-telemetry-sentinel')->delete();

        $createResponse = $this->actingAs($admin)->post('/admin/projects', [
            'title' => 'Cloud Telemetry Sentinel',
            'slug' => 'cloud-telemetry-sentinel',
            'category' => 'web',
            'tagline' => 'Distributed Monitoring Platform',
            'summary' => 'Real-time telemetry and anomaly detection engine.',
            'description' => 'Architected high-throughput ingest pipeline.',
            'technologies_text' => 'Laravel, Redis, Go, ClickHouse',
            'live_url' => 'https://sentinel.example.com',
            'is_featured' => 1,
        ]);

        $createResponse->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', ['slug' => 'cloud-telemetry-sentinel']);

        // Check on public home
        $homeResponse = $this->get('/');
        $homeResponse->assertSee('Cloud Telemetry Sentinel');
    }

    /**
     * 6. Blog: Create, publish, view public article.
     */
    public function test_blog_lifecycle(): void
    {
        $admin = $this->getAdminUser();
        $slug = 'securing-laravel-platforms-' . uniqid();

        $postResponse = $this->actingAs($admin)->post('/admin/blog', [
            'title' => 'Securing Laravel Platforms',
            'slug' => $slug,
            'excerpt' => 'A guide to hardening web application security.',
            'content' => 'Full article body detailing security policies and headers.',
            'tags_text' => 'Security, Laravel, Architecture',
            'status' => 'published',
            'read_time' => 4,
        ]);

        $postResponse->assertRedirect(route('admin.blog.index'));
        $this->assertDatabaseHas('blog_posts', [
            'slug' => $slug,
            'status' => 'published',
        ]);

        // Public article reader
        $readerResponse = $this->get('/blog/' . $slug);
        $readerResponse->assertStatus(200);
        $readerResponse->assertSee('Securing Laravel Platforms');
    }

    /**
     * 7. Contact Inquiries Management.
     */
    public function test_contact_inquiries_management(): void
    {
        $admin = $this->getAdminUser();

        $msg = ContactMessage::create([
            'name' => 'Tech Recruiter Corp',
            'email' => 'talent@bigtech.com',
            'subject' => 'Software Engineering Position',
            'message' => 'We are impressed with DoctorQR and your background.',
            'status' => 'unread',
        ]);

        // View in admin
        $viewResponse = $this->actingAs($admin)->get("/admin/inquiries/{$msg->id}");
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Tech Recruiter Corp');
        $this->assertDatabaseHas('contact_messages', ['id' => $msg->id, 'status' => 'read']);

        // Update status
        $statusResponse = $this->actingAs($admin)->post("/admin/inquiries/{$msg->id}/status", [
            'status' => 'replied',
        ]);
        $statusResponse->assertStatus(302);
        $this->assertDatabaseHas('contact_messages', ['id' => $msg->id, 'status' => 'replied']);
    }

    /**
     * 8. Live Chat Admin Reply Flow.
     */
    public function test_live_chat_admin_reply(): void
    {
        $admin = $this->getAdminUser();

        $conv = ChatConversation::create([
            'session_id' => 'conv_test_' . uniqid(),
            'visitor_name' => 'Founder Partner',
            'last_message_at' => now(),
        ]);

        ChatMessage::create([
            'conversation_id' => $conv->id,
            'sender' => 'visitor',
            'message' => 'Are you open for contract web development?',
            'is_read' => false,
        ]);

        // Admin replies
        $replyResponse = $this->actingAs($admin)->post('/admin/chat/reply', [
            'conversation_id' => $conv->id,
            'message' => 'Yes, absolutely! Let us discuss your requirements.',
        ]);
        $replyResponse->assertStatus(302);

        $this->assertDatabaseHas('chat_messages', [
            'conversation_id' => $conv->id,
            'sender' => 'admin',
            'message' => 'Yes, absolutely! Let us discuss your requirements.',
        ]);
    }

    /**
     * 9. PWA: Manifest, Service Worker, and Offline Fallback.
     */
    public function test_pwa_requirements_satisfied(): void
    {
        $manifestResponse = $this->get('/manifest.json');
        $manifestResponse->assertStatus(200);
        $manifestResponse->assertJsonFragment([
            'name' => 'Hasnain Portfolio Manager',
            'display' => 'standalone',
        ]);

        $swResponse = $this->get('/sw.js');
        $swResponse->assertStatus(200);
        $this->assertStringContainsString('CACHE_NAME', $swResponse->getContent());

        $offlineResponse = $this->get('/offline.html');
        $offlineResponse->assertStatus(200);
        $offlineResponse->assertSee('Internet Connection Lost');
    }

    /**
     * 10. Web Push Subscription endpoint.
     */
    public function test_web_push_subscription_registration(): void
    {
        $admin = $this->getAdminUser();

        $pushResponse = $this->actingAs($admin)->postJson('/admin/push/subscribe', [
            'endpoint' => 'https://updates.push.services.mozilla.com/wpush/v2/gAAAAAB...',
            'public_key' => 'BNcRdreAL...',
            'auth_token' => 'tBHItDaTt...',
            'device_name' => 'Samsung Galaxy A54 (Chrome Mobile)',
        ]);

        $pushResponse->assertStatus(200);
        $pushResponse->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('push_subscriptions', [
            'device_name' => 'Samsung Galaxy A54 (Chrome Mobile)',
        ]);
    }

    /**
     * 11. 3-Pillar Admin Portal structure and command hub.
     */
    public function test_admin_three_pillar_navigation_and_dashboard(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Core Operations');
        $response->assertSee('Content Management');
        $response->assertSee('System & Insights');
        $response->assertSee('Open Visual Portfolio Editor');

        // Content management redirect
        $contentRedirect = $this->actingAs($admin)->get('/admin/content');
        $contentRedirect->assertRedirect('/?admin_mode=cms');
    }

    /**
     * 12. In-Place Visual CMS presence for Admin vs complete privacy for regular visitors.
     */
    public function test_in_place_visual_cms_admin_vs_guest_privacy(): void
    {
        $admin = $this->getAdminUser();

        // Admin sees the visual toolbar and in-place edit modals
        $adminView = $this->actingAs($admin)->get('/');
        $adminView->assertStatus(200);
        $adminView->assertSee('id="adminCommandBar"', false);
        $adminView->assertSee('id="aboutBioModal"', false);
        $adminView->assertSee('id="profilePhotoModal"', false);
        $adminView->assertSee("openAdminModal('aboutBioModal')", false);

        // Guest / Public visitor sees ZERO admin toolbar, edit buttons, or admin modals
        $this->flushSession();
        auth()->logout();
        $guestView = $this->get('/');
        $guestView->assertStatus(200);
        $guestView->assertDontSee('id="adminCommandBar"', false);
        $guestView->assertDontSee('id="aboutBioModal"', false);
        $guestView->assertDontSee('id="profilePhotoModal"', false);
        $guestView->assertDontSee("openAdminModal('aboutBioModal')", false);
        $guestView->assertDontSee('Visitor View');
    }

    /**
     * 13. In-Place About Bio: update and authentic restore endpoints.
     */
    public function test_about_bio_update_and_restore(): void
    {
        $admin = $this->getAdminUser();

        // Update bio via in-place editor
        $updateResponse = $this->actingAs($admin)->postJson('/admin/profile/about-bio', [
            'paragraphs' => [
                'Custom paragraph 1 for in-place test.',
                'Custom paragraph 2 for in-place test.',
            ],
        ]);
        $updateResponse->assertStatus(200);
        $updateResponse->assertJson(['status' => 'success']);

        // Restore authentic bio
        $restoreResponse = $this->actingAs($admin)->postJson('/admin/profile/about-bio/restore');
        $restoreResponse->assertStatus(200);
        $restoreResponse->assertJson(['status' => 'success']);
        $data = $restoreResponse->json('detailed_bio');
        $this->assertCount(4, $data);
        $this->assertStringContainsString('Bangladesh University of Business and Technology (BUBT)', $data[0]);
        $this->assertStringContainsString('DoctorQR', $data[1]);
    }

    /**
     * 14. Profile photo upload with automatic aspect-ratio fitting and position alignment.
     */
    public function test_photo_upload_with_object_position(): void
    {
        $admin = $this->getAdminUser();

        $photoFile = UploadedFile::fake()->create('profile_test.jpg', 200, 'image/jpeg');

        $uploadResponse = $this->actingAs($admin)->post(
            '/admin/profile/photo',
            [
                'profile_image' => $photoFile,
                'object_position' => 'center 20%',
            ],
            ['Accept' => 'application/json']
        );

        $uploadResponse->assertStatus(200);
        $uploadResponse->assertJson(['status' => 'success']);
        $this->assertNotNull($uploadResponse->json('photo_url'));
    }

    /**
     * 15. In-Place Section visibility toggle.
     */
    public function test_section_visibility_toggle(): void
    {
        $admin = $this->getAdminUser();

        $toggleResponse = $this->actingAs($admin)->postJson('/admin/settings/toggle-section', [
            'section' => 'show_blog',
            'enabled' => false,
        ]);

        $toggleResponse->assertStatus(200);
        $toggleResponse->assertJson(['status' => 'success']);
    }

    /**
     * 16. Web App Version & QR Code Mobile Installation Configuration.
     */
    public function test_web_app_version_and_qr_modal_integration(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Hasnain Portfolio Manager');
        $response->assertSee('Install Web App on Phone');
        $response->assertSee('qrInstallModal');
        $response->assertSee('appVersionModal');
        $response->assertSee('qrcode.min.js');

        // Test PWA Manifest returns dynamic app info
        $manifestResponse = $this->get('/manifest.json');
        $manifestResponse->assertStatus(200);
        $manifestResponse->assertJsonFragment([
            'name' => 'Hasnain Portfolio Manager',
            'start_url' => '/portal/login',
            'display' => 'standalone',
        ]);
    }

    /**
     * 17. Mobile Viewport & Responsive Shell Integration for Samsung Galaxy A54.
     */
    public function test_mobile_viewport_and_responsive_shell_integration(): void
    {
        $admin = $this->getAdminUser();

        // Admin shell layout must include mobile viewport-fit and bottom navigation
        $adminResponse = $this->actingAs($admin)->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('viewport-fit=cover', false);
        $adminResponse->assertSee('mobile-bottom-nav', false);
        $adminResponse->assertSee('table-responsive', false);
        $adminResponse->assertSee('web-app-hero-card', false);
        $adminResponse->assertSee('mobile-brand-pill', false);

        // Login screen must include standalone mobile tags
        auth()->logout();
        $this->flushSession();
        $loginResponse = $this->get('/portal/login');
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('viewport-fit=cover', false);
        $loginResponse->assertSee('rel="manifest"', false);
    }

    /**
     * 18. Unified Inbox: Consolidates live chats and contact inquiries.
     */
    public function test_unified_inbox_and_conversation_flow(): void
    {
        $admin = $this->getAdminUser();

        // Create anonymous chat conversation
        $chat = ChatConversation::create([
            'session_id' => 'session_test_' . uniqid(),
            'visitor_name' => null,
            'last_message_at' => now(),
        ]);
        ChatMessage::create([
            'conversation_id' => $chat->id,
            'sender' => 'visitor',
            'message' => 'Hey, quick question about your architecture consulting.',
            'is_read' => false,
        ]);

        // Create contact inquiry
        $inquiry = ContactMessage::create([
            'name' => 'Sara Connor',
            'email' => 'sara@cyberdyne.tech',
            'subject' => 'Enterprise System Migration',
            'message' => 'We are interested in working together on our cloud migration.',
            'status' => 'unread',
        ]);

        // 1. Unified Inbox List View
        $inboxResponse = $this->actingAs($admin)->get('/admin/inbox');
        $inboxResponse->assertStatus(200);
        $inboxResponse->assertSee('Unified Inbox');
        $inboxResponse->assertSee('Live &amp; Random Portfolio Chats', false);
        $inboxResponse->assertSee('Contact Inquiries &amp; Named Messages', false);
        $inboxResponse->assertSee('Sara Connor');

        // 2. Chat Conversation View with Back Button
        $chatViewResponse = $this->actingAs($admin)->get('/admin/inbox?conversation_id=' . $chat->id);
        $chatViewResponse->assertStatus(200);
        $chatViewResponse->assertSee('Back to Message List');
        $chatViewResponse->assertSee('quick question about your architecture consulting');

        // 3. Contact Inquiry View with Back Button
        $inquiryViewResponse = $this->actingAs($admin)->get('/admin/inbox?inquiry_id=' . $inquiry->id);
        $inquiryViewResponse->assertStatus(200);
        $inquiryViewResponse->assertSee('Back to Message List');
        $inquiryViewResponse->assertSee('sara@cyberdyne.tech');

        // 4. Verify that providing a name or email moves user from Random to Identified Direct Conversations
        $identifiedChat = ChatConversation::create([
            'session_id' => 'session_identified_' . uniqid(),
            'visitor_name' => 'Tarek Rahman',
            'visitor_email' => 'tarek@gmail.com',
            'last_message_at' => now(),
        ]);
        ChatMessage::create([
            'conversation_id' => $identifiedChat->id,
            'sender' => 'visitor',
            'message' => 'Hello Hasnain, I have a Laravel project for you.',
            'is_read' => false,
        ]);

        $inboxAfterIdentify = $this->actingAs($admin)->get('/admin/inbox');
        $inboxAfterIdentify->assertStatus(200);
        $inboxAfterIdentify->assertSee('Identified Direct Conversations');
        $inboxAfterIdentify->assertSee('Tarek Rahman');
        $inboxAfterIdentify->assertSee('tarek@gmail.com');
    }

    /**
     * 19. Content & Feed: Admin post publishing and public feed view.
     */
    public function test_feed_post_creation_and_public_feed_flow(): void
    {
        $admin = $this->getAdminUser();

        // 1. Admin creates a post
        $postResponse = $this->actingAs($admin)->post('/admin/feed', [
            'category' => 'industrial',
            'title' => 'Industrial Automated Systems Deployment',
            'content' => 'High-availability control systems must withstand network partitioning without state corruption.',
            'media_type' => 'none',
            'is_pinned' => true,
        ]);
        $postResponse->assertRedirect('/admin/feed');

        $this->assertDatabaseHas('feed_posts', [
            'title' => 'Industrial Automated Systems Deployment',
            'category' => 'industrial',
            'is_pinned' => 1,
        ]);

        // 2. Public feed page displays the post serially
        $feedResponse = $this->get('/feed');
        $feedResponse->assertStatus(200);
        $feedResponse->assertSee('Industrial Automated Systems Deployment');
        $feedResponse->assertSee('Back to Portfolio');

        // 3. Public like endpoint
        $post = \App\Models\FeedPost::where('title', 'Industrial Automated Systems Deployment')->first();
        $likeResponse = $this->postJson("/feed/{$post->id}/like");
        $likeResponse->assertStatus(200);
        $likeResponse->assertJson(['status' => 'success']);
    }

    /**
     * 20. Feed Post Comments flow.
     */
    public function test_feed_post_comments_flow(): void
    {
        $admin = $this->getAdminUser();

        $post = \App\Models\FeedPost::create([
            'author_name' => 'Hasnain Al Ifan',
            'category' => 'professional',
            'content' => 'Zero-downtime deployment strategies for enterprise Laravel.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        // 1. Visitor posts comment via AJAX
        $commentResponse = $this->postJson("/feed/{$post->id}/comment", [
            'user_name' => 'Fahim Rahman',
            'user_email' => 'fahim@techbd.com',
            'comment' => 'Great insights! How do you handle schema migrations with zero downtime?',
        ]);
        $commentResponse->assertStatus(200);
        $commentResponse->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('feed_post_comments', [
            'feed_post_id' => $post->id,
            'user_name' => 'Fahim Rahman',
        ]);

        // 2. Admin deletes comment
        $comment = $post->comments()->first();
        $deleteResponse = $this->actingAs($admin)->delete("/admin/feed/comments/{$comment->id}");
        $deleteResponse->assertStatus(302);
        $this->assertDatabaseMissing('feed_post_comments', ['id' => $comment->id]);
    }

    /**
     * 21. Incoming message notification check endpoint.
     */
    public function test_incoming_message_notification_check_endpoint(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->getJson('/admin/notifications/check');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_unread',
            'chat_unread',
            'inquiry_unread',
            'latest_preview',
            'latest_sender',
        ]);
    }

    /**
     * 22. Interactive Apps & Tools directory and tools flow.
     */
    public function test_interactive_apps_hub_and_tools_flow(): void
    {
        // 1. Directory
        $dirResponse = $this->get('/apps');
        $dirResponse->assertStatus(200);
        $dirResponse->assertSee('Interactive Projects &amp; Tools Hub', false);
        $dirResponse->assertSee('Live Resume &amp; CV Builder', false);
        $dirResponse->assertSee('Code Breaker: Cyber Defense', false);

        // 2. Resume Builder tool
        $resumeResponse = $this->get('/apps/resume-builder');
        $resumeResponse->assertStatus(200);
        $resumeResponse->assertSee('Interactive Resume &amp; CV Builder', false);
        $resumeResponse->assertSee('Print / Save as PDF');

        // 3. Cyber Game tool
        $gameResponse = $this->get('/apps/cyber-game');
        $gameResponse->assertStatus(200);
        $gameResponse->assertSee('Code Breaker: Cyber Defense');
        $gameResponse->assertSee('gameCanvas');

        // 4. DoctorQR Medical ID Card Generator
        $qrResponse = $this->get('/apps/qr-medical');
        $qrResponse->assertStatus(200);
        $qrResponse->assertSee('DoctorQR Emergency Medical ID Generator');
    }
}




