<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\JobOfferController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobApi = new JobOfferController();

        $response = $jobApi->index($request);

        // Decodifica i dati JSON
        $jobs = json_decode($response->getContent(), true);

        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs['data'],
            'auth' => [
                'user' => Auth::user(),
            ],
        ]);
    }
}