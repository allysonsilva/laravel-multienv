<?php

namespace Allyson\MultiEnv\Console\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\RouteClearCommand as LaravelRouteClearCommand;

#[AsCommand(name: 'route:clear')]
class RouteClearCommand extends LaravelRouteClearCommand
{
    use CommonOptions;
}
