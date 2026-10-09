<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class DirectoryAndProfileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_directory_page_renders_successfully(): void
    {
        $response = $this->get('/directory');
        $response->assertStatus(200);
        $response->assertSee('Alumni Directory');
        $response->assertSee('Graduating Set');
        $response->assertSee('All Faculties');
    }

    public function test_profile_page_renders_successfully(): void
    {
        $alumna = User::where('email', 'amina.bello@summituniversity.edu.ng')->first();
        $this->assertNotNull($alumna);

        $response = $this->get("/alumni/{$alumna->id}");
        $response->assertStatus(200);
        $response->assertSee('Verified Member Profile');
        $response->assertSee('Official Academic Credentials');
    }

    public function test_alumni_directory_api_returns_paginated_verified_members(): void
    {
        $response = $this->getJson('/api/alumni');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'current_page',
                'total',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'degree',
                        'graduation_year',
                        'faculty',
                        'department',
                        'is_verified',
                    ],
                ],
            ]);

        $this->assertGreaterThan(0, $response->json('total'));
    }

    public function test_alumni_directory_api_filters_by_graduating_year(): void
    {
        $response = $this->getJson('/api/alumni?year=2019');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);

        foreach ($data as $item) {
            $this->assertEquals(2019, $item['graduation_year']);
        }
    }

    public function test_alumni_directory_api_search_by_keyword(): void
    {
        $response = $this->getJson('/api/alumni?search=Amina');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('Amina Fatima Bello', $data[0]['name']);
    }

    public function test_alumni_detail_api_and_ndpa_privacy_masking(): void
    {
        $alumna = User::where('email', 'amina.bello@summituniversity.edu.ng')->first();
        $this->assertNotNull($alumna);

        // 1. Unauthenticated Guest Request:
        // Phone visibility is 'private', so phone MUST be null/hidden
        // Email visibility is 'alumni_only', so email MUST be null/hidden for guest
        $guestResponse = $this->getJson("/api/alumni/{$alumna->id}");
        $guestResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'alumnus' => [
                    'name' => 'Amina Fatima Bello',
                    'degree' => 'B.Sc. Computer Science',
                    'phone' => null,
                    'phone_hidden' => true,
                    'email' => null,
                    'email_hidden' => true,
                ],
            ]);

        // 2. Authenticated Alumnus Request:
        // Email visibility is 'alumni_only', so authenticated alumnus CAN view email!
        $token = $alumna->createToken('test_token')->plainTextToken;
        $authResponse = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson("/api/alumni/{$alumna->id}");

        $authResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'alumnus' => [
                    'email' => 'amina.bello@summituniversity.edu.ng',
                    'email_hidden' => false,
                    'phone' => null, // Phone was set to private, so it remains hidden even from other alumni
                ],
            ]);
    }
}
