<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultyAndDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            [
                'name' => 'Faculty of Natural and Applied Sciences',
                'code' => 'FNAS',
                'description' => 'Dedicated to scientific excellence, computational intelligence, and technological innovation.',
                'departments' => [
                    ['name' => 'Computer Science', 'code' => 'CSC', 'degree_title' => 'B.Sc. Computer Science'],
                    ['name' => 'Biochemistry', 'code' => 'BCH', 'degree_title' => 'B.Sc. Biochemistry'],
                    ['name' => 'Microbiology', 'code' => 'MCB', 'degree_title' => 'B.Sc. Microbiology'],
                    ['name' => 'Physics with Electronics', 'code' => 'PHE', 'degree_title' => 'B.Sc. Physics with Electronics'],
                    ['name' => 'Mathematics', 'code' => 'MTH', 'degree_title' => 'B.Sc. Mathematics'],
                ],
            ],
            [
                'name' => 'Faculty of Management and Social Sciences',
                'code' => 'FMSS',
                'description' => 'Nurturing ethical leadership, financial acumen, and global economic thinkers.',
                'departments' => [
                    ['name' => 'Accounting', 'code' => 'ACC', 'degree_title' => 'B.Sc. Accounting'],
                    ['name' => 'Business Administration', 'code' => 'BUS', 'degree_title' => 'B.Sc. Business Administration'],
                    ['name' => 'Economics', 'code' => 'ECO', 'degree_title' => 'B.Sc. Economics'],
                    ['name' => 'Mass Communication', 'code' => 'MAC', 'degree_title' => 'B.Sc. Mass Communication'],
                    ['name' => 'Political Science', 'code' => 'POL', 'degree_title' => 'B.Sc. Political Science'],
                ],
            ],
            [
                'name' => 'Faculty of Humanities',
                'code' => 'FHUM',
                'description' => 'Fostering cultural literacy, critical inquiry, communication, and moral heritage.',
                'departments' => [
                    ['name' => 'English and Literary Studies', 'code' => 'ELS', 'degree_title' => 'B.A. English and Literary Studies'],
                    ['name' => 'History and Diplomatic Studies', 'code' => 'HDS', 'degree_title' => 'B.A. History and Diplomatic Studies'],
                    ['name' => 'Arabic and Islamic Studies', 'code' => 'AIS', 'degree_title' => 'B.A. Arabic and Islamic Studies'],
                ],
            ],
            [
                'name' => 'Faculty of Engineering and Environmental Sciences',
                'code' => 'FEES',
                'description' => 'Pioneering sustainable technology, automated engineering, and smart infrastructure.',
                'departments' => [
                    ['name' => 'Electrical & Electronics Engineering', 'code' => 'EEE', 'degree_title' => 'B.Eng. Electrical & Electronics Engineering'],
                    ['name' => 'Mechanical Engineering', 'code' => 'MEE', 'degree_title' => 'B.Eng. Mechanical Engineering'],
                    ['name' => 'Mechatronics Engineering', 'code' => 'MCE', 'degree_title' => 'B.Eng. Mechatronics Engineering'],
                ],
            ],
        ];

        foreach ($faculties as $facData) {
            $faculty = Faculty::firstOrCreate(
                ['code' => $facData['code']],
                ['name' => $facData['name'], 'description' => $facData['description']]
            );

            foreach ($facData['departments'] as $dept) {
                Department::firstOrCreate(
                    ['code' => $dept['code']],
                    [
                        'faculty_id' => $faculty->id,
                        'name' => $dept['name'],
                        'degree_title' => $dept['degree_title'],
                    ]
                );
            }
        }
    }
}
