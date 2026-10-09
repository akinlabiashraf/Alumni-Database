<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\GraduateRecord;
use App\Models\PrivacySetting;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@summituniversity.edu.ng'],
            [
                'name' => 'Summit Alumni Directorate Admin',
                'matric_number' => null,
                'phone' => '+2348012345678',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'ndpa_consent' => true,
                'ndpa_consent_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        Profile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'headline' => 'Directorate of Alumni & Advancement Relations, Summit University',
                'bio' => 'Official administrator portal for Summit University Offa alumni management, records verification, and communications.',
                'city' => 'Offa',
                'state' => 'Kwara',
                'country' => 'Nigeria',
            ]
        );

        PrivacySetting::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'profile_visibility' => 'public',
                'email_visibility' => 'public',
                'phone_visibility' => 'alumni_only',
                'work_visibility' => 'public',
                'location_visibility' => 'public',
            ]
        );

        // 2. Moderator
        User::firstOrCreate(
            ['email' => 'moderator@summituniversity.edu.ng'],
            [
                'name' => 'Records Verification Moderator',
                'phone' => '+2348098765432',
                'password' => Hash::make('password'),
                'role' => 'moderator',
                'status' => 'active',
                'ndpa_consent' => true,
                'ndpa_consent_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        // 3. Pioneer Verified Alumna (Claims SU/19/NAS/001)
        $pioneerRecord = GraduateRecord::where('matric_number', 'SU/19/NAS/001')->first();

        $alumna = User::firstOrCreate(
            ['email' => 'amina.bello@summituniversity.edu.ng'],
            [
                'name' => 'Amina Fatima Bello',
                'matric_number' => 'SU/19/NAS/001',
                'phone' => '+2348031112233',
                'password' => Hash::make('password'),
                'role' => 'alumnus',
                'status' => 'active',
                'graduate_record_id' => $pioneerRecord?->id,
                'ndpa_consent' => true,
                'ndpa_consent_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        if ($pioneerRecord) {
            $pioneerRecord->update([
                'status' => 'claimed',
                'claimed_by_user_id' => $alumna->id,
                'claimed_at' => now(),
            ]);
        }

        Profile::firstOrCreate(
            ['user_id' => $alumna->id],
            [
                'headline' => 'Lead Software Engineer at Paystack | Pioneer Class of 2019',
                'bio' => 'Passionate software architect and proud pioneer alumna of Summit University Offa. Building scalable payment infrastructure across emerging markets.',
                'current_job_title' => 'Lead Software Engineer',
                'current_company' => 'Paystack',
                'industry' => 'Technology / Software',
                'work_location' => 'Lagos, Nigeria',
                'city' => 'Ikeja',
                'state' => 'Lagos',
                'country' => 'Nigeria',
                'linkedin_url' => 'https://linkedin.com/in/amina-bello',
                'github_url' => 'https://github.com/aminabello',
                'skills' => ['PHP', 'Laravel', 'TypeScript', 'Node.js', 'System Architecture', 'AWS'],
                'education_history' => [
                    [
                        'school' => 'Summit University Offa',
                        'degree' => 'B.Sc. Computer Science (First Class Honours)',
                        'year' => '2015 - 2019',
                    ],
                ],
                'career_history' => [
                    [
                        'role' => 'Lead Software Engineer',
                        'company' => 'Paystack',
                        'period' => '2022 - Present',
                        'description' => 'Leading core payments and merchant checkout systems engineering.',
                    ],
                    [
                        'role' => 'Software Engineer',
                        'company' => 'Andela',
                        'period' => '2019 - 2022',
                        'description' => 'Developed high-throughput microservices and API gateways.',
                    ],
                ],
            ]
        );

        PrivacySetting::firstOrCreate(
            ['user_id' => $alumna->id],
            [
                'profile_visibility' => 'public',
                'email_visibility' => 'alumni_only',
                'phone_visibility' => 'private',
                'work_visibility' => 'public',
                'location_visibility' => 'alumni_only',
                'allow_messaging' => true,
            ]
        );

        AuditLog::create([
            'user_id' => $alumna->id,
            'action' => 'matric_verified',
            'description' => 'Alumnus claimed record SU/19/NAS/001 successfully.',
            'ip_address' => '127.0.0.1',
            'created_at' => now(),
        ]);

        // 4. HABEEBULLAH Ashrof Akintola (22/CONAS/CSC/025)
        $habeebullahRecord = GraduateRecord::where('matric_number', '22/CONAS/CSC/025')->first();

        $userHabeebullah = User::firstOrCreate(
            ['email' => 'ashrafhabeeb204@gmail.com'],
            [
                'name' => 'HABEEBULLAH Ashrof Akintola',
                'matric_number' => '22/CONAS/CSC/025',
                'phone' => '08027352041',
                'password' => Hash::make('password'),
                'role' => 'alumnus',
                'status' => 'active',
                'graduate_record_id' => $habeebullahRecord?->id,
                'ndpa_consent' => true,
                'ndpa_consent_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        if ($habeebullahRecord) {
            $habeebullahRecord->update([
                'status' => 'claimed',
                'claimed_by_user_id' => $userHabeebullah->id,
                'claimed_at' => now(),
            ]);
        }

        Profile::firstOrCreate(
            ['user_id' => $userHabeebullah->id],
            [
                'headline' => 'Class of 2026 | Computer Science, Summit University Offa',
                'bio' => 'Computer Science graduate, Class of 2026. Passionate software engineer and tech innovator.',
                'city' => 'Offa',
                'state' => 'Kwara',
                'country' => 'Nigeria',
                'skills' => ['Computer Science', 'Software Engineering', 'Web Development'],
            ]
        );

        PrivacySetting::firstOrCreate(
            ['user_id' => $userHabeebullah->id],
            [
                'profile_visibility' => 'public',
                'email_visibility' => 'alumni_only',
                'phone_visibility' => 'private',
                'work_visibility' => 'public',
                'location_visibility' => 'alumni_only',
                'allow_messaging' => true,
            ]
        );

        // 5. ADEBAYO Shamsudeen Anuoluwapo (22/CONAS/CSC/026)
        $adebayoRecord = GraduateRecord::where('matric_number', '22/CONAS/CSC/026')->first();

        $userAdebayo = User::firstOrCreate(
            ['email' => 'adebayoshamo.st@summituniversity.edu.ng'],
            [
                'name' => 'ADEBAYO Shamsudeen Anuoluwapo',
                'matric_number' => '22/CONAS/CSC/026',
                'phone' => '08104795758',
                'password' => Hash::make('password'),
                'role' => 'alumnus',
                'status' => 'active',
                'graduate_record_id' => $adebayoRecord?->id,
                'ndpa_consent' => true,
                'ndpa_consent_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        if ($adebayoRecord) {
            $adebayoRecord->update([
                'status' => 'claimed',
                'claimed_by_user_id' => $userAdebayo->id,
                'claimed_at' => now(),
            ]);
        }

        Profile::firstOrCreate(
            ['user_id' => $userAdebayo->id],
            [
                'headline' => 'Class of 2026 | Computer Science, Summit University Offa',
                'bio' => 'Computer Science graduate, Class of 2026 at Summit University Offa.',
                'city' => 'Offa',
                'state' => 'Kwara',
                'country' => 'Nigeria',
                'skills' => ['Computer Science', 'Information Technology', 'Software Development'],
            ]
        );

        PrivacySetting::firstOrCreate(
            ['user_id' => $userAdebayo->id],
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
