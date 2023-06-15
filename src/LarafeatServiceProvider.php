<?php

namespace Novadaemon\Larafeat;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Novadaemon\Larafeat\Console\Commands\FeatureMakeCommand;

class LarafeatServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FeatureMakeCommand::class,
            ]);
        }
    }
}
