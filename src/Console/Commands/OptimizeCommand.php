<?php

namespace Allyson\MultiEnv\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\OptimizeCommand as LaravelOptimizeCommand;

#[AsCommand(name: 'optimize')]
class OptimizeCommand extends LaravelOptimizeCommand
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

        return parent::callSilent($command, $arguments);
    }
}
