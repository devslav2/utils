<?php

namespace App\Services;

use App\Models\Job;
use Carbon\Carbon;

class JobService
{
    public function create(array $data)
    {
        $job = Job::findOrFail($data['job_id']);
        // logica di business
        $history = $job->history ?? [];
        $candidates = $history['candidates'] ?? [];

        // Aggiorna o aggiungi candidato
        $candidates[$data['user_id']] = [
            'status' => $data['status'],
            'job'    => $job->job_title,
        ];
        
        $history['updated_at'] = now();
        $history['candidates'] = $candidates;

        // validazioni avanzate
        // chiamate a model / repository
        $job->update([
            'history' => $history,
        ]);
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