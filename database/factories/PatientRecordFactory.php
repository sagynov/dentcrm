<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientRecord>
 */
class PatientRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => 12,
            'doctor_id' => 2,
            'clinic_id' => 1,
            'comment' => fake()->text(),
            'attachments' => [],
        ];
    }
}
