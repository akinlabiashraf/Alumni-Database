<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\GraduateRecord;
use Illuminate\Database\Seeder;

class GraduateRecordSeeder extends Seeder
{
    public function run(): void
    {
        $fnas = Faculty::where('code', 'FNAS')->first();
        $fmss = Faculty::where('code', 'FMSS')->first();
        $fhum = Faculty::where('code', 'FHUM')->first();
        $fees = Faculty::where('code', 'FEES')->first();

        $csc = Department::where('code', 'CSC')->first();
        $bch = Department::where('code', 'BCH')->first();
        $acc = Department::where('code', 'ACC')->first();
        $eco = Department::where('code', 'ECO')->first();
        $mac = Department::where('code', 'MAC')->first();
        $els = Department::where('code', 'ELS')->first();
        $eee = Department::where('code', 'EEE')->first();

        $graduates = [
            // 2019 Pioneers
            [
                'matric_number' => 'SU/19/NAS/001',
                'first_name' => 'Amina',
                'middle_name' => 'Fatima',
                'last_name' => 'Bello',
                'faculty_id' => $fnas?->id,
                'department_id' => $csc?->id,
                'degree' => 'B.Sc. Computer Science',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2019,
            ],
            [
                'matric_number' => 'SU/19/MSS/005',
                'first_name' => 'Olawale',
                'middle_name' => 'Kareem',
                'last_name' => 'Balogun',
                'faculty_id' => $fmss?->id,
                'department_id' => $acc?->id,
                'degree' => 'B.Sc. Accounting',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2019,
            ],
            // 2020 Set
            [
                'matric_number' => 'SU/20/NAS/014',
                'first_name' => 'Ibrahim',
                'middle_name' => 'Babatunde',
                'last_name' => 'Alabi',
                'faculty_id' => $fnas?->id,
                'department_id' => $csc?->id,
                'degree' => 'B.Sc. Computer Science',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2020,
            ],
            [
                'matric_number' => 'SU/20/MSS/022',
                'first_name' => 'Khadijah',
                'middle_name' => 'Omowunmi',
                'last_name' => 'Yusuf',
                'faculty_id' => $fmss?->id,
                'department_id' => $eco?->id,
                'degree' => 'B.Sc. Economics',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2020,
            ],
            // 2021 Set
            [
                'matric_number' => 'SU/21/MSS/032',
                'first_name' => 'Zainab',
                'middle_name' => 'Ololade',
                'last_name' => 'Adeleke',
                'faculty_id' => $fmss?->id,
                'department_id' => $acc?->id,
                'degree' => 'B.Sc. Accounting',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2021,
            ],
            [
                'matric_number' => 'SU/21/NAS/041',
                'first_name' => 'Mustapha',
                'middle_name' => 'Adekunle',
                'last_name' => 'Oyewole',
                'faculty_id' => $fnas?->id,
                'department_id' => $bch?->id,
                'degree' => 'B.Sc. Biochemistry',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2021,
            ],
            // 2022 Set
            [
                'matric_number' => 'SU/22/MSS/055',
                'first_name' => 'Abdulrahman',
                'middle_name' => 'Kehinde',
                'last_name' => 'Salami',
                'faculty_id' => $fmss?->id,
                'department_id' => $eco?->id,
                'degree' => 'B.Sc. Economics',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2022,
            ],
            [
                'matric_number' => 'SU/22/HUM/019',
                'first_name' => 'Rashidat',
                'middle_name' => 'Abiola',
                'last_name' => 'Ibrahim',
                'faculty_id' => $fhum?->id,
                'department_id' => $els?->id,
                'degree' => 'B.A. English and Literary Studies',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2022,
            ],
            // 2023 Set
            [
                'matric_number' => 'SU/23/NAS/089',
                'first_name' => 'Maryam',
                'middle_name' => 'Chukwuemeka',
                'last_name' => 'Okonkwo',
                'faculty_id' => $fnas?->id,
                'department_id' => $bch?->id,
                'degree' => 'B.Sc. Biochemistry',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2023,
            ],
            [
                'matric_number' => 'SU/23/MSS/064',
                'first_name' => 'Tunde',
                'middle_name' => 'Lateef',
                'last_name' => 'Gbadamosi',
                'faculty_id' => $fmss?->id,
                'department_id' => $mac?->id,
                'degree' => 'B.Sc. Mass Communication',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2023,
            ],
            // 2024 Set
            [
                'matric_number' => 'SU/24/ENG/012',
                'first_name' => 'Yusuf',
                'middle_name' => 'Ademola',
                'last_name' => 'Oladipo',
                'faculty_id' => $fees?->id,
                'department_id' => $eee?->id,
                'degree' => 'B.Eng. Electrical & Electronics Engineering',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2024,
            ],
            [
                'matric_number' => 'SU/24/NAS/105',
                'first_name' => 'Hauwa',
                'middle_name' => 'Aliyu',
                'last_name' => 'Garba',
                'faculty_id' => $fnas?->id,
                'department_id' => $csc?->id,
                'degree' => 'B.Sc. Computer Science',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2024,
            ],
            // 2025 Set
            [
                'matric_number' => 'SU/25/HUM/008',
                'first_name' => 'Halimat',
                'middle_name' => 'Titilayo',
                'last_name' => 'Quadri',
                'faculty_id' => $fhum?->id,
                'department_id' => $els?->id,
                'degree' => 'B.A. English and Literary Studies',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2025,
            ],
            [
                'matric_number' => 'SU/25/NAS/120',
                'first_name' => 'Farouk',
                'middle_name' => 'Sulaiman',
                'last_name' => 'Ahmad',
                'faculty_id' => $fnas?->id,
                'department_id' => $csc?->id,
                'degree' => 'B.Sc. Computer Science',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2025,
            ],
            // 2026 Set
            [
                'matric_number' => '22/CONAS/CSC/025',
                'first_name' => 'Habeebullah',
                'middle_name' => 'Ashrof',
                'last_name' => 'Akintola',
                'faculty_id' => $fnas?->id,
                'department_id' => $csc?->id,
                'degree' => 'B.Sc. Computer Science',
                'class_of_degree' => 'First Class Honours',
                'graduation_year' => 2026,
            ],
            [
                'matric_number' => '22/CONAS/CSC/026',
                'first_name' => 'Adebayo',
                'middle_name' => 'Shamsudeen',
                'last_name' => 'Anuoluwapo',
                'faculty_id' => $fnas?->id,
                'department_id' => $csc?->id,
                'degree' => 'B.Sc. Computer Science',
                'class_of_degree' => 'Second Class Honours (Upper Division)',
                'graduation_year' => 2026,
            ],
        ];

        foreach ($graduates as $grad) {
            $middle = !empty($grad['middle_name']) ? ' ' . $grad['middle_name'] : '';
            $fullName = "{$grad['first_name']}{$middle} {$grad['last_name']}";

            GraduateRecord::firstOrCreate(
                ['matric_number' => $grad['matric_number']],
                [
                    'first_name' => $grad['first_name'],
                    'middle_name' => $grad['middle_name'],
                    'last_name' => $grad['last_name'],
                    'full_name' => $fullName,
                    'faculty_id' => $grad['faculty_id'],
                    'department_id' => $grad['department_id'],
                    'degree' => $grad['degree'],
                    'class_of_degree' => $grad['class_of_degree'],
                    'graduation_year' => $grad['graduation_year'],
                    'status' => 'unclaimed',
                ]
            );
        }
    }
}
