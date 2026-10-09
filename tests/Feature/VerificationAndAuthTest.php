<?php

namespace Tests\Feature;

use App\Models\GraduateRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationAndAuthTest extends TestCase
{
    protected static bool $migrated = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$migrated) {
            $this->artisan('migrate:fresh');
            $this->artisan('db:seed');
            static::$migrated = true;
        }
    }

    public function test_verify_matric_unclaimed_returns_preview_and_token(): void
    {
        $response = $this->postJson('/api/verify-matric', [
            'matric_number' => 'su/20/nas/014', // Test case insensitivity
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'verified',
            ])
            ->assertJsonStructure([
                'success',
                'status',
                'verification_token',
                'graduate' => [
                    'id',
                    'matric_number',
                    'first_name',
                    'masked_name',
                    'faculty',
                    'department',
                    'graduation_year',
                ],
            ]);

        $this->assertEquals('SU/20/NAS/014', $response->json('graduate.matric_number'));
    }

    public function test_verify_matric_claimed_returns_409(): void
    {
        $response = $this->postJson('/api/verify-matric', [
            'matric_number' => 'SU/19/NAS/001',
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'status' => 'already_claimed',
            ]);
    }

    public function test_verify_matric_not_found_returns_404(): void
    {
        $response = $this->postJson('/api/verify-matric', [
            'matric_number' => 'SU/99/XXX/999',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'status' => 'not_found',
                'can_request_manual' => true,
            ]);
    }

    public function test_register_with_forged_token_is_rejected(): void
    {
        $response = $this->postJson('/api/register', [
            'matric_number' => 'SU/20/NAS/014',
            'verification_token' => 'forged-fake-token-12345',
            'name' => 'Ibrahim Babatunde Alabi',
            'email' => 'ibrahim.alabi@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'ndpa_consent' => true,
        ]);

        $response->assertStatus(403);
    }

    public function test_full_registration_and_record_claim_lifecycle(): void
    {
        // 1. Verify unclaimed record (SU/20/NAS/014)
        $verifyRes = $this->postJson('/api/verify-matric', [
            'matric_number' => 'SU/20/NAS/014',
        ]);
        $verifyRes->assertStatus(200);
        $token = $verifyRes->json('verification_token');

        // 2. Register alumnus
        $registerRes = $this->postJson('/api/register', [
            'matric_number' => 'SU/20/NAS/014',
            'verification_token' => $token,
            'name' => 'Ibrahim Babatunde Alabi',
            'email' => 'ibrahim.babatunde@example.com',
            'phone' => '+2348077778899',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'ndpa_consent' => true,
        ]);

        $registerRes->assertStatus(201)
            ->assertJson([
                'success' => true,
                'user' => [
                    'matric_number' => 'SU/20/NAS/014',
                    'email' => 'ibrahim.babatunde@example.com',
                    'role' => 'alumnus',
                ],
            ])
            ->assertJsonStructure(['token', 'redirect']);

        $newUserId = $registerRes->json('user.id');

        // 3. Confirm record in database is marked claimed
        $record = GraduateRecord::where('matric_number', 'SU/20/NAS/014')->first();
        $this->assertEquals('claimed', $record->status);
        $this->assertEquals($newUserId, $record->claimed_by_user_id);
        $this->assertNotNull($record->claimed_at);

        // 4. Confirm Profile and Privacy Settings were automatically initialized
        $user = User::with(['profile', 'privacySetting'])->find($newUserId);
        $this->assertNotNull($user->profile);
        $this->assertNotNull($user->privacySetting);
        $this->assertEquals('Summit University Offa', $user->profile->education_history[0]['school']);
        $this->assertEquals('alumni_only', $user->privacySetting->email_visibility);

        // 5. Subsequent attempt to verify same matric now returns 409
        $reVerifyRes = $this->postJson('/api/verify-matric', [
            'matric_number' => 'SU/20/NAS/014',
        ]);
        $reVerifyRes->assertStatus(409)->assertJson(['status' => 'already_claimed']);

        // 6. Login with new credentials using matriculation number
        $loginRes = $this->postJson('/api/login', [
            'login' => 'SU/20/NAS/014',
            'password' => 'SecurePass123!',
        ]);
        $loginRes->assertStatus(200)->assertJson(['success' => true]);
    }

    public function test_login_with_email_and_matric_number(): void
    {
        // 1. Login with email
        $responseEmail = $this->postJson('/api/login', [
            'login' => 'amina.bello@summituniversity.edu.ng',
            'password' => 'password',
        ]);

        $responseEmail->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['token', 'user', 'redirect']);

        // 2. Login with matric number
        $responseMatric = $this->postJson('/api/login', [
            'login' => 'su/19/nas/001',
            'password' => 'password',
        ]);

        $responseMatric->assertStatus(200)
            ->assertJson(['success' => true]);

        // 3. Login with invalid password
        $responseInvalid = $this->postJson('/api/login', [
            'login' => 'amina.bello@summituniversity.edu.ng',
            'password' => 'wrong-pass',
        ]);

        $responseInvalid->assertStatus(401);
    }

    public function test_me_and_logout_endpoints(): void
    {
        $user = User::where('email', 'amina.bello@summituniversity.edu.ng')->first();
        $token = $user->createToken('test_token')->plainTextToken;

        $meResponse = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/me');

        $meResponse->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonPath('user.email', 'amina.bello@summituniversity.edu.ng')
            ->assertJsonStructure([
                'user' => [
                    'profile',
                    'privacy_setting',
                    'graduate_record',
                ],
            ]);

        $logoutResponse = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/logout');

        $logoutResponse->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_manual_verification_submission(): void
    {
        $response = $this->postJson('/api/verify-manual', [
            'full_name' => 'Kazeem Oladimeji Sanusi',
            'matric_number' => 'SU/18/NAS/015',
            'graduation_year' => 2018,
            'department_name' => 'Computer Science',
            'email' => 'kazeem.sanusi@example.com',
            'phone' => '+2348055554433',
            'notes' => 'Pioneer set graduate, please verify against archives.',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure(['ticket_id', 'reference_number']);
    }

    public function test_academic_data_endpoint(): void
    {
        $response = $this->getJson('/api/academic-data');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'faculties' => [
                    '*' => ['id', 'name', 'code', 'departments'],
                ],
                'graduation_years',
            ]);
    }
}
