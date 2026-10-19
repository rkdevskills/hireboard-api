<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\JobListing;

abstract class BaseTest extends TestCase
{
    use RefreshDatabase;

    protected function createEmployer()
    {
        return User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'employer'
        ]);
    }

    protected function createCandidate()
    {
        return User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'candidate'
        ]);
    }

    protected function createJob(User $employer, array $attributes = [])
    {
        return JobListing::factory()->create(array_merge([
            'employer_id' => $employer->id,
            'status' => 'published',
        ], $attributes));
    }
}