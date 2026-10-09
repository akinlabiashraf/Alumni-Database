<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\GraduateRecord;
use App\Models\PrivacySetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardAndProfileManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');

        // Ensure Amina's baseline state is intact
        $amina = User::where('email', 'amina.bello@summituniversity.edu.ng')->first();
        if ($amina) {
            $amina->update([
                'name' => 'Amina Fatima Bello',
                'password' => Hash::make('password'),
            ]);
            PrivacySetting::updateOrCreate(
                ['user_id' => $amina->id],
                [
                    'profile_visibility' => 'public',
                    'email_visibility' => 'alumni_only',
                    'phone_visibility' => 'private',
                    'work_visibility' => 'public',
                    'location_visibility' => 'alumni_only',
                    'allow_messaging' => true,
                ]
            );
        }
    }

    private function getOrCreateTestAlumnus(): User
    {
        $record = GraduateRecord::where('matric_number', 'SU/22/MSS/055')->first();

        $user = User::firstOrCreate(
            ['email' => 'tunde.adeyemi@test.summituniversity.edu.ng'],
            [
                'name' => 'Tunde Adeyemi',
                'matric_number' => 'SU/22/MSS/055',
                'phone' => '+2348022233445',
                'password' => Hash::make('password'),
                'role' => 'alumnus',
                'status' => 'active',
                'graduate_record_id' => $record?->id,
                'ndpa_consent' => true,
                'ndpa_consent_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'headline' => 'Business Analyst | Class of 2022',
                'country' => 'Nigeria',
                'skills' => ['Data Analysis', 'Excel'],
            ]
        );

        PrivacySetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'profile_visibility' => 'public',
                'email_visibility' => 'alumni_only',
                'phone_visibility' => 'private',
                'work_visibility' => 'public',
                'location_visibility' => 'alumni_only',
                'allow_messaging' => true,
            ]
        );

        return $user;
    }

    public function test_member_portal_views_render_successfully(): void
    {
        // 1. Dashboard
        $dashResponse = $this->get('/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Profile Strength');
        $dashResponse->assertSee('Classmates in Your Circle');

        // 2. Profile Settings
        $profileResponse = $this->get('/profile-settings');
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Profile');
        $profileResponse->assertSee('Career Management');
        $profileResponse->assertSee('Summit University Credentials');

        // 3. Privacy Settings
        $privacyResponse = $this->get('/privacy-settings');
        $privacyResponse->assertStatus(200);
        $privacyResponse->assertSee('NDPA 2023');
        $privacyResponse->assertSee('Granular Visibility Controls');

        // 4. Change Password
        $pwdResponse = $this->get('/change-password');
        $pwdResponse->assertStatus(200);
        $pwdResponse->assertSee('Change Account Password');
        $pwdResponse->assertSee('Current Password');
    }

    public function test_unauthenticated_requests_to_profile_apis_are_rejected(): void
    {
        $this->getJson('/api/dashboard/stats')->assertStatus(401);
        $this->putJson('/api/profile', ['headline' => 'Test'])->assertStatus(401);
        $this->postJson('/api/profile/avatar', [])->assertStatus(401);
    }

    public function test_get_dashboard_stats_api(): void
    {
        $user = $this->getOrCreateTestAlumnus();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/dashboard/stats');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'stats' => [
                    'completion_percentage',
                    'classmates_in_set',
                    'registered_classmates',
                    'department_alumni',
                    'total_alumni',
                    'suggested_classmates',
                    'announcements',
                ],
                'user' => [
                    'id',
                    'name',
                    'matric_number',
                ],
            ]);

        $this->assertGreaterThanOrEqual(20, $response->json('stats.completion_percentage'));
    }

    public function test_update_profile_api(): void
    {
        $user = $this->getOrCreateTestAlumnus();
        Sanctum::actingAs($user);

        $payload = [
            'name' => 'Tunde Babatunde Adeyemi',
            'phone' => '+234 803 999 8877',
            'headline' => 'Senior Financial Modeler at KPMG',
            'bio' => 'Passionate about financial technologies and fintech expansion.',
            'current_job_title' => 'Senior Financial Modeler',
            'current_company' => 'KPMG Nigeria',
            'industry' => 'Financial Services & Banking',
            'city' => 'Victoria Island',
            'state' => 'Lagos',
            'country' => 'Nigeria',
            'skills' => ['Financial Modeling', 'Valuation', 'Leadership'],
            'career_history' => [
                [
                    'role' => 'Senior Financial Modeler',
                    'company' => 'KPMG Nigeria',
                    'start_year' => '2023',
                    'end_year' => 'Present',
                    'description' => 'Corporate finance valuations.',
                ],
            ],
            'education_history' => [
                [
                    'school' => 'Summit University Offa',
                    'degree' => 'B.Sc. Accounting',
                    'year' => '2022',
                    'description' => 'Second Class Upper',
                ],
            ],
            'linkedin_url' => 'https://linkedin.com/in/tundeadeyemi',
        ];

        $response = $this->putJson('/api/profile', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profile updated successfully.',
            ]);

        // Verify database updates
        $user->refresh();
        $this->assertEquals('Tunde Babatunde Adeyemi', $user->name);
        $this->assertEquals('+234 803 999 8877', $user->phone);

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertNotNull($profile);
        $this->assertEquals('Senior Financial Modeler at KPMG', $profile->headline);
        $this->assertEquals('KPMG Nigeria', $profile->current_company);
        $this->assertEquals('Victoria Island', $profile->city);
        $this->assertContains('Financial Modeling', $profile->skills);

        // Audit log assertion
        $log = AuditLog::where('user_id', $user->id)
            ->where('action', 'profile_updated')
            ->first();
        $this->assertNotNull($log);
    }

    public function test_upload_avatar_api(): void
    {
        Storage::fake('public');

        $user = $this->getOrCreateTestAlumnus();
        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image('profile_headshot.jpg', 300, 300);

        $response = $this->postJson('/api/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Profile photo updated successfully!',
            ]);

        $avatarUrl = $response->json('avatar_url');
        $this->assertNotNull($avatarUrl);

        $profile = Profile::where('user_id', $user->id)->first();
        $this->assertEquals($avatarUrl, $profile->avatar);

        // Audit log assertion
        $log = AuditLog::where('user_id', $user->id)
            ->where('action', 'avatar_updated')
            ->first();
        $this->assertNotNull($log);
    }

    public function test_update_privacy_settings_api(): void
    {
        $user = $this->getOrCreateTestAlumnus();
        Sanctum::actingAs($user);

        $payload = [
            'profile_visibility' => 'alumni_only',
            'email_visibility' => 'private',
            'phone_visibility' => 'private',
            'work_visibility' => 'public',
            'location_visibility' => 'alumni_only',
            'allow_messaging' => true,
        ];

        $response = $this->postJson('/api/privacy', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Privacy settings updated successfully.',
            ]);

        $privacy = PrivacySetting::where('user_id', $user->id)->first();
        $this->assertNotNull($privacy);
        $this->assertEquals('alumni_only', $privacy->profile_visibility);
        $this->assertEquals('private', $privacy->email_visibility);
    }

    public function test_change_password_api_validation_and_success(): void
    {
        $user = $this->getOrCreateTestAlumnus();
        $user->update(['password' => Hash::make('password')]);
        Sanctum::actingAs($user);

        // 1. Wrong current password
        $failResponse = $this->postJson('/api/change-password', [
            'current_password' => 'wrong_password_123',
            'new_password' => 'NewSecret2026',
            'new_password_confirmation' => 'NewSecret2026',
        ]);
        $failResponse->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The current password provided is incorrect.',
            ]);

        // 2. Passwords do not match
        $mismatchResponse = $this->postJson('/api/change-password', [
            'current_password' => 'password',
            'new_password' => 'NewSecret2026',
            'new_password_confirmation' => 'DifferentSecret2026',
        ]);
        $mismatchResponse->assertStatus(422);

        // 3. Valid password change
        $successResponse = $this->postJson('/api/change-password', [
            'current_password' => 'password',
            'new_password' => 'NewSecret2026',
            'new_password_confirmation' => 'NewSecret2026',
        ]);
        $successResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password updated successfully.',
            ]);

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecret2026', $user->password));

        // Restore password
        $user->update(['password' => Hash::make('password')]);
    }
}
