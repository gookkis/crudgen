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

    function convertToDashes($input)
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('-', $ret);
    }

    protected function getStub($type)
    {
        return file_get_contents("vendor/gookkis/crudgen/resources/stubs/$type.stub");
    }

    protected function view($modelName)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelDashes}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $this->convertToDashes($modelName)
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

        $public_col = '';
        $rules_col = '';
        $create_col = '';
        $update_col = '';
        $func_update_col = '';
        $null_col = '';
        foreach ($cols as $col) {
            $public_col .= '
            public $' . $col . ';';
            $rules_col .= '
            //\'' . $col . '\' => \'required\',';
            $create_col .= '
            \'' . $col . '\' => $this->' . $col . ',';
            $update_col .= '
            $' . strtolower($modelName) . '->' . $col . ' = $this->' . $col . ';';
            $func_update_col .= '
            $this->' . $col . ' = $' . strtolower($modelName) . '->' . $col . ';';
            $null_col .= '
            $this->' . $col . ' = null;';
        }



        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{public_col}}',
                '{{rules_col}}',
                '{{create_col}}',
                '{{update_col}}',
                '{{func_update_col}}',
                '{{null_col}}',
                '{{modelDashes}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $public_col,
                $rules_col,
                $create_col,
                $update_col,
                $func_update_col,
                $null_col,
                $this->convertToDashes($modelName)
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
                '{{modelDashes}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $this->convertToDashes($modelName)
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
                '{{modelDashes}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $cols[0],
                $this->convertToDashes($modelName)
            ],
            $this->getStub('controller-table')
        );
        if (!file_exists(app_path('/Http/Livewire/' . $modelName))) {
            mkdir(app_path('/Http/Livewire/' . $modelName, 0755));
        }
        file_put_contents(app_path("/Http/Livewire/{$modelName}/{$modelName}Table.php"), $controllerTemplate);

        $this->info("{$modelName}Table.php Created");
    }

    protected function view_form($modelName, $cols)
    {

        $input_col = '';

        foreach ($cols as $col) {
            $input_col .= '
            <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">' . ucwords(strtolower($col)) . '</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control  @error(\'' . $col . '\') is-invalid @enderror"
                                wire:model="' . $col . '" placeholder="' . ucwords(strtolower($col)) . '" required>
                            @error(\'' . $col . '\')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
            ';
        }

        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{input_col}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $input_col
            ],
            $this->getStub('view-form')
        );
        if (!file_exists(resource_path('views/livewire/' . $this->convertToDashes($modelName)))) {
            mkdir(resource_path('views/livewire/' . $this->convertToDashes($modelName), 0755));
        }
        file_put_contents(resource_path("views/livewire/" . $this->convertToDashes($modelName) . "/" . $this->convertToDashes($modelName) . "-form.blade.php"), $controllerTemplate);

        $this->info($this->convertToDashes($modelName) . "-form.blade.php Created");
    }

    protected function view_index($modelName, $cols)
    {
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelDashes}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $this->convertToDashes($modelName)
            ],
            $this->getStub('view-index')
        );
        if (!file_exists(resource_path('views/livewire/' . $this->convertToDashes($modelName)))) {
            mkdir(resource_path('views/livewire/' . $this->convertToDashes($modelName), 0755));
        }
        file_put_contents(resource_path("views/livewire/" . $this->convertToDashes($modelName) . "/" . $this->convertToDashes($modelName) . "-index.blade.php"), $controllerTemplate);

        $this->info($this->convertToDashes($modelName) . "-index.blade.php Created");
    }



    protected function view_table($modelName, $cols)
    {

        $th_col = '';
        $td_col = '';
        foreach ($cols as $col) {
            $th_col .=  '
            <th wire:click="sortBy(\'' . $col . '\')" style="cursor: pointer;">
            ' . ucwords(strtolower($col)) . '
            @include(\'livewire.partials._sort-icon\', [
                \'field\' => \'' . $col . '\',
            ])</th>';

            $td_col .= '
            <td>{{ $item->' . $col . ' }}</td>';
        }
        $controllerTemplate = str_replace(
            [
                '{{modelName}}',
                '{{modelNameSingularLowerCase}}',
                '{{th_col}}',
                '{{td_col}}',
                '{{cols1}}'
            ],
            [
                $modelName,
                strtolower($modelName),
                $th_col,
                $td_col,
                $cols[0]
            ],
            $this->getStub('view-table')
        );
        if (!file_exists(resource_path('views/livewire/' . $this->convertToDashes($modelName)))) {
            mkdir(resource_path('views/livewire/' . $this->convertToDashes($modelName), 0755));
        }
        file_put_contents(resource_path("views/livewire/" . $this->convertToDashes($modelName) . "/" . $this->convertToDashes($modelName) . "-table.blade.php"), $controllerTemplate);

        $this->info($this->convertToDashes($modelName) . "-table.blade.php Created");
    }
}
