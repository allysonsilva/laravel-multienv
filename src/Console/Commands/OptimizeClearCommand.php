<?php

namespace Allyson\MultiEnv\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\OptimizeClearCommand as LaravelOptimizeClearCommand;

#[AsCommand(name: 'optimize:clear')]
class OptimizeClearCommand extends LaravelOptimizeClearCommand
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
        if (in_array($command, ['route:clear', 'config:clear'])) {
            $arguments = array_merge($arguments, ['--domain' => $this->option('domain')]);
        }

        return parent::callSilent($command, $arguments);
    }
}
