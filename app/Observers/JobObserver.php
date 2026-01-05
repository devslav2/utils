<?php

namespace App\Observers;

use App\Models\Job;
use App\Services\JobService;

class JobObserver
{
    /**
     * Handle the Job "created" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function created(Job $job)
    {
        //
    }

    /**
     * Handle the Job "updated" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function updated(Job $job)
    {
        //
    }

    /**
     * Handle the Job "deleted" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function deleted(Job $job)
    {
        //
    }

    /**
     * Handle the Job "restored" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function restored(Job $job)
    {
        //
    }

    /**
     * Handle the Job "force deleted" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function forceDeleted(Job $job)
    {
        //
    }

     /**
     * Handle the Job "saving" event.
     *
     * @param  \App\Models\Job  $job
     * @return void
     */
    public function saving(Job $job): void
    {
        if (
            !$job->isDirty([
                'history',
                'expires_at',
                'manually_closed',
                'max_hires',
            ])
        ) {
            return;
        }

        app(JobService::class)->recalculateStatus($job);
    }
}
