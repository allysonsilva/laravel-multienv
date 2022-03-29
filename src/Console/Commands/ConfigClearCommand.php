<?php

namespace Allyson\MultiEnv\Console\Commands;

use Allyson\MultiEnv\Console\Concerns\CommonOptions;
use Illuminate\Foundation\Console\ConfigClearCommand as LaravelConfigClearCommand;

class ConfigClearCommand extends LaravelConfigClearCommand
{
    use CommonOptions;
}
