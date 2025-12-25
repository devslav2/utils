<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobOfferController extends Controller
{
    public function index(Request $request)
    {
        $offers = Job::query()
            ->latest()
            ->select([
                "job_title",
                "slug",
                "salary"
            ])
            ->get();

        return response()->json([
            'data' => $offers
        ]);
    }
}
