<?php

namespace Allyson\MultiEnv;

use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Allyson\MultiEnv\Providers\ArtisanServiceProvider;

class MultiEnvServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__ . '/../config/envs.php', 'envs');
    }

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/envs.php' => config_path('envs.php'),
            ], 'envs-config');
        }
    }
}
