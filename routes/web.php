<?php

use App\Http\Controllers\Front\HomepageController;
use App\Http\Controllers\Front\JobDetailsController;
use App\Http\Controllers\Front\StoreJobApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dashboard\JobController;
use App\Services\MessageQueueService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::any('/', HomepageController::class)->name('homepage');
Route::get('position/{job}', JobDetailsController::class)->name('jobDetails');

Route::middleware('auth.candidatura')->group(function () {
    Route::post('position/{job}/store-application', [StoreJobApplicationController::class, 'store'])->name('storeJobApplication');
});

Route::get('application/{job}', [StoreJobApplicationController::class, 'applicationReceived'])->name('jobApplicationReceived');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/jobs', [JobController::class, 'index'])
        ->name('jobs');
});

Route::get('/send', function (MessageQueueService $producer) {
    $message = json_encode([
        'user_id' => 12,
        'new_email' => 'nuovo@email.com',
        'name' => 'Mario Kafka'
    ]);

    $producer->send($message);

    return "Messaggio inviato!";
});

Route::get('/debug-test', function () {
    $var = 'Hello Xdebug!';
    return $var;
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
