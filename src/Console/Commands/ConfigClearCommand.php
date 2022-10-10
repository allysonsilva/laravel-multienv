<?php

namespace Allyson\MultiEnv\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\ConfigClearCommand as LaravelConfigClearCommand;

#[AsCommand(name: 'config:clear')]
class ConfigClearCommand extends LaravelConfigClearCommand
{
    use CommonOptions;
}
