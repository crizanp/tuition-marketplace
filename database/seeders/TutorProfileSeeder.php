<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tutor;
use App\Models\TutorProfile;

class TutorProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active tutors who don't have profiles yet
        $tutors = Tutor::whereDoesntHave('profile')->get();

        foreach ($tutors as $tutor) {
            TutorProfile::create([
                'tutor_id' => $tutor->id,
                'bio' => $tutor->bio,
                'languages' => [
                    ['name' => 'English', 'level' => 'Fluent'],
                    ['name' => 'Nepali', 'level' => 'Native'],
                ],
                'availability_status' => 'available',
                'availability_schedule' => [
                    'sunday' => ['start' => '09:00', 'end' => '18:00', 'off' => false],
                    'monday' => ['start' => '09:00', 'end' => '18:00', 'off' => false],
                    'tuesday' => ['start' => '09:00', 'end' => '18:00', 'off' => false],
                    'wednesday' => ['start' => '09:00', 'end' => '18:00', 'off' => false],
                    'thursday' => ['start' => '09:00', 'end' => '18:00', 'off' => false],
                    'friday' => ['start' => '09:00', 'end' => '18:00', 'off' => false],
                    'saturday' => ['start' => null, 'end' => null, 'off' => true],
                ],
            ]);
        }
    }
}
