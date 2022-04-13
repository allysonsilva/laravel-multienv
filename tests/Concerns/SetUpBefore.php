<?php

namespace Allyson\MultiEnv\Tests\Concerns;

use App\Http\HttpKernel;
use App\Console\ConsoleKernel;
use App\Providers\RouteServiceProvider;
use Allyson\MultiEnv\MultiEnvServiceProvider;
use Allyson\MultiEnv\Bootstrappers\LoadEnvironmentVariables;
use Illuminate\Contracts\Http\Kernel as IlluminateHttpKernel;
use Illuminate\Contracts\Console\Kernel as IlluminateConsoleKernel;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as LaravelLoadEnvironmentVariables;

trait SetUpBefore
{
    /**
     * Application (business) folder path.
     *
     * @var string
     */
    protected static string $applicationPath;

    /**
     * Path of the folder containing the `.env` files.
     *
     * @var string
     */
    protected static string $envsFolder;

    /**
     * Get Application's base path.
     *
     * @return string
     */
    public static function applicationBasePath()
    {
        return realpath(__DIR__ . '/../LaravelApp');
    }

    /**
     * Resolve application setUp.
     *
     * @return void
     */
    protected static function resolveSetUpBefore(): void
    {
        static::$applicationPath = realpath(__DIR__ . '/../LaravelApp');
        static::$envsFolder = realpath(__DIR__ . '/../Configuration/envs');

        copy(static::$envsFolder . '/.env.example', static::applicationBasePath() . '/.env');

        if (! file_exists($envsPath = static::applicationBasePath() . '/envs/')) {
            mkdir($envsPath, 0755, true);
        }
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton(IlluminateHttpKernel::class, HttpKernel::class);
    }

    /**
     * Resolve application Console Kernel implementation.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(IlluminateConsoleKernel::class, ConsoleKernel::class);
    }

    /**
     * Resolve application core implementation.
     *
     * @param \Illuminate\Foundation\Application $app
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
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array<int, string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            MultiEnvServiceProvider::class,
            RouteServiceProvider::class
        ];
    }
}
