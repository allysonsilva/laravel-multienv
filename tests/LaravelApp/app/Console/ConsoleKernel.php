<?php

namespace App\Console;

use Throwable;
use Allyson\MultiEnv\Concerns\ConsoleCallTrait;
use Allyson\MultiEnv\Concerns\BootstrappersTrait;
use Illuminate\Foundation\Console\Kernel as LaravelConsoleKernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration as LaravelLoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as LaravelLoadEnvironmentVariables;

class ConsoleKernel extends LaravelConsoleKernel
{
    use BootstrappersTrait, ConsoleCallTrait;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Report the exception to the exception handler.
     *
     * @param  \Throwable  $e
     *
     * @throws \Throwable
     *
     * @return void
     */
    protected function reportException(Throwable $e)
    {
        throw $e;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Bootstrap the application for artisan commands.
     *
     * @return void
     */
    public function bootstrap()
    {
        // Force reload bootstrappers needed to run
        // multiple cache commands in the same process or file!
        if ($this->app->hasBeenBootstrapped()) {
            $this->app->make(LaravelLoadEnvironmentVariables::class)->bootstrap($this->app);
            $this->app->make(LaravelLoadConfiguration::class)->bootstrap($this->app);
        }

        parent::bootstrap();
    }
}
