<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\JobOfferController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* OLD
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/login', [AuthController::class, 'login']);// login pubblico

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // logout protetto
    Route::get('/user', function (Request $request) { // info utente
        return $request->user();
    });

    Route::get('/endpoint', [ApiController::class, 'methodName']);// tuo endpoint protetto

    Route::get('/job-offers', [JobOfferController::class, 'index']);
});