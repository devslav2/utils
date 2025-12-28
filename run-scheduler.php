<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Esegui lo scheduler
$status = $kernel->call('schedule:run');

echo "Scheduler eseguito con status: $status\n";
