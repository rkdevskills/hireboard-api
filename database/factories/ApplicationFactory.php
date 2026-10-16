<?php

namespace Database\Factories;

use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JobListing;
use App\Models\User;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => JobListing::factory(),
            'candidate_id' => User::factory(),
            'cover_letter' => $this->faker->text(),
            'resume_path' => $this->faker->filePath(),
            'status' => $this->faker->randomElement(['pending', 'reviewed', 'shortlisted', 'rejected']),
        ];
    }
}
