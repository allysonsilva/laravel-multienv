<?php

namespace Allyson\MultiEnv\Console\Commands;

use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\RouteClearCommand as LaravelRouteClearCommand;

class RouteClearCommand extends LaravelRouteClearCommand
{
    use CommonOptions;
}
