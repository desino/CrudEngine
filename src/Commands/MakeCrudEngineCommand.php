<?php

namespace Desino\CrudEngine\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudEngineCommand extends Command
{
    protected $signature = 'make:crud-engine {name}';
    protected $description = 'Command to generate full CURD operations for an Entity for e.g. categories, products, users.';

    protected $name         = '';
    protected $singularName = '';
    protected $pluralName   = '';
    protected $lowerCaseSingularName   = '';
    protected $upperCaseSingularName   = '';
    protected $snakeCaseSingularName   = '';
    protected $camelCaseSingularName   = '';
    protected $capitalCaseSingularName = '';

    protected $lowerCasePluralName     = '';
    protected $upperCasePluralName     = '';
    protected $snakeCasePluralName     = '';
    protected $camelCasePluralName     = '';
    protected $capitalCasePluralName   = '';

    /*route path -> snakeCasePluralName
    name -> camelCasePluralName

    plurals in language -> snakeCasePluralName
    singular in language -> snakeCaseSingularName

    Model class name -> CapitalCaseSingularName
    single modal variable name => ${{camelCaseSingularName}}
    list of records variable name => ${{camelCasePluralName}}
    
    view folder => snakeCasePluralName
    */

    public function handle()
    {
        $this->name = $this->argument('name');
        $this->configureNames();

        $this->createModel();
        $this->createController();
        $this->createMigration();
        $this->createViews();
        $this->updateRoutes();

        $this->info('CRUD scaffold for '.$this->name.' is ready.');
    }

    protected function configureNames()
    {
        $this->singularName          = Str::singular($this->name);//Mastercategory
        $this->lowerCaseSingularName = Str::lower($this->singularName);//mastercategory
        $this->upperCaseSingularName = Str::upper($this->singularName);//MASTERCATEGORY
        $this->snakeCaseSingularName = Str::snake($this->singularName);//master_category
        $this->camelCaseSingularName = Str::snake($this->singularName);//masterCategory
        $this->capitalCaseSingularName= Str::ucfirst($this->singularName);//MasterCategory
        $this->pluralName            = Str::plural($this->singularName);//Mastercategories
        $this->lowerCasePluralName   = Str::lower($this->pluralName);//mastercategories
        $this->upperCasePluralName   = Str::upper($this->pluralName);//MASTERCATEGORIES
        $this->snakeCasePluralName   = Str::snake($this->pluralName);//master_categories
        $this->camelCasePluralName   = Str::upper($this->pluralName);//masterCategories
        $this->capitalCasePluralName = Str::ucfirst($this->pluralName);//MasterCategories
    }

    protected function createModel()
    {
        $modelTemplate = str_replace(
            ['{{modelName}}'],
            [$this->capitalCaseSingularName],
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$this->capitalCaseSingularName}.php"), $modelTemplate);
    }

    protected function createController()
    {
        $controllerTemplate = str_replace(
            ['{{modelName}}', '{{singularName}}', '{{lowerCaseSingularName}}', '{{upperCaseSingularName}}', '{{snakeCaseSingularName}}', '{{camelCaseSingularName}}', '{{capitalCaseSingularName}}', '{{pluralName}}', '{{lowerCasePluralName}}', '{{upperCasePluralName}}', '{{camelCasePluralName}}', '{{snakeCasePluralName}}', '{{capitalCasePluralName}}'],
            [$this->capitalCaseSingularName, $this->singularName, $this->lowerCaseSingularName, $this->upperCaseSingularName, $this->snakeCaseSingularName, $this->camelCaseSingularName, $this->capitalCaseSingularName, $this->pluralName, $this->lowerCasePluralName, $this->upperCasePluralName, $this->camelCasePluralName, $this->snakeCasePluralName, $this->capitalCasePluralName],
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$this->capitalCaseSingularName}Controller.php"), $controllerTemplate);
    }

    protected function createMigration()
    {
        $migrationFile     = date('Y_m_d_His').'_create_{$this->snakeCasePluralName}_table.php';
        $migrationTemplate = str_replace(
            ['{{singularName}}', '{{lowerCaseSingularName}}', '{{upperCaseSingularName}}', '{{snakeCaseSingularName}}', '{{camelCaseSingularName}}', '{{capitalCaseSingularName}}', '{{pluralName}}', '{{lowerCasePluralName}}', '{{upperCasePluralName}}', '{{camelCasePluralName}}', '{{snakeCasePluralName}}', '{{capitalCasePluralName}}'],
            [$this->singularName, $this->lowerCaseSingularName, $this->upperCaseSingularName, $this->snakeCaseSingularName, $this->camelCaseSingularName, $this->capitalCaseSingularName, $this->pluralName, $this->lowerCasePluralName, $this->upperCasePluralName, $this->camelCasePluralName, $this->snakeCasePluralName, $this->capitalCasePluralName],
            $this->getStub('Migration')
        );

        file_put_contents(database_path("/migrations/{$migrationFile}"), $migrationTemplate);
    }

    protected function createViews()
    {
        $viewPath = resource_path('views/' . $this->snakeCasePluralName);

        if (!File::isDirectory($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        $views = ['index', 'create', 'edit'];
        foreach ($views as $view) {
            $viewTemplate = str_replace(
                ['{{singularName}}', '{{lowerCaseSingularName}}', '{{upperCaseSingularName}}', '{{snakeCaseSingularName}}', '{{camelCaseSingularName}}', '{{capitalCaseSingularName}}', '{{pluralName}}', '{{lowerCasePluralName}}', '{{upperCasePluralName}}', '{{camelCasePluralName}}', '{{snakeCasePluralName}}', '{{capitalCasePluralName}}'],
                [$this->singularName, $this->lowerCaseSingularName, $this->upperCaseSingularName, $this->snakeCaseSingularName, $this->camelCaseSingularName, $this->capitalCaseSingularName, $this->pluralName, $this->lowerCasePluralName, $this->upperCasePluralName, $this->camelCasePluralName, $this->snakeCasePluralName, $this->capitalCasePluralName],
                $this->getStub("Views/{$view}")
            );

            file_put_contents($viewPath . "/{$view}.blade.php", $viewTemplate);
        }
    }

    protected function updateRoutes()
    {
        $routeTemplate = "\nRoute::get('" . $this->snakeCasePluralName . "', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'index']')->name('" . $this->camelCasePluralName . ".index');\n";
        $routeTemplate .= "\nRoute::post('" . $this->snakeCasePluralName . "', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'index']')->name('" . $this->camelCasePluralName . ".index');\n";
        $routeTemplate .= "\nRoute::get('" . $this->snakeCasePluralName . "/create', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'create']')->name('" . $this->camelCasePluralName . ".create');\n";
        $routeTemplate .= "\nRoute::post('" . $this->snakeCasePluralName . "/create', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'create']')->name('" . $this->camelCasePluralName . ".create');\n";
        $routeTemplate .= "\nRoute::get('" . $this->snakeCasePluralName . "/edit/{id}', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'edit']')->name('" . $this->camelCasePluralName . ".edit');\n";
        $routeTemplate .= "\nRoute::post('" . $this->snakeCasePluralName . "/edit/{id}', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'edit']')->name('" . $this->camelCasePluralName . ".edit');\n";
        $routeTemplate .= "\nRoute::post('" . $this->snakeCasePluralName . "/activate', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'activate']')->name('" . $this->camelCasePluralName . ".activate');\n";
        $routeTemplate .= "\nRoute::post('" . $this->snakeCasePluralName . "/deactivate', [App\Http\Controllers\'" . $this->capitalCaseSingularName . "Controller::class, 'deactivate']')->name('" . $this->camelCasePluralName . ".deactivate');\n";

        file_put_contents(base_path('routes/web.php'), $routeTemplate, FILE_APPEND);
    }

    protected function getStub($type)
    {
        return file_get_contents(__DIR__ . "/../stubs/$type.stub");
    }
}
