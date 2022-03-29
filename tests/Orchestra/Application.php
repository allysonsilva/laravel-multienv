<?php

namespace Allyson\MultiEnv\Tests\Orchestra;

use App\Http\HttpKernel;
use App\Console\ConsoleKernel;
use App\Providers\RouteServiceProvider;
use Allyson\MultiEnv\MultiEnvServiceProvider;
use Allyson\MultiEnv\Bootstrappers\LoadEnvironmentVariables;
use Illuminate\Contracts\Http\Kernel as IlluminateHttpKernel;
use Illuminate\Contracts\Console\Kernel as IlluminateConsoleKernel;
use Orchestra\Testbench\Foundation\Application as OrchestraApplication;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as LaravelLoadEnvironmentVariables;

class Application extends OrchestraApplication
{
    /**
     * Load Environment variables.
     *
     * @var bool
     */
    protected $loadEnvironmentVariables = false;

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            MultiEnvServiceProvider::class,
            RouteServiceProvider::class,
        ];
    }

    /**
     * Resolve application core implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationCore($app)
    {
        parent::resolveApplicationCore($app);

        $app->singleton(LaravelLoadEnvironmentVariables::class, LoadEnvironmentVariables::class);
        // $app->make(LaravelLoadEnvironmentVariables::class)->bootstrap($app);
    }

    /**
     * Resolve application Console Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(IlluminateConsoleKernel::class, ConsoleKernel::class);
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(IlluminateHttpKernel::class, HttpKernel::class);
    }
}
