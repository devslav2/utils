<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Inertia\Inertia;
use App\Models\Job;
use App\Observers\JobObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share([
            'flash' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                ];
            },
        ]);

        Job::observe(JobObserver::class);
    }
}
