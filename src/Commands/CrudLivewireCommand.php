<?php

namespace Gookkis\CrudGen\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CrudLivewireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crudgen:livewire {table} {cols}';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Generate CRUD with Livewire Component!';
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
        $modelName = $this->argument('table');
        $cols = explode(',', $this->argument('cols'));
        $this->controller($modelName, $cols);
        $this->view($modelName, $cols);
        $this->controller_form($modelName, $cols);
        $this->controller_index($modelName, $cols);
        $this->controller_table($modelName, $cols);
        $this->view_form($modelName, $cols);
        $this->view_index($modelName, $cols);
        $this->view_table($modelName, $cols);

        File::append(
            base_path('routes/web.php'),
            "Route::get('" . strtolower($modelName) . "', [{$modelName}Controller::class, 'index'])->name('" . strtolower($modelName) . "');"
        );
    }

    protected function getStub($type)
    {
        return file_get_contents("packages/gookkis/crudgen/resources/stubs/$type.stub");
    }

    protected function view($modelName)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $modelName,
                strtolower($modelName),
            ],
            $this->getStub('view')
        );
        if (!file_exists(resource_path('views/' . strtolower($modelName)))) {
            mkdir(resource_path('views/' . strtolower($modelName), 0755));
        }
        file_put_contents(resource_path("views/" . strtolower($modelName) . "/index.blade.php"), $controllerTemplate);

        $this->info(strtolower($modelName) . "/index.blade.php Created");
    }

    protected function controller($modelName)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
            ],
            [
                $modelName,
                strtolower($modelName),
            ],
            $this->getStub('controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$modelName}Controller.php"), $controllerTemplate);

        $this->info("{$modelName}Controller.php Created");
    }

    protected function controller_form($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{cols1}}',
                '{{cols2}}',
                '{{cols3}}',
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $cols[1],
                $cols[2]
            ],
            $this->getStub('controller-form')
        );
        if (!file_exists(app_path('/Http/Livewire/' . $modelName))) {
            mkdir(app_path('/Http/Livewire/' . $modelName, 0755));
        }
        file_put_contents(app_path("/Http/Livewire/{$modelName}/{$modelName}Form.php"), $controllerTemplate);

        $this->info("{$modelName}Form.php Created");
    }

    protected function controller_index($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{cols1}}',
                '{{cols2}}',
                '{{cols3}}',
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $cols[1],
                $cols[2]
            ],
            $this->getStub('controller-index')
        );
        if (!file_exists(app_path('/Http/Livewire/' . $modelName))) {
            mkdir(app_path('/Http/Livewire/' . $modelName, 0755));
        }
        file_put_contents(app_path("/Http/Livewire/{$modelName}/{$modelName}Index.php"), $controllerTemplate);

        $this->info("{$modelName}Index.php Created");
    }
    protected function controller_table($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{cols1}}',
                '{{cols2}}',
                '{{cols3}}',
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $cols[1],
                $cols[2]
            ],
            $this->getStub('controller-table')
        );
        if (!file_exists(app_path('/Http/Livewire/' . $modelName))) {
            mkdir(app_path('/Http/Livewire/' . $modelName, 0755));
        }
        file_put_contents(app_path("/Http/Livewire/{$modelName}/{$modelName}Table.php"), $controllerTemplate);

        $this->info("{$modelName}Table.php Created");
    }

    protected function view_index($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{cols1}}',
                '{{cols2}}',
                '{{cols3}}',
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $cols[1],
                $cols[2]
            ],
            $this->getStub('view-index')
        );
        if (!file_exists(resource_path('views/livewire/' . strtolower($modelName)))) {
            mkdir(resource_path('views/livewire/' . strtolower($modelName), 0755));
        }
        file_put_contents(resource_path("views/livewire/" . strtolower($modelName) . "/" . strtolower($modelName) . "-index.blade.php"), $controllerTemplate);

        $this->info(strtolower($modelName) . "-index.blade.php Created");
    }

    protected function view_form($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{cols1}}',
                '{{cols2}}',
                '{{cols3}}',
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $cols[1],
                $cols[2]
            ],
            $this->getStub('view-form')
        );
        if (!file_exists(resource_path('views/livewire/' . strtolower($modelName)))) {
            mkdir(resource_path('views/livewire/' . strtolower($modelName), 0755));
        }
        file_put_contents(resource_path("views/livewire/" . strtolower($modelName) . "/" . strtolower($modelName) . "-form.blade.php"), $controllerTemplate);

        $this->info(strtolower($modelName) . "-form.blade.php Created");
    }

    protected function view_table($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{cols1}}',
                '{{cols2}}',
                '{{cols3}}',
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $cols[1],
                $cols[2]
            ],
            $this->getStub('view-table')
        );
        if (!file_exists(resource_path('views/livewire/' . strtolower($modelName)))) {
            mkdir(resource_path('views/livewire/' . strtolower($modelName), 0755));
        }
        file_put_contents(resource_path("views/livewire/" . strtolower($modelName) . "/" . strtolower($modelName) . "-table.blade.php"), $controllerTemplate);

        $this->info(strtolower($modelName) . "-table.blade.php Created");
    }
}
