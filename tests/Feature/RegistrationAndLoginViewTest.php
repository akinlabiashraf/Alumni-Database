<?php

namespace Tests\Feature;

use App\Models\GraduateRecord;
use App\Models\User;
use Tests\TestCase;

class RegistrationAndLoginViewTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_register_page_renders_successfully(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Alumni Verification');
        $response->assertSee('Instant Matric Verification');
        $response->assertSee('Manual Verification Request');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In to Alumni Portal');
        $response->assertSee('Email Address or Matric Number');
        $response->assertSee('Password');
    }

    public function test_forgot_password_page_renders_successfully(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertSee('Reset Your Password');
        $response->assertSee('Password Recovery');
    }

    public function test_verify_and_register_alumnus_wizard_flow(): void
    {
        // 1. Pick an unclaimed record from seeded sets (e.g., SU/21/MSS/032 - Zainab Adeleke)
        $record = GraduateRecord::where('status', 'unclaimed')->first();
        $this->assertNotNull($record, 'Expected at least one unclaimed graduate record to exist in test database.');

        // 2. Call verify-matric
        $verifyRes = $this->postJson('/api/verify-matric', [
            'matric_number' => $record->matric_number,
        ]);

        $verifyRes->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'verified',
            ]);

        $token = $verifyRes->json('verification_token');
        $this->assertNotEmpty($token);

        // 3. Register with matching token
        $email = 'graduate.' . strtolower(str_replace('/', '', $record->matric_number)) . '@example.com';
        $registerRes = $this->postJson('/api/register', [
            'matric_number' => $record->matric_number,
            'verification_token' => $token,
            'name' => $record->full_name,
            'email' => $email,
            'phone' => '+2348099887766',
            'password' => 'SafePass123!',
            'password_confirmation' => 'SafePass123!',
            'ndpa_consent' => true,
        ]);

        $registerRes->assertStatus(201)
            ->assertJson([
                'success' => true,
                'redirect' => '/dashboard',
            ]);

        // 4. Verify record is now claimed
        $updatedRecord = GraduateRecord::find($record->id);
        $this->assertEquals('claimed', $updatedRecord->status);
        $this->assertNotNull($updatedRecord->claimed_by_user_id);

        // 5. Test login with matric number and new password
        $loginRes = $this->postJson('/api/login', [
            'login' => $record->matric_number,
            'password' => 'SafePass123!',
        ]);

        $loginRes->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => '/dashboard',
            ])
            ->assertJsonStructure(['token', 'user']);
    }
}
