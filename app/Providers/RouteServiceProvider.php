<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // Route::prefix('api')
            //     ->middleware('api')
            //     ->group(base_path('routes/api.php'));

            // Route::middleware('web')
            //     ->group(base_path('routes/web.php'));

            $this->mapApiRoutes();
            $this->mapWebRoutes();
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */

    protected function mapApiRoutes()
    {

        $files =  Finder::create()
            ->in(base_path('routes/api'))
            ->name('*.php');
        foreach ($files as $file)
            Route::prefix('api')
                ->middleware('api')
                ->group($file->getRealPath());
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */

    protected function mapWebRoutes()
    {

        $files =  Finder::create()
            ->in(base_path('routes/web'))
            ->name('*.php');
        foreach ($files as $file)
            Route::prefix('api')
                ->middleware('api')
                ->group($file->getRealPath());
    }
}
