<?php

namespace Allyson\MultiEnv\Tests\Orchestra;

use Orchestra\Testbench\Console\Commander as OrchestraCommander;

class Commander extends OrchestraCommander
{
    /**
     * Get Application base path.
     *
     * @return string
     */
    public static function applicationBasePath()
    {
        return realpath(__DIR__ . '/../LaravelApp');
    }

    /**
     * Create Laravel application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function laravel()
    {
        if (! $this->app) {
            $this->app = Application::create($this->getBasePath(), null, [
                'extra' => [
                    'providers' => $this->config['providers'] ?? [],
                    'dont-discover' => $this->config['dont-discover'] ?? [],
                ],
            ]);
        }

        return $this->app;
    }
}
