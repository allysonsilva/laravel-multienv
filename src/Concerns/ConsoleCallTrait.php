<?php

namespace Allyson\MultiEnv\Concerns;

trait ConsoleCallTrait
{
    /**
     * Run an Artisan console command by name.
     *
     * @param  string  $command
     * @param  array  $parameters
     * @param  \Symfony\Component\Console\Output\OutputInterface|null  $outputBuffer
     *
     * @return int
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     *
     * @throws \Symfony\Component\Console\Exception\CommandNotFoundException
     */
    public function call($command, array $parameters = [], $outputBuffer = null)
    {
        putenv('RUNNING_IN_ARTISAN_CALL=true');

        $removeDomainFromArgv = array_filter($_SERVER['argv'] ?? [], function ($arg) {
            return ! str_starts_with((string) $arg, '--domain');
        });

        $_SERVER['argv'] = $removeDomainFromArgv;

        if (! empty($domain = $parameters['--domain'] ?? [])) {
            $_SERVER['argv'][] = "--domain={$domain}";
        }

        return parent::call($command, $parameters, $outputBuffer);
    }
}
