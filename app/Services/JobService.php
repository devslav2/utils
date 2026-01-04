<?php

namespace App\Services;

use App\Models\Job;

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
}