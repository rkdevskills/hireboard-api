<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTest;

class ApplicationTest extends BaseTest
{
    use RefreshDatabase;

    public function test_candidate_can_apply_for_job(): void
    {
        $candidate = $this->createCandidate();

        $employer = $this->createEmployer();

        $job = $this->createJob($employer);
        
        $response = $this->actingAs($candidate)->postJson("/api/applications",[
            'job_id' => $job->id,
            'cover_letter' => 'test test',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('applications',[
            'job_id' => $job->id,
            'cover_letter' => 'test test',
        ]);
    }

    public function test_candidate_cannot_apply_twice(): void
    {
        $candidate = $this->createCandidate();

        $employer = $this->createEmployer();

        $job = $this->createJob($employer);

        $this->actingAs($candidate)->postJson("/api/applications",[
            'job_id' => $job->id,
            'cover_letter' => 'test test',
        ]);

        $response = $this->actingAs($candidate)->postJson("/api/applications",[
            'job_id' => $job->id,
            'cover_letter' => 'test test',
        ]);

        $response->assertStatus(409);
        $this->assertDatabaseCount('applications', 1);
    }

    public function test_employer_cannot_apply_for_job(): void
    {
        $employer = $this->createEmployer();

        $job = $this->createJob($employer);

        $response = $this->actingAs($employer)->postJson("/api/applications",[
            'job_id' => $job->id,
            'cover_letter' => 'test test',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('applications',[
            'job_id' => $job->id,
            'cover_letter' => 'test test',
        ]);
    }

    public function test_employer_can_update_application_status(): void
    {
        $candidate = $this->createCandidate();
        $employer = $this->createEmployer();
        $job = $this->createJob($employer);

        $applicationResponse = $this->actingAs($candidate)->postJson("/api/applications", [
            'job_id' => $job->id,
            'cover_letter' => 'test cover letter',
        ]);
        
        $applicationId = $applicationResponse->json('id');

        $response = $this->actingAs($employer)->putJson("/api/applications/{$applicationId}",[
            'status' => 'shortlisted',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('applications',[
            'id' => $applicationId,
            'status' => 'shortlisted',
        ]);
    }
}
