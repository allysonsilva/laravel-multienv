<?php

namespace Allyson\MultiEnv\Console\Concerns;

use Symfony\Component\Console\Input\InputOption;

trait CommonOptions
{
    /**
     * Get the console command options.
     *
     * @return array<mixed>
     */
    protected function getOptions()
    {
        $options = parent::getOptions();

        $domainDescription = 'Filter the routes by domain | The domain the command should run under';

        array_push($options, ['domain', null, InputOption::VALUE_OPTIONAL, $domainDescription]);

        return $options;
    }
}
