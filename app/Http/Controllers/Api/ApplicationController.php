<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\JobListing;
use App\Http\Resources\ApplicationResource;

use Illuminate\Support\Facades\Auth;


class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->role === 'employer') {
            $applications = Application::with('jobListing', 'candidate')
                ->whereHas('jobListing', function ($query) {
                    $query->where('employer_id', Auth::id());
                })
                ->get();
            return ApplicationResource::collection($applications);
        } elseif ($request->user()->role === 'candidate') {
            $applications = Application::with('jobListing', 'candidate')
                ->where('candidate_id', Auth::id())
                ->get();
            return ApplicationResource::collection($applications);    
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd('store method called');
        //dd($request->all());

        $this->authorize('create', Application::class);

        $fields = $request->validate([
            'job_id' => 'required|exists:job_listings,id',
            'cover_letter' => 'nullable|string',
            'resume_path' => 'nullable|string',
        ]);

        $jobListing = JobListing::findOrFail($fields['job_id']);

        if ($jobListing->status !== 'published') {
            return response()->json(['message' => 'This job listing is not published.'], 422);
        }
        
        $isDuplicateApplication = Application::where('job_id', $fields['job_id'])
            ->where('candidate_id', Auth::id())
            ->exists();

        if ($isDuplicateApplication) {
            return response()->json(['message' => 'You have already applied for this job.'], 409);
        }

        $fields['candidate_id'] = Auth::id();

        $application = Application::create($fields);

        return response(new ApplicationResource($application), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application)
    {
        $this->authorize('view', $application);
        return new ApplicationResource($application->load('jobListing', 'candidate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $fields = $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected',
        ]);

        $application->update($fields);

        return new ApplicationResource($application);
    }
}
