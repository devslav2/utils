<?php

namespace App\Services;

use App\Models\Job;
use Carbon\Carbon;

class JobService
{
    public function create(array $data)
    {
        //dd($data);
        $job = $data['job'];
        // logica di business
        $listing['history'] = $job->history ?? [];
        $listing['history'] = [
            'status' => $this->calculateStatus($job->history, $data['status']), // da calcolare in base al valore degli status dei candidates
            'updated_at' => now(),
            'candidates' => [
                $data['user_id'] => ["status" => $data['status'], "job" => $job->job_title]
            ], // array (json) di candidati
        ];
        
        // validazioni avanzate
        // chiamate a model / repository
        $job->update($listing);
    }

    private function calculateStatus(array $data, string $input_status): string
    {
        // logica di calcolo
        $status = "open";

        return $status;
    }

    public function recalculateStatus(Job $job): void
    {
        $history = $job->history ?? [];
        $maxHires = $job->max_hires ?? 1; //default

        $hiredCount = 0;

        $expiresAt = $job->expires_at
        ? Carbon::parse($job->expires_at)
        : null;

        foreach ($history['candidates'] ?? [] as $candidate) {
            if (($candidate['status'] ?? null) === 'hired') {
                $hiredCount++;
            }
        }

        if ($hiredCount >= $maxHires) {
            $history['status'] = 'completed';
            $job->history = $history;
            return;
        }

        if (
            $job->manually_closed ||
            ($expiresAt && $expiresAt->isPast())
        ) {
            $history['status'] = 'closed';
            $job->history = $history;
            return;
        }

        $history['status'] = 'open';

        $job->history = $history;
    }
}