<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Empowering Summit Graduates');
        $response->assertSee('Instant Matric Verification');
    }

    public function test_about_page_renders_successfully(): void
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('About Our Alumni Community');
        $response->assertSee('Directorate of Alumni');
    }

    public function test_features_page_renders_successfully(): void
    {
        $response = $this->get('/features');
        $response->assertStatus(200);
        $response->assertSee('Platform Architecture');
        $response->assertSee('Two-Step Matriculation Verification');
    }

    public function test_how_it_works_page_renders_successfully(): void
    {
        $response = $this->get('/how-it-works');
        $response->assertStatus(200);
        $response->assertSee('How Verification Works');
        $response->assertSee('Frequently Asked Questions');
    }

    public function test_contact_page_renders_successfully(): void
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
        $response->assertSee('Contact the Alumni Directorate');
        $response->assertSee('Irra Road, Offa');
    }

    public function test_privacy_page_renders_successfully(): void
    {
        $response = $this->get('/privacy');
        $response->assertStatus(200);
        $response->assertSee('Privacy Policy');
        $response->assertSee('NDPA 2023');
    }

    public function test_terms_page_renders_successfully(): void
    {
        $response = $this->get('/terms');
        $response->assertStatus(200);
        $response->assertSee('Terms of Membership');
    }

    public function test_contact_form_submission_api(): void
    {
        $response = $this->postJson('/api/contact', [
            'name' => 'Kareem Abdul',
            'email' => 'kareem.abdul@example.com',
            'phone' => '+2348011223344',
            'subject' => 'Transcript & Academic Reference',
            'message' => 'I graduated in 2020 and need an official verification sent to my employer.',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure(['inquiry_id', 'message']);

        $this->assertDatabaseHas('contact_inquiries', [
            'email' => 'kareem.abdul@example.com',
            'subject' => 'Transcript & Academic Reference',
        ]);
    }
}
