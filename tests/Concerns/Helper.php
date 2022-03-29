<?php

namespace Allyson\MultiEnv\Tests\Concerns;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Contracts\Console\Kernel;

trait Helper
{
    /**
     * Copy `.env` files to laravel root folder.
     *
     * @param string[] $envsName
     *
     * @return void
     */
    protected function copyEnvsToRoot(string ...$envsName): void
    {
        if (empty($envsName)) {
            foreach (glob(static::$envsFolder . '/.env[ABC]') as $file) {
                $envsName[] = basename($file);
            }
        }

        foreach ($envsName as $envFilename) {
            if (file_exists($envToCopy = static::$envsFolder . '/' . $envFilename)) {
                copy($envToCopy, "{$this->getBasePath()}/{$envFilename}");
            }
        }
    }

    /**
     * Retrieves the path of the folder where the domain's .envs files should be.
     *
     * @return string
     */
    private function getLaravelAppEnvsFolder(): string
    {
        $envsFolder = config('envs.folder') ?? 'envs';

        return "{$this->getBasePath()}/{$envsFolder}";
    }

    /**
     * Copy `.env` files to `envs` folder.
     *
     * Copy the domain's `.env` file to the custom folder according to the `envs.folder` config.
     *
     * @param string[] $envsName
     *
     * @return void
     */
    protected function copyEnvsToEnvs(string ...$envsName): void
    {
        foreach ($envsName as $envFilename) {
            $envToCopy = static::$envsFolder . '/' . $envFilename;

            copy($envToCopy, "{$this->getLaravelAppEnvsFolder()}/{$envFilename}");
        }
    }

    /**
     * @param string $envSource
     * @param string $envDest
     *
     * @return void
     */
    protected function copyEnvsToEnvsWithCustomName(string $envSource, string $envDest): void
    {
        $this->copyEnvsToEnvs($envSource);

        rename("{$this->getLaravelAppEnvsFolder()}/{$envSource}", "{$this->getLaravelAppEnvsFolder()}/{$envDest}");
    }

    /**
     * - Deletes all `.env[ABC]` files in the Laravel root folder.
     * - Deleted configuration files from config folder.
     *
     * @return void
     */
    protected static function cleanEnvsAndConfigs(): void
    {
        $laravelBasePath = static::applicationBasePath();

        foreach (glob("{$laravelBasePath}/.env{[ABC],.non-existent-domain}", GLOB_BRACE) as $envFile) {
            unlink($envFile);
        }

        exec(sprintf('rm -rf %s', escapeshellarg("{$laravelBasePath}/envs/")));
        exec(sprintf('find %s -maxdepth 1 -type f -name "*.php" -exec rm "{}" \;', escapeshellarg("{$laravelBasePath}/bootstrap/cache/")));

        // Clean up
        @unlink("{$laravelBasePath}/.env");
        @unlink("$laravelBasePath/config/envs.php");

        return;
    }

    /**
     * Delete all Laravel cache files from `bootstrap/cache` folder.
     *
     * @return void
     */
    protected static function cleanBootstrapCaches(): void
    {
        $bootstrapCachePath = realpath(static::applicationBasePath() . '/bootstrap/cache/');

        File::cleanDirectory($bootstrapCachePath);
    }

    /**
     * Run artisan in a php process using `proc_open`.
     *
     * @param string[] $command
     *
     * @example $this->runArtisan('route:list', '--domain=xyz')->getOutput()
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function runArtisan(string ...$command): Process
    {
        $artisan = "{$this->getBasePath()}/artisan";

        $process = new Process(['php', $artisan, ...$command]);
        $process->run();

        return $process;
    }

    /**
     * Retrieves the fixture of the `$name` parameter.
     *
     * @param string $name
     *
     * @return array
     */
    protected function getFixture(string $name): array
    {
        $jsonContent = file_get_contents(__DIR__ . "/../Configuration/fixtures/{$name}.json");

        return json_decode($jsonContent, true);
    }

    /**
     * Replace stub fields to create a class.
     *
     * @param string $stubName
     * @param string $namespace
     * @param string $className
     * @param string $path
     *
     * @return void
     */
    protected function buildClass(string $stubName, string $namespace, string $className, string $path): void
    {
        $stub = realpath(__DIR__ . '/../') . "/stubs/{$stubName}";

        $fileContent = file_get_contents($stub);

        $fileContent = str_replace(
            ["{{ namespace }}", "{{ class }}"],
            [$namespace, $className],
            $fileContent
        );

        $file = "{$path}/{$className}.php";

        if (file_exists($file)) return;

        file_put_contents($file, $fileContent, LOCK_EX);
    }

    /**
     * Assert to check if the expected string is in the command output.
     *
     * @param string $expectedText
     *
     * @return void
     */
    protected function seeInConsoleOutput(string $expectedText): void
    {
        $consoleOutput = $this->app[Kernel::class]->output();

        $this->assertStringContainsString(
            $expectedText,
            $consoleOutput,
            "Did not see `{$expectedText}` in console output: `$consoleOutput`"
        );
    }

    /**
     * Assert to check whether a given string is not in the command output.
     *
     * @param string $unexpectedText
     *
     * @return void
     */
    protected function doNotSeeInConsoleOutput(string $unexpectedText): void
    {
        $consoleOutput = $this->app[Kernel::class]->output();

        $this->assertNotContains(
            $unexpectedText,
            $consoleOutput,
            "Did not expect to see `{$unexpectedText}` in console output: `$consoleOutput`"
        );
    }
}
