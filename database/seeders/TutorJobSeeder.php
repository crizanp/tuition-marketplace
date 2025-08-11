<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TutorJob;
use App\Models\Tutor;

class TutorJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutors = Tutor::where('status', 'active')->get();
        
        if ($tutors->count() === 0) {
            $this->command->info('No active tutors found. Please create some tutors first.');
            return;
        }

        $sampleJobs = [
            [
                'title' => 'Mathematics Tutor for High School Students',
                'description' => 'Experienced mathematics tutor offering comprehensive support for high school students. I specialize in algebra, geometry, calculus, and exam preparation. With 5+ years of teaching experience, I use practical examples and interactive methods to make math concepts clear and engaging.',
                'subjects' => ['Mathematics', 'Physics'],
                'hourly_rate' => 25.00,
                'country' => 'Nepal',
                'state' => 'Bagmati Province',
                'district' => 'Kathmandu',
                'place' => 'Thamel',
                'landmark' => 'Near Garden of Dreams',
                'ward_no' => '16',
                'postal_code' => '44600',
                'teaching_mode' => 'any',
                'preferred_times' => ['evening', 'afternoon'],
                'gender_preference' => 'any',
                'student_level' => 'Grade 9-12',
                'requirements' => 'Students should bring their textbooks and be ready to practice problems.',
                'max_students' => 3,
                'session_type' => 'both',
                'status' => 'active',
            ],
            [
                'title' => 'English Language & Literature Expert',
                'description' => 'Native English speaker with Master\'s in English Literature. I help students improve their speaking, writing, and comprehension skills. Perfect for students preparing for IELTS, TOEFL, or academic writing.',
                'subjects' => ['English', 'Foreign Languages'],
                'hourly_rate' => 30.00,
                'country' => 'Nepal',
                'state' => 'Bagmati Province',
                'district' => 'Lalitpur',
                'place' => 'Patan',
                'landmark' => 'Near Patan Durbar Square',
                'teaching_mode' => 'online',
                'preferred_times' => ['morning', 'evening'],
                'gender_preference' => 'any',
                'student_level' => 'All levels',
                'max_students' => 1,
                'session_type' => 'individual',
                'status' => 'active',
            ],
            [
                'title' => 'Computer Science & Programming',
                'description' => 'Software engineer with 3 years of industry experience teaching programming fundamentals, web development, and computer science concepts. I cover Python, JavaScript, HTML/CSS, and basic algorithms.',
                'subjects' => ['Computer Science'],
                'hourly_rate' => 35.00,
                'country' => 'Nepal',
                'state' => 'Bagmati Province',
                'district' => 'Bhaktapur',
                'place' => 'Bhaktapur Durbar Square Area',
                'teaching_mode' => 'home',
                'preferred_times' => ['afternoon', 'evening'],
                'gender_preference' => 'any',
                'student_level' => 'Beginner to Intermediate',
                'requirements' => 'Students need a laptop with internet connection.',
                'max_students' => 2,
                'session_type' => 'both',
                'status' => 'active',
            ],
            [
                'title' => 'Science Tutor - Biology & Chemistry',
                'description' => 'Medical graduate offering tutoring in Biology and Chemistry for high school and pre-medical students. I focus on conceptual understanding and practical application of scientific principles.',
                'subjects' => ['Biology', 'Chemistry'],
                'hourly_rate' => 28.00,
                'country' => 'Nepal',
                'state' => 'Bagmati Province',
                'district' => 'Kathmandu',
                'place' => 'Baneshwor',
                'landmark' => 'Near TU Teaching Hospital',
                'teaching_mode' => 'any',
                'preferred_times' => ['morning', 'afternoon'],
                'gender_preference' => 'any',
                'student_level' => 'Grade 10-12, Pre-medical',
                'max_students' => 4,
                'session_type' => 'group',
                'status' => 'active',
            ],
        ];

        foreach ($sampleJobs as $jobData) {
            // Assign to a random active tutor
            $tutor = $tutors->random();
            $jobData['tutor_id'] = $tutor->id;
            
            TutorJob::create($jobData);
        }

        $this->command->info('Sample tutor jobs created successfully!');
    }
}
