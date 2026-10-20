<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\BaseTest;

class JobListingTest extends BaseTest
{
    use RefreshDatabase;

    public function test_employer_can_create_job(): void
    {
        $employer = $this->createEmployer();

        $response = $this->actingAs($employer)->postJson('/api/jobs', [
                'title' => 'php developer',
                'description' => 'php developer derscription',
                'location' => 'London',
                'type' => 'full-time',
                'salary_min' => '3000',
                'salary_max' => '5000',
                'status' => 'draft',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('job_listings', [
            'title' => 'php developer',
            'description' => 'php developer derscription',
            'location' => 'London',
            'type' => 'full-time',
            'salary_min' => '3000',
            'salary_max' => '5000',
            'status' => 'draft',
        ]);
    }

    public function test_candidate_cannot_create_job(): void
    {
        $candidate = $this->createCandidate();

        $response = $this->actingAs($candidate)->postJson('/api/jobs', [
                'title' => 'php developer',
                'description' => 'php developer derscription',
                'location' => 'London',
                'type' => 'full-time',
                'salary_min' => '3000',
                'salary_max' => '5000',
                'status' => 'draft',
            ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('job_listings', [
            'title' => 'php developer',
            'description' => 'php developer derscription',
            'location' => 'London',
            'type' => 'full-time',
            'salary_min' => '3000',
            'salary_max' => '5000',
            'status' => 'draft',
        ]);
    }

    public function test_anyone_can_view_published_jobs(): void
    {
        $response = $this->getJson('/api/jobs');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'location', 'type','salary_min','salary_max', 'status']
            ]
        ]);
    }

    public function test_employer_can_update_own_job(): void
    {
        $employer = $this->createEmployer();

        $job = $this->createJob($employer);

       $response = $this->actingAs($employer)->putJson("/api/jobs/{$job->id}", [
            'title' => 'laravel developer',
            'description' => $job->description,
            'location' => $job->location,
            'type' => $job->type,
            'status' => $job->status,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('job_listings', [
            'title' => 'laravel developer'
        ]);
    }

    public function test_employer_cannot_update_others_job(): void
    {
        $employer = $this->createEmployer();

        $job = $this->createJob($employer);

        $otherEmployer = $this->createEmployer();

        $response = $this->actingAs($otherEmployer)->putJson("/api/jobs/{$job->id}", [
            'title' => 'laravel developer',
            'description' => $job->description,
            'location' => $job->location,
            'type' => $job->type,
            'status' => $job->status,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('job_listings', [
            'title' => 'laravel developer'
        ]);
    }

    public function test_visitor_cannot_view_draft_job(): void
    {
        $employer = $this->createEmployer();
        $job = $this->createJob($employer, ['status' => 'draft']);

        $response = $this->getJson("/api/jobs/{$job->id}");

        $response->assertStatus(404);
    }

    public function test_visitor_can_view_published_job(): void
    {
        $employer = $this->createEmployer();
        $job = $this->createJob($employer);

        $response = $this->getJson("/api/jobs/{$job->id}");
        $response->assertStatus(200);
    }

    public function test_employer_can_view_own_draft_job(): void
    {
        $employer = $this->createEmployer();
        $job = $this->createJob($employer, ['status' => 'draft']);

        $response = $this->actingAs($employer)->getJson("/api/jobs/{$job->id}");

        $response->assertStatus(200);
    }

    public function test_employer_cannot_view_others_draft_job(): void
    {
        $employer = $this->createEmployer();
        $job = $this->createJob($employer, ['status' => 'draft']);

        $otherEmployer = $this->createEmployer();

        $response = $this->actingAs($otherEmployer)->getJson("/api/jobs/{$job->id}");

        $response->assertStatus(404);
    }
}
