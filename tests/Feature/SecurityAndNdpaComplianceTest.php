<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\GraduateRecord;
use App\Models\PrivacySetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SecurityAndNdpaComplianceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_ndpa_data_portability_export_returns_complete_user_archive(): void
    {
        $alumna = User::where('email', 'amina.bello@summituniversity.edu.ng')->first();
        $this->assertNotNull($alumna);

        Sanctum::actingAs($alumna);

        $response = $this->getJson('/api/me/export-data');
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'compliance' => 'Nigeria Data Protection Act (NDPA) 2023 — Right to Data Portability',
                'account' => [
                    'email' => 'amina.bello@summituniversity.edu.ng',
                    'matric_number' => 'SU/19/NAS/001',
                ],
            ],
        ]);

        $this->assertArrayHasKey('academic_record', $response->json('data'));
        $this->assertArrayHasKey('public_profile', $response->json('data'));
        $this->assertArrayHasKey('privacy_settings', $response->json('data'));
        $this->assertArrayHasKey('activity_audit_logs', $response->json('data'));

        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $alumna->id,
            'action' => 'data_exported',
        ]);
    }

    public function test_ndpa_account_deletion_erases_user_and_restores_unclaimed_graduate_record(): void
    {
        $fac = \App\Models\Faculty::first();
        $dept = \App\Models\Department::first();

        // Create dedicated temporary graduate and user
        $record = GraduateRecord::create([
            'matric_number' => 'SU/25/DEL/' . rand(100, 999),
            'first_name' => 'Temporary',
            'last_name' => 'Graduate',
            'full_name' => 'Temporary Graduate',
            'faculty_id' => $fac?->id,
            'department_id' => $dept?->id,
            'degree' => 'B.Sc. Computer Science',
            'graduation_year' => 2025,
            'status' => 'claimed',
        ]);

        $user = User::create([
            'name' => 'Temporary Graduate',
            'email' => 'temp.delete.' . rand(100, 999) . '@test.summituniversity.edu.ng',
            'password' => Hash::make('secret123'),
            'matric_number' => $record->matric_number,
            'role' => 'alumnus',
            'status' => 'active',
            'graduate_record_id' => $record->id,
            'ndpa_consent' => true,
        ]);

        $record->update(['claimed_by_user_id' => $user->id]);

        Profile::create(['user_id' => $user->id, 'headline' => 'Test Alumnus']);
        PrivacySetting::create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        // 1. Wrong password check
        $failRes = $this->postJson('/api/me/delete-account', [
            'password' => 'wrong_password',
        ]);
        $failRes->assertStatus(422);

        // 2. Correct password deletion
        $successRes = $this->postJson('/api/me/delete-account', [
            'password' => 'secret123',
        ]);
        $successRes->assertStatus(200);
        $successRes->assertJson(['success' => true]);

        // Verify user, profile, and privacy setting are removed
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('profiles', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('privacy_settings', ['user_id' => $user->id]);

        // Verify graduate record was restored to unclaimed in the vault
        $freshRecord = $record->fresh();
        $this->assertEquals('unclaimed', $freshRecord->status);
        $this->assertNull($freshRecord->claimed_by_user_id);

        // Verify erasure audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'account_erased_ndpa',
        ]);
    }

    public function test_sole_admin_cannot_delete_own_account(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/me/delete-account', [
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_database_backup_command_creates_valid_json_snapshot(): void
    {
        $exitCode = Artisan::call('summit:backup-database');
        $this->assertEquals(0, $exitCode);

        $backupDir = storage_path('app/backups');
        $this->assertTrue(File::exists($backupDir));

        $files = File::files($backupDir);
        $this->assertNotEmpty($files);

        $latestBackup = end($files);
        $content = json_decode(File::get($latestBackup->getRealPath()), true);

        $this->assertIsArray($content);
        $this->assertArrayHasKey('metadata', $content);
        $this->assertArrayHasKey('tables', $content);
        $this->assertArrayHasKey('graduate_records', $content['tables']);
        $this->assertArrayHasKey('users', $content['tables']);
    }

    public function test_pwa_manifest_and_service_worker_files_are_accessible(): void
    {
        // 1. Manifest
        $manifestPath = public_path('manifest.json');
        $this->assertTrue(File::exists($manifestPath));
        $manifestContent = json_decode(File::get($manifestPath), true);
        $this->assertEquals('Summit University Alumni Network', $manifestContent['name']);
        $this->assertEquals('#006837', $manifestContent['theme_color']);

        // 2. Service Worker
        $swPath = public_path('sw.js');
        $this->assertTrue(File::exists($swPath));
        $swContent = File::get($swPath);
        $this->assertStringContainsString('summit-alumni-cache', $swContent);
    }
}
