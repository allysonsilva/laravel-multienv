<?php

namespace Allyson\MultiEnv\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Allyson\MultiEnv\Console\Commands\RouteCacheCommand;
use Allyson\MultiEnv\Console\Commands\RouteClearCommand;
use Allyson\MultiEnv\Console\Commands\ConfigCacheCommand;
use Allyson\MultiEnv\Console\Commands\ConfigClearCommand;

class ArtisanServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * The commands to be registered.
     *
     * @var array<string, string>
     */
    protected $commands = [
        'ConfigCache' => ConfigCacheCommand::class,
        'ConfigClear' => ConfigClearCommand::class,
        'RouteCache' => RouteCacheCommand::class,
        'RouteClear' => RouteClearCommand::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerCommands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return array_values($this->commands);
    }

    /**
     * Register the given commands.
     *
     * @param array<string, string> $commands
     *
     * @return void
     */
    protected function registerCommands(array $commands): void
    {
        foreach (array_keys($commands) as $command) {
            $this->{"register{$command}Command"}();
        }

        $commands = array_values($commands);

        $this->commands($commands);
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerConfigCacheCommand(): void
    {
        $this->app->singleton(ConfigCacheCommand::class, function ($app) {
            return new ConfigCacheCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerConfigClearCommand(): void
    {
        $this->app->singleton(ConfigClearCommand::class, function ($app) {
            return new ConfigClearCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRouteCacheCommand(): void
    {
        $this->app->singleton(RouteCacheCommand::class, function ($app) {
            return new RouteCacheCommand($app['files']);
        });
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRouteClearCommand(): void
    {
        $this->app->singleton(RouteClearCommand::class, function ($app) {
            return new RouteClearCommand($app['files']);
        });
    }
}
