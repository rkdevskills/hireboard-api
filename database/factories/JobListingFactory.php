<?php

namespace Database\Factories;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<JobListing>
 */
class JobListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employer_id' => User::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'type' => fake()->randomElement(['full-time', 'part-time', 'contract', 'remote']),
            'salary_min' => fake()->numberBetween(20000, 50000),
            'salary_max' => fake()->numberBetween(50000, 100000),
            'status' => 'published',
        ];
    }
}
