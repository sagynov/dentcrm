<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'doctor_id' => Doctor::factory(),
            'patient_id' => Patient::factory(),
            'notes' => fake()->text(100),
            'visit_at' => fake()->dateTimeBetween('today', 'tomorrow')->format('d-m-Y H:i'),
            'status' => 'scheduled'
        ];
    }
}
