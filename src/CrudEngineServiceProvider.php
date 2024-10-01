<?php

namespace Desino\CrudEngine;

use Illuminate\Support\ServiceProvider;
use Desino\CrudEngine\Commands\MakeCrudEngineCommand;

class CrudEngineServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            MakeCrudEngineCommand::class,
        ]);
    }

    public function boot()
    {
        // Boot package-specific resources if needed.
    }
}
