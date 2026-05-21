<?php

namespace Desino\CrudEngine\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudEngineCommand extends Command
{
    protected $signature         = 'make:crud-engine {name : The entity name, e.g. Category or Product} {--force : Overwrite existing generated files}';

    protected $description       = 'Generate CRUD scaffold (model, controller, migration, views, routes, translations).';

    protected string $entityName = '';

    protected string $singularName                = '';
    protected string $lowerCaseSingularName       = '';
    protected string $upperCaseSingularName       = '';
    protected string $snakeCaseSingularName       = '';
    protected string $camelCaseSingularName       = '';
    protected string $capitalCaseSingularName     = '';
    protected string $headlineSingularName        = '';
    protected string $capitalHeadlineSingularName = '';

    protected string $pluralName                  = '';
    protected string $lowerCasePluralName         = '';
    protected string $upperCasePluralName         = '';
    protected string $snakeCasePluralName         = '';
    protected string $camelCasePluralName         = '';
    protected string $capitalCasePluralName       = '';
    protected string $headlinePluralName          = '';
    protected string $capitalHeadlinePluralName   = '';

    /*route path -> snakeCasePluralName
    name -> camelCasePluralName

    plurals in language -> snakeCasePluralName
    singular in language -> snakeCaseSingularName

    Model class name -> capitalCaseSingularName
    single modal variable name => ${{camelCaseSingularName}}
    list of records variable name => ${{camelCasePluralName}}

    view folder => snakeCasePluralName
    */

    /**
     * Handles the command execution to generate the CRUD scaffold for the specified entity.
     *
     * @return integer
     */
    public function handle(): int
    {
        $this->entityName = $this->argument('name');
        $this->configureNames();

        $this->createModel();
        $this->createController();
        $this->createMigration();
        $this->createViews();
        $this->updateRoutes();
        $this->updateTranslations();

        $this->info('CRUD scaffold for '.$this->capitalCaseSingularName.' is ready.');
        $this->line('Model:      app/Models/'.$this->capitalCaseSingularName.'.php');
        $this->line('Controller: app/Http/Controllers/'.$this->capitalCaseSingularName.'Controller.php');
        $this->line('Views:      resources/views/'.$this->snakeCasePluralName.'/');
        $this->line('Routes:     appended to routes/web.php');

        return self::SUCCESS;
    }

    /**
     * Configures the various name formats based on the provided entity name.
     *
     * @return void
     */
    protected function configureNames(): void
    {
        $this->snakeCaseSingularName       = Str::snake(Str::singular($this->entityName));//master_category
        $this->camelCaseSingularName       = Str::camel($this->snakeCaseSingularName);//masterCategory
        $this->singularName                = Str::singular($this->camelCaseSingularName);//masterCategory
        $this->lowerCaseSingularName       = Str::lower($this->camelCaseSingularName);//mastercategory
        $this->upperCaseSingularName       = Str::upper($this->camelCaseSingularName);//MASTERCATEGORY
        $this->capitalCaseSingularName     = Str::studly($this->camelCaseSingularName);//MasterCategory
        $this->headlineSingularName        = Str::headline($this->snakeCaseSingularName);//Master Category
        $this->capitalHeadlineSingularName = Str::upper(Str::headline($this->headlineSingularName));//MASTER CATEGORY

        $this->pluralName                  = Str::plural($this->singularName);//masterCategories
        $this->camelCasePluralName         = Str::camel($this->pluralName);//masterCategories
        $this->snakeCasePluralName         = Str::snake($this->pluralName);//master_categories
        $this->lowerCasePluralName         = Str::lower($this->pluralName);//mastercategories
        $this->upperCasePluralName         = Str::upper($this->pluralName);//MASTERCATEGORIES
        $this->capitalCasePluralName       = Str::studly($this->camelCasePluralName);//MasterCategories
        $this->headlinePluralName          = Str::headline($this->snakeCasePluralName);//Master Categories
        $this->capitalHeadlinePluralName   = Str::upper(Str::headline($this->headlinePluralName));//MASTER CATEGORIES
    }

    /**
     * Gets the placeholders for the names.
     *
     * @return array
     */
    private function getNamesPlaceholders(): array
    {
        return [
            '{{snakeCaseSingularName}}',
            '{{camelCaseSingularName}}',
            '{{singularName}}',
            '{{lowerCaseSingularName}}',
            '{{upperCaseSingularName}}',
            '{{capitalCaseSingularName}}',
            '{{headlineSingularName}}',
            '{{capitalHeadlineSingularName}}',
            '{{pluralName}}',
            '{{camelCasePluralName}}',
            '{{snakeCasePluralName}}',
            '{{lowerCasePluralName}}',
            '{{upperCasePluralName}}',
            '{{capitalCasePluralName}}',
            '{{headlinePluralName}}',
            '{{capitalHeadlinePluralName}}',
        ];
    }

    /**
     * Gets the corresponding values for the name placeholders.
     *
     * @return array
     */
    private function getNamesPlaceholderValues(): array
    {
        return [
            $this->snakeCaseSingularName,
            $this->camelCaseSingularName,
            $this->singularName,
            $this->lowerCaseSingularName,
            $this->upperCaseSingularName,
            $this->capitalCaseSingularName,
            $this->headlineSingularName,
            $this->capitalHeadlineSingularName,
            $this->pluralName,
            $this->camelCasePluralName,
            $this->snakeCasePluralName,
            $this->lowerCasePluralName,
            $this->upperCasePluralName,
            $this->capitalCasePluralName,
            $this->headlinePluralName,
            $this->capitalHeadlinePluralName,
        ];
    }

    /**
     * Renders the stub file with the appropriate placeholders replaced.
     *
     * @param string $type The type of stub to render (e.g., 'Controller', 'Model', 'Migration', 'Views/index', etc.)
     * 
     * @return string
     */
    protected function renderStub(string $type): string
    {
        return str_replace(
            $this->getNamesPlaceholders(),
            $this->getNamesPlaceholderValues(),
            $this->getStub($type)
        );
    }

    /**
     * Creates the controller file for the CRUD operations.
     *
     * @return void
     */
    protected function createController(): void
    {
        $this->writeApplicationFile(path: app_path('Http/Controllers/'.$this->capitalCaseSingularName.'Controller.php'), contents: $this->renderStub('Controller'), elementType: 'Controller');
    }

    /**
     * Creates the model file for the CRUD operations.
     *
     * @return void
     */
    protected function createModel(): void
    {
        $this->writeApplicationFile(path: app_path('Models/'.$this->capitalCaseSingularName.'.php'), contents: $this->renderStub('Model'), elementType: 'Model');
    }

    /**
     * Creates the migration file for the CRUD operations.
     *
     * @return void
     */
    protected function createMigration(): void
    {
        $migrationFile = date('Y_m_d_His').'_create_'.$this->snakeCasePluralName.'_table.php';
        $path = database_path('migrations/'.$migrationFile);

        if (! $this->option('force') && File::exists($path)) {
            $this->error("Migration already exists: {$migrationFile} (use --force to overwrite).");

            return;
        }

        File::put($path, $this->renderStub('Migration'));
        $this->info("Migration created: database/migrations/{$migrationFile}");
    }

    /**
     * Creates the view files for the CRUD operations.
     *
     * @return void
     */
    protected function createViews(): void
    {
        $viewPath = resource_path('views/'.$this->snakeCasePluralName);
        if (! File::isDirectory($viewPath)) {
            File::makeDirectory($viewPath, 0755, true);
        }

        foreach (['index', 'create', 'edit'] as $eachBladeFile) {
            $this->writeApplicationFile(path: $viewPath.'/'.$eachBladeFile.'.blade.php', contents: $this->renderStub("Views/{$eachBladeFile}"), elementType: "View {$eachBladeFile}");
        }
    }

    /**
     * Updates the routes/web.php file with the necessary routes for the CRUD operations.
     *
     * @return void
     */
    protected function updateRoutes(): void
    {
        $routesFile = base_path('routes/web.php');
        $routeMarker = "// Routers :{$this->camelCasePluralName}";
        if (File::exists($routesFile) && str_contains(File::get($routesFile), $routeMarker)) {
            $this->warn("Routes for {$this->camelCasePluralName} already exist in routes/web.php — skipped.");

            return;
        }

        $controllerClassName = "App\\Http\\Controllers\\{$this->capitalCaseSingularName}Controller::class";
        $lines = [
            '',
            $routeMarker,
            "Route::match(['get', 'post'], '{$this->snakeCasePluralName}', [{$controllerClassName}, 'index'])->name('{$this->camelCasePluralName}.index');",
            "Route::match(['get', 'post'], '{$this->snakeCasePluralName}', [{$controllerClassName}, 'create'])->name('{$this->camelCasePluralName}.create');",
            "Route::match(['get', 'post'], '{$this->snakeCasePluralName}', [{$controllerClassName}, 'edit'])->name('{$this->camelCasePluralName}.edit');",
            "Route::post('{$this->snakeCasePluralName}/activate', [{$controllerClassName}, 'activate'])->name('{$this->camelCasePluralName}.activate');",
            "Route::post('{$this->snakeCasePluralName}/deactivate', [{$controllerClassName}, 'deactivate'])->name('{$this->camelCasePluralName}.deactivate');",
            "Route::post('{$this->snakeCasePluralName}/delete', [{$controllerClassName}, 'delete'])->name('{$this->camelCasePluralName}.delete');",
        ];

        File::append($routesFile, implode("\n", $lines)."\n");
        $this->info('Routes appended to routes/web.php');
    }

    /**
     * Updates the lang/en/messages.php file with the necessary translation keys for the CRUD operations.
     *
     * @return void
     */
    protected function updateTranslations(): void
    {
        $translationsFile = base_path('lang/en/messages.php');
        if (! File::exists($translationsFile)) {
            $this->warn("Translation file not found: {$translationsFile} — skipped.");

            return;
        }

        $contents = File::get($translationsFile);
        if (! preg_match('/return\s+\[(.*)\];/s', $contents, $matches)) {
            $this->warn('Could not parse translation file — skipped.');

            return;
        }

        $arrayContent = $matches[1];
        $newTranslationString = '';

        $translations = $this->buildTranslationEntries();
        foreach ($translations as $key => $value) {
            if (! preg_match("/'".preg_quote($key, '/')."'\s*=>/", $arrayContent)) {
                $escapedValue = str_replace("'", "\\'", $value);
                $newTranslationString .= "    '{$key}' => '{$escapedValue}',\n";
            }
        }

        if ($newTranslationString === '') {
            $this->line('Translation keys already present — nothing to add.');

            return;
        }

        $arrayContent = rtrim($arrayContent)."\n\n".$newTranslationString."\n";
        $newContents  = str_replace($matches[1], $arrayContent, $contents);
        File::put($translationsFile, $newContents);
        $this->info('Translation keys merged into lang/en/messages.php');
    }

    /**
     * Builds the translation entries for the generated CRUD operations.
     * 
     * @return array 
     */
    protected function buildTranslationEntries(): array
    {
        return [
            'general_error_'.$this->snakeCaseSingularName.'_not_found'     => 'The requested '.$this->singularName.' was not found.',
            $this->snakeCasePluralName.'_general_error_name_is_not_unique' => 'The specified name is already taken.',
            $this->snakeCaseSingularName.'_form_field_name_text' => 'Name',
            $this->snakeCasePluralName.'_list_page_title'          => $this->capitalCasePluralName,
            $this->snakeCasePluralName.'_list_page_desc'           => 'Description goes here...',
            $this->snakeCasePluralName.'_list_create_btn_text'     => 'Create New',
            $this->snakeCasePluralName.'_list_filter_keyword_text' => 'Search by keyword',
            $this->snakeCasePluralName.'_list_column_name_text'    => 'Name',
            $this->snakeCasePluralName.'_list_column_actions_text' => 'Actions',
            $this->snakeCasePluralName.'_list_action_deactivate_tooltip_text'        => 'Click here to deactivate this '.$this->singularName.'.',
            $this->snakeCasePluralName.'_list_action_activate_tooltip_text'          => 'Click here to activate this '.$this->singularName.'.',
            $this->snakeCasePluralName.'_list_action_delete_btn_tooltip_text'        => 'Click here to delete this '.$this->singularName.'.',
            $this->snakeCasePluralName.'_list_action_edit_btn_tooltip_text'          => 'Click here to edit this '.$this->singularName.'.',
            $this->snakeCasePluralName.'_list_activate_confirm_modal_title'          => $this->singularName.' Activation',
            $this->snakeCasePluralName.'_list_activate_confirm_modal_body_text'      => 'Are you sure you want to activate this '.$this->singularName.'?',
            $this->snakeCasePluralName.'_list_activate_confirm_modal_cnf_btn_text'   => 'Yes, Activate',
            $this->snakeCasePluralName.'_list_deactivate_confirm_modal_title'        => $this->singularName.' Deactivation',
            $this->snakeCasePluralName.'_list_deactivate_confirm_modal_body_text'    => 'Are you sure you want to deactivate this '.$this->singularName.'?',
            $this->snakeCasePluralName.'_list_deactivate_confirm_modal_cnf_btn_text' => 'Yes, Deactivate',
            $this->snakeCasePluralName.'_list_delete_confirm_modal_title'            => $this->singularName.' Deletion',
            $this->snakeCasePluralName.'_list_delete_confirm_modal_body_text'        => 'Are you sure you want to delete this '.$this->singularName.'?',
            $this->snakeCasePluralName.'_list_delete_confirm_modal_cnf_btn_text'     => 'Yes, Delete',
            'enable_'.$this->snakeCaseSingularName.'_error_msg'    => 'An error occurred while enabling the requested '.$this->singularName.'.',
            'enable_'.$this->snakeCaseSingularName.'_success_msg'  => 'The requested '.$this->singularName.' has been enabled.',
            'disable_'.$this->snakeCaseSingularName.'_error_msg'   => 'An error occurred while disabling the requested '.$this->singularName.'.',
            'disable_'.$this->snakeCaseSingularName.'_success_msg' => 'The requested '.$this->singularName.' has been disabled.',
            'delete_'.$this->snakeCaseSingularName.'_error_msg'    => 'An error occurred while deleting the requested '.$this->singularName.'.',
            'delete_'.$this->snakeCaseSingularName.'_success_msg'  => 'The requested '.$this->singularName.' has been deleted.',
            'create_'.$this->snakeCaseSingularName.'_page_title'           => 'Create: '.$this->capitalCaseSingularName,
            'create_'.$this->snakeCaseSingularName.'_page_desc'            => 'Description goes here...',
            'create_'.$this->snakeCaseSingularName.'_form_submit_btn_text' => 'Create',
            'create_'.$this->snakeCaseSingularName.'_error_msg'            => 'An error occurred while creating the requested '.$this->singularName.'.',
            'create_'.$this->snakeCaseSingularName.'_success_msg'          => 'The requested '.$this->singularName.' has been created.',
            'edit_'.$this->snakeCaseSingularName.'_page_title'             => 'Edit '.$this->capitalCaseSingularName.' :{{upperCaseSingularName}}_NAME',
            'edit_'.$this->snakeCaseSingularName.'_page_desc'              => 'Description goes here...',
            'edit_'.$this->snakeCaseSingularName.'_form_submit_btn_text'   => 'Update',
            'edit_'.$this->snakeCaseSingularName.'_error_msg'              => 'An error occurred while updating the requested '.$this->singularName.'.',
            'edit_'.$this->snakeCaseSingularName.'_success_msg'            => 'The requested '.$this->singularName.' has been updated.',
        ];
    }

    /**
     * Writes the given contents to the specified path, with a check for existing files and an option to force overwrite.
     *
     * @param string $path The file path where the contents should be written.
     * @param string $contents The content to write to the file.
     * @param string $elementType A descriptive name of the element being created (e.g., 'Controller', 'Model', 'View index') for logging purposes.
     * 
     * @return void
     */
    protected function writeApplicationFile(string $path, string $contents, string $elementType): void
    {
        if (File::exists($path) && ! $this->option('force')) {
            $this->error("{$elementType} already exists: {$path} (use --force to overwrite).");

            return;
        }

        File::put($path, $contents);
        $this->info("{$elementType} written: {$path}");
    }

    /**
     * Gets the content of the specified stub file.
     *
     * @param string $type The type of stub to retrieve (e.g., 'Controller', 'Model', 'Migration', 'Views/index', etc.)
     * 
     * @return string
     */
    protected function getStub(string $type): string
    {
        $path = __DIR__."/../stubs/{$type}.stub";
        if (! File::exists($path)) {
            throw new \RuntimeException("Stub not found: {$path}");
        }

        return File::get($path);
    }
}
