<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
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

    public function test_chat_toggle_status_requires_admin_login(): void
    {
        $this->postJson('/api/chat/status', ['is_online' => false])->assertStatus(401);
        $this->assertTrue((bool) \App\Models\ChatStatus::current()->is_online);
    }

    public function test_chat_toggle_status_works_for_admin(): void
    {
        $admin = \App\Models\User::factory()->create();

        $this->actingAs($admin)->postJson('/api/chat/status', ['is_online' => false])
            ->assertStatus(200)->assertJson(['is_online' => false]);
        $this->actingAs($admin)->postJson('/api/chat/status', ['is_online' => true])
            ->assertStatus(200)->assertJson(['is_online' => true]);
    }

    public function test_public_cannot_reply_as_admin_or_list_conversations(): void
    {
        $conv = \App\Models\ChatConversation::create([
            'session_id' => 'secret_session_abc',
            'visitor_name' => 'Private Person',
            'visitor_email' => 'private@example.com',
            'last_message_at' => now(),
        ]);

        $this->postJson('/api/chat/reply', ['conversation_id' => $conv->id, 'message' => 'fake admin'])
            ->assertStatus(401);
        $this->assertDatabaseMissing('chat_messages', ['message' => 'fake admin']);

        $leak = $this->getJson('/api/chat/conversations');
        $leak->assertStatus(401);
        $this->assertStringNotContainsString('private@example.com', $leak->getContent());
    }

    public function test_chat_session_is_issued_by_server_and_not_created_by_get(): void
    {
        $this->getJson('/api/chat/messages?session_id=attacker_chosen_id')->assertJson(['messages' => []]);
        $this->assertDatabaseMissing('chat_conversations', ['session_id' => 'attacker_chosen_id']);

        $send = $this->postJson('/api/chat/send', [
            'session_id' => 'attacker_chosen_id',
            'message' => 'Hello from a visitor',
        ])->assertStatus(200);

        $token = $send->json('session_id');
        $this->assertNotSame('attacker_chosen_id', $token);
        $this->assertGreaterThanOrEqual(40, strlen($token));
        $this->assertDatabaseMissing('chat_conversations', ['session_id' => 'attacker_chosen_id']);

        $this->getJson('/api/chat/messages?session_id=' . $token)->assertSee('Hello from a visitor');
    }

    public function test_chat_message_flow(): void
    {
        $sendResponse = $this->postJson('/api/chat/send', [
            'message' => 'Hello Hasnain, testing live chat!',
            'visitor_name' => 'Recruiter Visitor',
            'visitor_email' => 'visitor@test.com',
        ]);
        $sendResponse->assertStatus(200);
        $session = $sendResponse->json('session_id');

        // Follow-up message continues the same thread
        $this->postJson('/api/chat/send', ['session_id' => $session, 'message' => 'Second message'])->assertStatus(200);
        $this->assertSame(1, \App\Models\ChatConversation::count());

        $fetchResponse = $this->get("/api/chat/messages?session_id={$session}");
        $fetchResponse->assertStatus(200);
        $fetchResponse->assertSee('Hello Hasnain, testing live chat!');
        $fetchResponse->assertSee('Second message');
    }

    public function test_admin_status_toggle_button_hidden_from_guests(): void
    {
        $this->get('/')->assertDontSee('id="admin-status-toggle"', false);
        $this->actingAs(\App\Models\User::factory()->create())->get('/')->assertSee('id="admin-status-toggle"', false);
    }
}
