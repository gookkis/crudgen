<?php

namespace Gookkis\CrudGen;

use Gookkis\CrudGen\Commands\CrudLivewireCommand;
use Gookkis\CrudGen\Commands\PartialCommand;
use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider
{
    private $commandPath = 'command.gookkis.';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // register all the artisan commands
        $this->registerCommand(PartialCommand::class, 'partial');
        $this->registerCommand(CrudLivewireCommand::class, 'livewire');
    }

    /**
     * Register a singleton command
     *
     * @param $class
     * @param $command
     */
    private function registerCommand($class, $command)
    {
        $this->app->singleton($this->commandPath . $command, function ($app) use ($class) {
            return $app[$class];
        });

        $this->commands($this->commandPath . $command);
    }
}
