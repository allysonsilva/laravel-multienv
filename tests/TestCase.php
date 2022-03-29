<?php

namespace Allyson\MultiEnv\Tests;

use Allyson\MultiEnv\Tests\Concerns\Helper;
use Orchestra\Testbench\TestCase as Orchestra;
use Allyson\MultiEnv\Tests\Concerns\SetUpBefore;
use Illuminate\Foundation\Bootstrap\LoadConfiguration as LaravelLoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as LaravelLoadEnvironmentVariables;

class TestCase extends Orchestra
{
    use SetUpBefore, Helper;

    /**
     * Load Environment variables.
     *
     * @var bool
     */
    protected $loadEnvironmentVariables = false;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        static::cleanEnvsAndConfigs();
        static::resolveSetUpBefore();

        $this->afterApplicationRefreshed(function (): void {
            if (! is_file("{$this->getBasePath()}/config/envs.php")) {
                $this->copyEnvsToRoot('.env.non-existent-domain');

                $this->artisan('vendor:publish', ['--tag' => 'envs-config', '--force' => true, ])->run();

                $this->app->make(LaravelLoadConfiguration::class)->bootstrap($this->app);
            }
        });

        // Code before application created.
        parent::setUp();
        // Code after application created.
    }

    /**
     * This method is called after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        static::cleanEnvsAndConfigs();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function defineEnvironment($app)
    {
        //
    }

    /**
     * Reload bootstrappers.
     *
     * @return void
     */
    protected function refreshConfiguration(): void
    {
        $this->app->make(LaravelLoadEnvironmentVariables::class)->bootstrap($this->app);
        $this->app->make(LaravelLoadConfiguration::class)->bootstrap($this->app);
    }

    /**
     * Update the `envs.php` config file.
     *
     * @return void
     */
    protected function updateEnvsConfigFile(): void
    {
        $newEnvsConfig = '<?php return ' . var_export(config('envs'), true) . ';';

        $this->app['files']->replace(
            $this->app->configPath('envs.php'), $newEnvsConfig
        );
    }

    /**
     * Configure the `sorted` key from the configuration.
     *
     * @param string $sorted
     *
     * @return void
     */
    protected function setCustomEnvsSort(string $sorted): void
    {
        $envsConfig = $this->getBasePath() . '/config/envs.php';

        $dataEnvsConfig = file($envsConfig);
        $dataEnvsConfig[21] = "    'sorted' => [{$sorted}],\n";
        $dataEnvsConfig = implode($dataEnvsConfig);

        file_put_contents($envsConfig, $dataEnvsConfig);

        $this->refreshConfiguration();
    }
}
