<?php

namespace Allyson\MultiEnv\Concerns;

use Allyson\MultiEnv\Bootstrappers\LoadEnvironmentVariables;
use Illuminate\Foundation\Bootstrap\LoadConfiguration as LaravelLoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as LaravelLoadEnvironmentVariables;

trait BootstrappersTrait
{
    /**
     * Bootstrappers that will run at startup.
     *
     * @var array<class-string>
     */
    protected array $beforeBootstrappers = [
        LoadEnvironmentVariables::class,
        LaravelLoadConfiguration::class,
    ];

    /**
     * Bootstrappers that will run at the end.
     *
     * @var array<class-string>
     */
    protected array $afterBootstrappers = [];

    /**
     * Bootstrappers that won't run.
     *
     * @var array<class-string>
     */
    protected array $withoutBootstrappers = [
        LaravelLoadEnvironmentVariables::class,
    ];

    /**
     * Returns bootstrappers that will be run at startup.
     *
     * @var array<class-string>
     */
    public function beforeBootstrappers(): array
    {
        return $this->beforeBootstrappers;
    }

    /**
     * Returns the bootstrappers that will be executed at the end.
     *
     * @var array<class-string>
     */
    public function afterBootstrappers(): array
    {
        return $this->afterBootstrappers;
    }

    /**
     * Return bootstrappers that will not be executed.
     *
     * @var array<class-string>
     */
    public function withoutBootstrappers(): array
    {
        return $this->withoutBootstrappers;
    }

    /**
     * Get the bootstrap classes for the application.
     *
     * @return array
     */
    protected function bootstrappers(): array
    {
        $allBootstrappers = array_merge(
            $this->beforeBootstrappers(),
            parent::bootstrappers(),
            $this->afterBootstrappers()
        );

        $bootstrappers = array_filter($allBootstrappers, function ($bootstrapper) {
            return ! in_array($bootstrapper, $this->withoutBootstrappers());
        });

        return array_unique($bootstrappers);
    }
}
