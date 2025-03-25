<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()
            ->has(Clinic::factory()
                ->has(Doctor::factory()->count(10))
                ->has(Patient::factory()->count(10))
                ->count(5)
            )
            ->create([
                'phone' => '77713652407',
                'password' => 'password'
            ]);
        $clinic = $user->clinics()->first();
        Appointment::factory(5)->create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $clinic->doctors()->first()->user_id,
            'patient_id' => $clinic->patients()->first()->user_id,
        ]);

    }
}
