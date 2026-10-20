<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\JobListingResource;
use App\Models\JobListing;
use Illuminate\Support\Facades\Auth;

class JobListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
    
        if($user && $user->role === 'employer') {
            $jobListings = JobListing::with('tags','employer')
                ->where('employer_id',$user->id);
            
            if ($request->filled('status')) {
                $jobListings->where('status', $request->status);
            }

            $jobListings = $jobListings
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return JobListingResource::collection($jobListings);
        } else {
            // $publishedJobListings = JobListing::with('tags','employer')
            //     ->where('status', 'published')
            //     ->orderBy('created_at', 'desc')
            //     ->get();
            // return JobListingResource::collection($publishedJobListings);

            // with the scopes
            $query = JobListing::with('tags','employer')->published();

            if ($request->has('search')) $query->search($request->search);

            if ($request->has('location')) $query->location($request->location);

            if ($request->has('type')) $query->ofType($request->type);

            if ($request->has('salary_min') || $request->has('salary_max')) {
                $query->salary($request->salary_min,$request->salary_max);
            }

            return JobListingResource::collection($query->orderBy('created_at', 'desc')->paginate(20));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', JobListing::class);

        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string',
            'type' => 'required|in:full-time,part-time,contract,remote',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'status' => 'required|in:draft,published,closed',
        ]);

        $fields['employer_id'] = Auth::id();

        $jobListing = JobListing::create($fields);

        return response(new JobListingResource($jobListing), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobListing $jobListing)
    {
        if($jobListing->status === 'draft') {

            $user = auth('sanctum')->user();
            
            if(!$user || $user->role !== 'employer' || 
            (int) $jobListing->employer_id !==  (int) $user->id ) {
                abort(404);
            }
        }
            
        return response(new JobListingResource($jobListing->load('tags', 'employer')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobListing $jobListing)
    {
        $this->authorize('update', $jobListing);

        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string',
            'type' => 'required|in:full-time,part-time,contract,remote',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric',
            'status' => 'required|in:draft,published,closed',
        ]);

        $jobListing->update($fields);

        return new JobListingResource($jobListing);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobListing $jobListing)
    {
        $this->authorize('delete', $jobListing);

        if ($jobListing->applications()->exists()) {
            return response()->json(['message' => 'Cannot delete job listing with existing applications.'], 422);
        }
        
        $jobListing->delete();

        return response(null, 204);
    }
}
