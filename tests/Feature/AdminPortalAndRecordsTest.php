<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\GraduateRecord;
use App\Models\ManualVerification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminPortalAndRecordsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    private function getAdminUser(): User
    {
        return User::where('email', 'admin@summituniversity.edu.ng')->first()
            ?? User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    private function getModeratorUser(): User
    {
        return User::where('email', 'moderator@summituniversity.edu.ng')->first()
            ?? User::factory()->create(['role' => 'moderator', 'status' => 'active']);
    }

    private function getAlumnusUser(): User
    {
        return User::where('email', 'amina.bello@summituniversity.edu.ng')->first()
            ?? User::factory()->create(['role' => 'alumnus', 'status' => 'active']);
    }

    public function test_unauthenticated_requests_to_admin_apis_are_rejected(): void
    {
        $response = $this->getJson('/api/admin/dashboard');
        $response->assertStatus(401);

        $response = $this->getJson('/api/admin/records');
        $response->assertStatus(401);
    }

    public function test_alumnus_request_to_admin_apis_is_forbidden(): void
    {
        $alumna = $this->getAlumnusUser();
        Sanctum::actingAs($alumna);

        $response = $this->getJson('/api/admin/dashboard');
        $response->assertStatus(403);
        $response->assertJson(['success' => false]);
    }

    public function test_admin_and_moderator_access_dashboard_metrics(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/dashboard');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'metrics' => [
                'total_records',
                'claimed_records',
                'unclaimed_records',
                'claim_rate',
                'total_users',
                'alumni_users',
                'pending_verifications',
            ],
            'set_distribution',
            'faculty_distribution',
        ]);
        $response->assertJson(['success' => true]);
    }

    public function test_admin_views_master_records_with_search_and_filter(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        // Search by pioneer matric
        $response = $this->getJson('/api/admin/records?search=SU/19/NAS/001');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertNotEmpty($response->json('records'));
        $this->assertEquals('SU/19/NAS/001', $response->json('records.0.matric_number'));

        // Filter by unclaimed
        $response = $this->getJson('/api/admin/records?status=unclaimed');
        $response->assertStatus(200);
        foreach ($response->json('records') as $rec) {
            $this->assertNull($rec['claimed_by_user_id']);
        }
    }

    public function test_admin_create_update_delete_single_record(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        $fnas = Faculty::where('code', 'FNAS')->first();
        $csc = Department::where('code', 'CSC')->first();

        $matric = 'SU/25/TEST/' . rand(100, 999);

        // 1. Create Record
        $createRes = $this->postJson('/api/admin/records', [
            'matric_number' => $matric,
            'first_name' => 'Fawaz',
            'middle_name' => 'Ade',
            'last_name' => 'Kareem',
            'faculty_id' => $fnas->id,
            'department_id' => $csc->id,
            'degree' => 'B.Sc. Computer Science',
            'class_of_degree' => 'First Class Honours',
            'graduation_year' => 2025,
        ]);

        $createRes->assertStatus(201);
        $createRes->assertJson(['success' => true]);
        $recordId = $createRes->json('record.id');

        // 2. Update Record
        $updateRes = $this->putJson("/api/admin/records/{$recordId}", [
            'matric_number' => $matric,
            'first_name' => 'Fawaz Updated',
            'last_name' => 'Kareem',
            'faculty_id' => $fnas->id,
            'department_id' => $csc->id,
            'degree' => 'B.Sc. Computer Science',
            'class_of_degree' => 'First Class Honours',
            'graduation_year' => 2025,
        ]);

        $updateRes->assertStatus(200);
        $this->assertEquals('Fawaz Updated Kareem', $updateRes->json('record.full_name'));

        // 3. Delete Record
        $deleteRes = $this->deleteJson("/api/admin/records/{$recordId}");
        $deleteRes->assertStatus(200);
        $this->assertNull(GraduateRecord::find($recordId));
    }

    public function test_admin_cannot_delete_claimed_record(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        $claimed = GraduateRecord::whereNotNull('claimed_by_user_id')->first();
        $this->assertNotNull($claimed);

        $response = $this->deleteJson("/api/admin/records/{$claimed->id}");
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_admin_download_template_csv(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        $response = $this->get('/api/admin/template-csv');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="summit_graduate_records_template.csv"');
    }

    public function test_admin_bulk_csv_import_with_duplicate_detection(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        $uniqueMatric1 = 'SU/25/CSV/' . rand(1000, 4999);
        $uniqueMatric2 = 'SU/25/CSV/' . rand(5000, 9999);

        // Line 1: Header
        // Line 2: Existing pioneer record (duplicate)
        // Line 3: Unique record 1
        // Line 4: Unique record 2
        $csvContent = implode("\n", [
            'matric_number,first_name,middle_name,last_name,faculty_code,department_code,degree,class_of_degree,graduation_year',
            'SU/19/NAS/001,Amina,Fatima,Bello,FNAS,CSC,B.Sc. Computer Science,First Class Honours,2019',
            "{$uniqueMatric1},Taofeek,Ayomide,Gbadamosi,FNAS,CSC,B.Sc. Computer Science,Second Class Honours (Upper Division),2025",
            "{$uniqueMatric2},Aishat,Morenikeji,Sanusi,FMSS,ACC,B.Sc. Accounting,First Class Honours,2025",
        ]);

        $file = UploadedFile::fake()->createWithContent('graduates_test.csv', $csvContent);

        $response = $this->postJson('/api/admin/import', [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'summary' => [
                'imported_count' => 2,
                'duplicate_count' => 1,
                'error_count' => 0,
            ],
        ]);

        $this->assertDatabaseHas('graduate_records', ['matric_number' => $uniqueMatric1]);
        $this->assertDatabaseHas('graduate_records', ['matric_number' => $uniqueMatric2]);
    }

    public function test_admin_moderation_queue_approve_and_reject(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        // Create test user and verification
        $applicant = User::factory()->create([
            'role' => 'student',
            'status' => 'pending_verification',
        ]);

        $matricToReject = 'SU/25/REJ/' . rand(100, 999);
        $verif1 = ManualVerification::create([
            'user_id' => $applicant->id,
            'full_name' => 'Test Rejected Applicant',
            'matric_number' => $matricToReject,
            'graduation_year' => 2025,
            'department_name' => 'Computer Science',
            'email' => $applicant->email,
            'status' => 'pending',
        ]);

        // 1. Reject verification
        $rejectRes = $this->postJson("/api/admin/verifications/{$verif1->id}/reject", [
            'rejection_reason' => 'Matriculation number not found in university registrar archives.',
        ]);
        $rejectRes->assertStatus(200);
        $this->assertEquals('rejected', $verif1->fresh()->status);

        // 2. Approve verification
        $matricToApprove = 'SU/25/APP/' . rand(100, 999);
        $verif2 = ManualVerification::create([
            'user_id' => $applicant->id,
            'full_name' => 'Test Approved Alumnus',
            'matric_number' => $matricToApprove,
            'graduation_year' => 2024,
            'department_name' => 'Computer Science',
            'email' => $applicant->email,
            'status' => 'pending',
        ]);

        $approveRes = $this->postJson("/api/admin/verifications/{$verif2->id}/approve");
        $approveRes->assertStatus(200);
        $this->assertEquals('approved', $verif2->fresh()->status);
        $this->assertEquals('alumnus', $applicant->fresh()->role);
        $this->assertEquals('active', $applicant->fresh()->status);
        $this->assertEquals($matricToApprove, $applicant->fresh()->matric_number);
    }

    public function test_admin_user_role_modification_and_status(): void
    {
        $admin = $this->getAdminUser();
        Sanctum::actingAs($admin);

        $targetUser = User::factory()->create([
            'role' => 'alumnus',
            'status' => 'active',
        ]);

        // 1. Promote to moderator
        $roleRes = $this->putJson("/api/admin/users/{$targetUser->id}/role", [
            'role' => 'moderator',
        ]);
        $roleRes->assertStatus(200);
        $this->assertEquals('moderator', $targetUser->fresh()->role);

        // 2. Suspend user
        $statusRes = $this->putJson("/api/admin/users/{$targetUser->id}/status", [
            'status' => 'suspended',
        ]);
        $statusRes->assertStatus(200);
        $this->assertEquals('suspended', $targetUser->fresh()->status);
    }

    public function test_moderator_cannot_change_roles(): void
    {
        $moderator = $this->getModeratorUser();
        Sanctum::actingAs($moderator);

        $targetUser = User::factory()->create(['role' => 'alumnus']);

        $response = $this->putJson("/api/admin/users/{$targetUser->id}/role", [
            'role' => 'admin',
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_blade_views_render_successfully(): void
    {
        $routes = [
            '/admin/dashboard',
            '/admin/records',
            '/admin/verifications',
            '/admin/users',
            '/admin/audit-logs',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
