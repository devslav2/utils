<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Job;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CloseExpiredJobs extends Command
{
    // Firma del comando
    protected $signature = 'jobs:close-expired';

    // Descrizione del comando
    protected $description = 'Chiude automaticamente i job scaduti aggiornando lo status nella history.';

    public function handle()
    {
        $now = Carbon::now()->toISOString();

        // Patch JSON da applicare
        $patch = json_encode([
            'status' => 'closed',
            'updated_at' => $now,
        ]);

        // Aggiorna tutti i job scaduti in un'unica query
        $affected = DB::table('jobs')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', $now)
            ->whereRaw("history->>'status' != ?", ['completed'])
            ->update([
                'history' => DB::raw("jsonb_merge_patch(history, '$patch'::jsonb)")
            ]);

        $this->info("Job scaduti aggiornati: $affected");
    }
}