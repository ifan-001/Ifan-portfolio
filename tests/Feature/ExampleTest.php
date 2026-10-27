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

    public function test_resume_download_handles_unreleased_state(): void
    {
        $response = $this->get('/resume/download');
        $response->assertStatus(302);
    }
}
