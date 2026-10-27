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
use Tests\TestCase;

class AdminPlatformTest extends TestCase
{
    protected function getAdminUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'hasnainalifan23@gmail.com'],
            [
                'name' => 'Hasnain Al Ifan',
                'password' => Hash::make('AdminSecretTestPass!'),
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
            'password' => 'AdminSecretTestPass!',
        ]);
        $loginResponse->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        // Access dashboard as authenticated admin
        $dashResponse = $this->actingAs($admin)->get('/admin');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Portfolio Control Center');

        // Logout
        $logoutResponse = $this->actingAs($admin)->post('/portal/logout');
        $logoutResponse->assertRedirect('/portal/login');
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
}
