<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Throwable;

class ConsumeKafkaMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $tries = 5;
    public $timeout = 120;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        try {
            Log::info('🚀 Job Kafka ricevuto con messaggio: ' . $this->message);
            
            $data = json_decode($this->message, true);

            if (!$data || !isset($data['user_id'])) {
                Log::warning('Messaggio Kafka non valido: ' . $this->message);
                return;
            }

            $user = User::find($data['user_id']);
            if (!$user) {
                Log::warning('Utente non trovato: ID ' . $data['user_id']);
                return;
            }

            if (isset($data['new_email'])) $user->email = $data['new_email'];
            if (isset($data['name'])) $user->name = $data['name'];

            $user->save();

            Log::info("Utente aggiornato: ID {$user->id}");

        } catch (Throwable $e) {
            Log::error("Errore job Kafka: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(Throwable $exception)
    {
        Log::error("Job Kafka fallito definitivamente: " . $exception->getMessage());
    }
}