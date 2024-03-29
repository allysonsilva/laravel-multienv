<?php

namespace Allyson\MultiEnv\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\ConfigCacheCommand as LaravelConfigCacheCommand;

#[AsCommand(name: 'config:cache')]
class ConfigCacheCommand extends LaravelConfigCacheCommand
{
    use CommonOptions;

    /**
     * Call another console command without output.
     *
     * @param \Symfony\Component\Console\Command\Command|string $command
     * @param array<mixed> $arguments
     *
     * @return int
     */
    public function callSilent($command, array $arguments = [])
    {
        $arguments = array_merge($arguments, ['--domain' => $this->option('domain')]);

        return parent::call($command, $arguments);
    }
}
