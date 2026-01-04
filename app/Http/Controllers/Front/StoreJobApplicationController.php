<?php

namespace App\Http\Controllers\Front;

use App\Events\JobApplicationReceivedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StoreJobApplicationController extends Controller
{
    public function store(StoreJobApplicationRequest $request, Job $job, JobService $jobService)
    {
        // get validated fields
        $validated = $request->validated();

        // append resume
        $validated["resume"] = $request->file("resume")->store("resumes");

        // append coverLetter
        $validated["cover_letter"] = request("coverLetter");

        $validated['user_id'] = Auth::id();

        // create job application
        $application = $job->applications()->create($validated);

        $job_update = array(
            "job" => $job,
            "user_id" => Auth::id(),
            "status" => "new",
        );

        $jobService->create($job_update);

        // fire events
        event(new JobApplicationReceivedEvent($application));

        // redirect with success message
        return redirect()->route("jobApplicationReceived", ["job" => $job]);
    }

    public function applicationReceived(Job $job)
    {
        return Inertia::render("JobApplicationReceived", compact("job"));
    }
}
