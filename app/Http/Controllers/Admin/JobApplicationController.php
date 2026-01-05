<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Services\JobService;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class JobApplicationController extends Controller
{
    protected JobService $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function view(JobApplication $jobApplication)
    {

        // uses JobApplicationPolicy
        $this->authorize('view', $jobApplication);

        // append job details to the bound $jobApplication
        $jobApplication->load('job', 'job.location', 'job.department', 'job.contractType');

        return Inertia::render('Dashboard/JobApplication', compact('jobApplication'));
    }

    public function updateStatus(JobApplication $jobApplication)
    {

        // uses JobApplicationPolicy
        $this->authorize('updateStatus', $jobApplication);

        $oldStatus = $jobApplication->status;

        $jobApplication->update([
            'status' => request('status'),
        ]);

        // Aggiorna la history e ricalcola lo status globale del job
        $this->jobService->create([
            'job_id'   => $jobApplication->job_id,
            'user_id'  => $jobApplication->user_id,
            'status'   => request('status'),
        ]);

        session()->flash('success', __('Job application status updated successfully from :oldStatus to :newStatus', [
            'oldStatus' => $oldStatus,
            'newStatus' => $jobApplication->status,
        ]));

        return back();
    }

    public function downloadPDF(JobApplication $jobApplication)
    {
        $this->authorize('view', $jobApplication);

        // pdf file
        $cvPDF = $jobApplication->resume;

        return response()->download(Storage::path($cvPDF));
    }
}
