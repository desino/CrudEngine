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

    Model class name -> capitalCaseSingularName
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
        $this->updateTranslations();

        $this->info('CRUD scaffold for '.$this->name.' is ready.');
    }

    protected function configureNames()
    {
        $this->singularName            = Str::singular($this->name);//Mastercategory
        $this->lowerCaseSingularName   = Str::lower($this->singularName);//mastercategory
        $this->upperCaseSingularName   = Str::upper($this->singularName);//MASTERCATEGORY
        $this->camelCaseSingularName   = Str::camel($this->singularName);//masterCategory
        $this->snakeCaseSingularName   = Str::snake($this->singularName);//master_category
        $this->capitalCaseSingularName = Str::ucfirst($this->camelCaseSingularName);//MasterCategory

        $this->pluralName              = Str::plural($this->singularName);//Mastercategories
        $this->lowerCasePluralName     = Str::lower($this->pluralName);//mastercategories
        $this->upperCasePluralName     = Str::upper($this->pluralName);//MASTERCATEGORIES
        $this->camelCasePluralName     = Str::camel($this->pluralName);//masterCategories
        $this->snakeCasePluralName     = Str::snake($this->pluralName);//master_categories
        $this->capitalCasePluralName   = Str::ucfirst($this->camelCasePluralName);//MasterCategories
    }

    private function getNamesPlaceholders()
    {
        return [
            '{{singularName}}',
            '{{lowerCaseSingularName}}',
            '{{upperCaseSingularName}}',
            '{{camelCaseSingularName}}',
            '{{snakeCaseSingularName}}',
            '{{capitalCaseSingularName}}',

            '{{pluralName}}',
            '{{lowerCasePluralName}}',
            '{{upperCasePluralName}}',
            '{{camelCasePluralName}}',
            '{{snakeCasePluralName}}',
            '{{capitalCasePluralName}}',
        ];
    }

    private function getNamesPlaceholderValues()
    {
        return [
            $this->singularName,
            $this->lowerCaseSingularName,
            $this->upperCaseSingularName,
            $this->camelCaseSingularName,
            $this->snakeCaseSingularName,
            $this->capitalCaseSingularName,

            $this->pluralName,
            $this->lowerCasePluralName,
            $this->upperCasePluralName,
            $this->camelCasePluralName,
            $this->snakeCasePluralName,
            $this->capitalCasePluralName,
        ];
    }

    protected function createController()
    {
        $controllerTemplate = str_replace(
            $this->getNamesPlaceholders(),
            $this->getNamesPlaceholderValues(),
            $this->getStub('Controller')
        );

        file_put_contents(app_path("/Http/Controllers/{$this->capitalCaseSingularName}Controller.php"), $controllerTemplate);
    }

    protected function createModel()
    {
        $modelTemplate = str_replace(
            $this->getNamesPlaceholders(),
            $this->getNamesPlaceholderValues(),
            $this->getStub('Model')
        );

        file_put_contents(app_path("/Models/{$this->capitalCaseSingularName}.php"), $modelTemplate);
    }

    protected function createMigration()
    {
        $migrationFile     = date('Y_m_d_His')."_create_{$this->snakeCasePluralName}_table.php";
        $migrationTemplate = str_replace(
            $this->getNamesPlaceholders(),
            $this->getNamesPlaceholderValues(),
            $this->getStub('Migration')
        );

        file_put_contents(database_path("migrations/{$migrationFile}"), $migrationTemplate);
    }

    protected function createViews()
    {
        $viewPath = resource_path('views/'.$this->snakeCasePluralName);

        if (!File::isDirectory($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        $views = ['index', 'create', 'edit'];
        foreach ($views as $view) {
            $viewTemplate = str_replace(
                $this->getNamesPlaceholders(),
                $this->getNamesPlaceholderValues(),
                $this->getStub("Views/{$view}")
            );

            file_put_contents($viewPath."/{$view}.blade.php", $viewTemplate);
        }
    }

    protected function updateRoutes()
    {
        $routeTemplate = "\n";
        $routeTemplate .= "Route::get('{$this->snakeCasePluralName}', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'index'])->name('{$this->camelCasePluralName}.index');\n";
        $routeTemplate .= "Route::post('{$this->snakeCasePluralName}', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'index'])->name('{$this->camelCasePluralName}.index');\n";
        $routeTemplate .= "Route::get('{$this->snakeCasePluralName}/create', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'create'])->name('{$this->camelCasePluralName}.create');\n";
        $routeTemplate .= "Route::post('{$this->snakeCasePluralName}/create', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'create'])->name('{$this->camelCasePluralName}.create');\n";
        $routeTemplate .= "Route::get('{$this->snakeCasePluralName}/edit/{id}', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'edit'])->name('{$this->camelCasePluralName}.edit');\n";
        $routeTemplate .= "Route::post('{$this->snakeCasePluralName}/edit/{id}', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'edit'])->name('{$this->camelCasePluralName}.edit');\n";
        $routeTemplate .= "Route::post('{$this->snakeCasePluralName}/activate', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'activate'])->name('{$this->camelCasePluralName}.activate');\n";
        $routeTemplate .= "Route::post('{$this->snakeCasePluralName}/deactivate', [App\Http\Controllers\{$this->capitalCaseSingularName}Controller::class, 'deactivate'])->name('{$this->camelCasePluralName}.deactivate');\n";

        file_put_contents(base_path('routes/web.php'), $routeTemplate, FILE_APPEND);
    }

    protected function updateTranslations()
    {
        $translations = [
            $this->snakeCasePluralName.'_list_page_title'      => $this->capitalCasePluralName,
            $this->snakeCasePluralName.'_list_page_desc'       => 'Description goes here...',
            $this->snakeCasePluralName.'_list_create_btn_text' => 'Create New',
            $this->snakeCaseSingularName.'_form_field_status_option0_text' => 'Deactivated',
            $this->snakeCaseSingularName.'_form_field_status_option1_text' => 'Activated',
            $this->snakeCasePluralName.'_filter_keyword_text' => 'Search by keyword',
            $this->snakeCasePluralName.'_column_name_text'    => 'Name',
            $this->snakeCasePluralName.'_column_actions_text' => 'Actions',
            $this->snakeCasePluralName.'_deactivate_tooltip_'.$this->snakeCaseSingularName.'_text' => 'Are you sure to deactivate this '.$this->singularName.'?',
            $this->snakeCasePluralName.'_tooltip_confirmation_deactivate_btn_text'                 => 'Yes, Deactivate it!',
            $this->snakeCasePluralName.'_activate_tooltip_'.$this->snakeCaseSingularName.'_text'   => 'Are you sure to activate this '.$this->singularName.'?',
            $this->snakeCasePluralName.'_tooltip_confirmation_activate_btn_text'                   => 'Yes, Activate it!',
            $this->snakeCasePluralName.'_tooltip_edit_action_text' => 'Click here adapt this '.$this->singularName.'',

            'enable_'.$this->snakeCaseSingularName.'_error_msg'    => 'An error occurred while enabling the requested '.$this->singularName.'.',
            'enable_'.$this->snakeCaseSingularName.'_success_msg'  => 'The requested '.$this->singularName.' has been enabled.',
            'disable_'.$this->snakeCaseSingularName.'_error_msg'   => 'An error occurred while disabling the requested '.$this->singularName.'.',
            'disable_'.$this->snakeCaseSingularName.'_success_msg' => 'The requested '.$this->singularName.' has been disabled.',

            'general_error_'.$this->snakeCaseSingularName.'_not_found'        => 'The requested '.$this->singularName.' was not found.',
            $this->snakeCasePluralName.'_general_error_name_is_not_unique' => 'The specified name is already taken.',
            
            $this->snakeCaseSingularName.'_form_field_name_text'           => 'Name',

            'create_'.$this->snakeCaseSingularName.'_page_title'           => 'Create: '.$this->capitalCaseSingularName,
            'create_'.$this->snakeCaseSingularName.'_page_desc'            => 'Description goes here...',
            'create_'.$this->snakeCaseSingularName.'_form_submit_btn_text' => 'Create',
            'create_'.$this->snakeCaseSingularName.'_error_msg'            => 'An error occurred while creating the requested '.$this->singularName.'.',
            'create_'.$this->snakeCaseSingularName.'_success_msg'          => 'The requested '.$this->singularName.' has been created.',

            'edit_'.$this->snakeCaseSingularName.'_page_title'           => 'Edit '.$this->capitalCaseSingularName.' :'.$this->upperCaseSingularName.'_NAME',
            'edit_'.$this->snakeCaseSingularName.'_page_desc'            => 'Description goes here...',
            'edit_'.$this->snakeCaseSingularName.'_form_submit_btn_text' => 'Update',
            'edit_'.$this->snakeCaseSingularName.'_error_msg'            => 'An error occurred while updating the requested '.$this->singularName.'.',
            'edit_'.$this->snakeCaseSingularName.'_success_msg'          => 'The requested '.$this->singularName.' has been updated.',
        ];

        $existingTranslations = include base_path('lang/en/messages.php');
        $updatedTranslations = array_merge($existingTranslations, $translations);
        file_put_contents(base_path('lang/en/messages.php'), '<?php return ' . var_export($updatedTranslations, true) . ';');
    }

    protected function getStub($type)
    {
        return file_get_contents(__DIR__."/../stubs/$type.stub");
    }
}
