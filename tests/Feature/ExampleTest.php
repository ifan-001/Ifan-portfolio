<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Hasnain Al Ifan');
        $response->assertSee('DoctorQR');
        $response->assertSee('APPIFLY BD LIMITED');
    }

    public function test_contact_form_submission(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'Recruiter Test',
            'email' => 'recruiter@tech.com',
            'subject' => 'Job Inquiry',
            'message' => 'Hello Hasnain, reviewing your portfolio.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);
    }

    public function test_contact_honeypot_rejects_spam_bots(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.com',
            'message' => 'Buy cheap links here now!',
            '_hp_title' => 'Sneaky bot filled field',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
        ]);
    }

    public function test_contact_validation_fails_on_empty_message(): void
    {
        $response = $this->postJson('/contact', [
            'name' => 'John',
            'email' => 'not-an-email',
            'message' => 'hi', // too short (< 10)
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'message']);
    }

    public function test_resume_download_serves_pdf(): void
    {
        $response = $this->get('/resume/download');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_chat_status_endpoint(): void
    {
        $response = $this->get('/api/chat/status');
        $response->assertStatus(200);
        $response->assertJsonStructure(['is_online', 'status_text', 'last_active_at']);
    }

    public function test_chat_toggle_status_endpoint(): void
    {
        $response = $this->postJson('/api/chat/status', ['is_online' => false]);
        $response->assertStatus(200);
        $response->assertJson(['is_online' => false]);

        $responseToggle = $this->postJson('/api/chat/status', ['is_online' => true]);
        $responseToggle->assertStatus(200);
        $responseToggle->assertJson(['is_online' => true]);
    }

    public function test_chat_message_flow(): void
    {
        $session = 'test_session_' . uniqid();
        $sendResponse = $this->postJson('/api/chat/send', [
            'session_id' => $session,
            'message' => 'Hello Hasnain, testing live chat!',
            'visitor_name' => 'Recruiter Visitor',
            'visitor_email' => 'visitor@test.com',
        ]);
        $sendResponse->assertStatus(200);

        $fetchResponse = $this->get("/api/chat/messages?session_id={$session}");
        $fetchResponse->assertStatus(200);
        $fetchResponse->assertSee('Hello Hasnain, testing live chat!');
    }
}
