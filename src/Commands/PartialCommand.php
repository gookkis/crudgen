<?php

namespace Gookkis\CrudGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PartialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crudgen:partial';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Generate Partial Livewire Components!';
    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->createPartials();
        //$this->controller($name);
        //$this->model($name);
        //create api route
        // File::append(
        //     base_path('routes/api.php'),
        //     "Route::post('" . str_plural(strtolower($name)) . "/create', '{$name}Controller@create');
        //    Route::post('" . str_plural(strtolower($name)) . "/show', '{$name}Controller@show');
        //    Route::post('" . str_plural(strtolower($name)) . "/update/{id}', '{$name}Controller@update');
        //    Route::delete('" . str_plural(strtolower($name)) . "/delete/{id}', '{$name}Controller@delete');"
        // );
    }

    protected function createPartials()
    {
        $sort = resource_path('views/livewire/partials/_sort-icon.blade.php');
        $spinner = resource_path('views/livewire/partials/_spinner.blade.php');
        if (!file_exists($sort)) {
            mkdir(resource_path('views/livewire/partials', 0700));
            file_put_contents(resource_path('views/livewire/partials/_sort-icon.blade.php'), $this->getStub('_sort-icon'));
        }
        if (!file_exists($spinner)) {
            file_put_contents(resource_path('views/livewire/partials/_spinner.blade.php'), $this->getStub('_spinner'));
        }
    }

    protected function getStub($type)
    {
        return file_get_contents("packages/gookkis/crudgen/resources/stubs/$type.stub");
    }

    // protected function controller($name)
    // {
    //     $controllerTemplate = str_replace(
    //         [
    //             '{{modelName}}',
    //             '{{modelNamePlural}}',
    //             '{{modelNameSingular}}'
    //         ],
    //         [
    //             $name,
    //             strtolower(str_plural($name)),
    //             strtolower($name)
    //         ],
    //         $this->getStub('Controller')
    //     );
    //     file_put_contents(app_path("/Http/Controllers/{$name}Controller.php"), $controllerTemplate);
    // }

    // protected function model($name)
    // {
    //     $modelTemplate = str_replace(
    //         ['{{modelName}}', '{{modelNamePlural}}'],
    //         [$name, strtolower(str_plural($name))],
    //         $this->getStub('Model')
    //     );
    //     file_put_contents(app_path("/{$name}.php"), $modelTemplate);
    // }
}
