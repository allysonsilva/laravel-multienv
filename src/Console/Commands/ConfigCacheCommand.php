<?php

namespace Allyson\MultiEnv\Console\Commands;

use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\ConfigCacheCommand as LaravelConfigCacheCommand;

class ConfigCacheCommand extends LaravelConfigCacheCommand
{
    use CommonOptions;

    /**
     * Call another console command.
     *
     * @param \Symfony\Component\Console\Command\Command|string $command
     * @param array<mixed> $arguments
     *
     * @return int
     */
    public function call($command, array $arguments = [])
    {
        $arguments = array_merge($arguments, ['--domain' => $this->option('domain')]);

        return parent::call($command, $arguments);
    }
}
