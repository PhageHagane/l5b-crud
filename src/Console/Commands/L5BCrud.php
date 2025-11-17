<?php

namespace pqrs\L5BCrud\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;

use Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class L5BCrud extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'l5b:crud {name} {--m|migrate} {--f|field=title} {--frontend} {--force}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Create a complete CRUD structure for Laravel 5 Boilerplate Backend';

    public $doforce;
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

        // Transform l5b:crud command parameter to singular lowercase
        $name = strtolower(Str::snake(Str::singular($this->argument('name'))));

        // Create Model "Name.php"
        $this->model( $name, Str::studly($name), 'make-model.stub' );

        // Create Attribute Trait "NameAttribute.php"
        $this->attribute( $name, Str::studly($name) . "Attribute", 'make-attribute.stub' );

        // Create Controller "NameController.php"
        $this->controller( $name, Str::studly($name) . "Controller", 'make-controller.stub' );

        // Create Repository "NameRepository.php"
        $this->repository( $name, Str::studly($name) . "Repository", 'make-repository.stub' );

        // Create Validation Request "ManageNameRequest.php"
        // Create Validation Request "StoreNameRequest.php"
        // Create Validation Request "UpdateNameRequest.php"
        $this->request( $name, "Manage" . Str::studly($name) . "Request", 'make-manage-request.stub' );
        $this->request( $name, "Store"  . Str::studly($name) . "Request", 'make-store-request.stub' );
        $this->request( $name, "Update" . Str::studly($name) . "Request", 'make-update-request.stub' );

        // Create Event "Events/Backend/Example/ExampleCreated.php"
        // Create Event "Events/Backend/Example/ExampleUpdated.php"
        // Create Event "Events/Backend/Example/ExampleDeleted.php"
            $this->frontend_event( $name, Str::studly($name) . "Created", 'make-frontend-event-created.stub' );
            $this->frontend_event( $name, Str::studly($name) . "Updated", 'make-frontend-event-updated.stub' );
            $this->frontend_event( $name, Str::studly($name) . "Deleted", 'make-frontend-event-deleted.stub' );

        // Create Listener "Listeners/Backend/Example/ExampleEventListener.php"
        $this->listener( $name, Str::studly($name) . "EventListener", 'make-listener.stub' );

        // Create Migration "YYYY_MM_DD_HHMMSS_create_names_table.php"
        $this->migration( $name, date('Y_m_d_His_') . "create_" . Str::plural($name)."_table", 'make-migration.stub' );

        // Create Routes "names.php"
        $this->routes( $name, Str::plural($name), 'make-routes.stub' );

        // Create Breadcrumbs "names.php"
        $this->breadcrumbs( $name, $name, 'make-breadcrumbs.stub' );

        // Create View "name/index.blade.php"
        // Create View "example/create.blade.php"
        // Create View "example/edit.blade.php"
        // Create View "example/show.blade.php"
        // Create View "example/deleted.blade.php"
        // Create View "example/includes/breadcrumb-links.blade.php"
        // Create View "example/includes/header-buttons.blade.php"
        // Create View "example/includes/sidebar-examples.blade.php"
        $this->view( $name, 'index', 'make-views-index.stub' );
        $this->view( $name, 'create', 'make-views-create.stub' );
        $this->view( $name, 'edit', 'make-views-edit.stub' );
        $this->view( $name, 'show', 'make-views-show.stub' );
        $this->view( $name, 'deleted', 'make-views-deleted.stub' );
        $this->view( $name, '/includes/breadcrumb-links', 'make-views-breadcrumb-links.stub' );
        $this->view( $name, '/includes/header-buttons', 'make-views-header-buttons.stub' );
        $this->view( $name, '/includes/sidebar-'. Str::plural($name), 'make-views-sidebar.stub' );

        $this->label($name,$name,'make-backend-labels.stub');

        if($this->option('frontend'))
        {
            $this->frontend_controller( $name, Str::studly($name) . "Controller", 'make-frontend-controller.stub' );
            $this->frontend_repository( $name, Str::studly($name) . "Repository", 'make-frontend-repository.stub' );

            $this->frontend_request( $name, "Manage" . Str::studly($name) . "Request", 'make-frontend-manage-request.stub' );
            $this->frontend_request( $name, "Store"  . Str::studly($name) . "Request", 'make-frontend-store-request.stub' );
            $this->frontend_request( $name, "Update" . Str::studly($name) . "Request", 'make-frontend-update-request.stub' );

            $this->frontend_event( $name, Str::studly($name) . "Created", 'make-frontend-event-created.stub' );
            $this->frontend_event( $name, Str::studly($name) . "Updated", 'make-frontend-event-updated.stub' );
            $this->frontend_event( $name, Str::studly($name) . "Deleted", 'make-frontend-event-deleted.stub' );

            $this->frontend_listener( $name, Str::studly($name) . "EventListener", 'make-frontend-listener.stub' );

            $this->frontend_routes( $name, Str::plural($name), 'make-frontend-routes.stub' );

            $this->frontend_view( $name, 'index', 'make-frontend-views-index.stub' );
            $this->frontend_view( $name, 'create', 'make-frontend-views-create.stub' );
            $this->frontend_view( $name, 'edit', 'make-frontend-views-edit.stub' );
            $this->frontend_view( $name, 'show', 'make-frontend-views-show.stub' );
            $this->frontend_view( $name, 'deleted', 'make-frontend-views-deleted.stub' );
            $this->frontend_view( $name, '/includes/header-buttons', 'make-frontend-views-header-buttons.stub' );

            $this->frontend_label($name,$name,'make-frontend-labels.stub');
        }

    }

    protected function model($key, $name, $stub)
    {
        $stubParams = [
            'name'              => $name,
            'stub'              => __DIR__ . '/Stubs/' . $stub,
            'namespace'         => '\Models',
            'attribute'         => Str::studly($key) . "Attribute",
            'field'             => $this->option('field'),
            'model'             => Str::studly($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Model ' . $stubParams['name'] . Artisan::output());
    }

    protected function event($key, $name, $stub)
    {
        $stubParams = [
            'name'              => $name,
            'stub'              => __DIR__ . '/Stubs/' . $stub,
            'namespace'         => '\Events\Backend\\' . Str::studly($key),
            'event'             => Str::studly($key),
            'model'             => Str::studly($key),
            'table'             =>  Str::plural($key),
            'variable'          => Str::camel($key),
            'field'             => $this->option('field'),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Event ' . $stubParams['name'] . Artisan::output());
    }

    protected function listener($key, $name, $stub)
    {
        $stubParams = [
            'name'              => $name,
            'stub'              => __DIR__ . '/Stubs/' . $stub,
            'namespace'         => '\Listeners\Backend\\' . Str::studly($key),
            'event'             => Str::studly($key),
            'field'             => $this->option('field'),
            'model'             => Str::studly($key),
            'table'             => $key,
            'variable'          => Str::camel($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Listener ' . $stubParams['name'] . Artisan::output());
    }

    protected function attribute($key, $name, $stub)
    {
        $stubParams = [
            'name'              => $name,
            'stub'              => __DIR__ . '/Stubs/' . $stub,
            'namespace'         => '\Models\Traits\Attribute',
            'attribute'         => Str::studly($key) . "Attribute",
            'route'             => Str::plural($key),
            'label'             => Str::plural($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Attribute ' . $stubParams['name'] . Artisan::output());
    }

    protected function controller($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\Http\Controllers\Backend',
            'array'                 => Str::camel(Str::plural($key)),
            'controller'            => Str::studly($key) . "Controller",
            'field'                 => $this->option('field'),
            'label'                 => Str::plural($key),
            'model'                 => Str::studly($key),
            'repository'            => Str::studly($key) . "Repository",
            'repositoryVariable'    => $key . "Repository",
            'request'               => Str::studly($key) . "Request",
            'route'                 => Str::plural($key),
            'variable'              => Str::camel($key),
            'view'                  => $key,
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Controller ' . $stubParams['name'] . Artisan::output());
    }

    protected function repository($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'field'                 => $this->option('field'),
            'namespace'             => '\Repositories\Backend',
            'model'                 => Str::studly($key),
            'repository'            => Str::studly($key) . "Repository",
            'variable'              => $key,
            'label'                 => Str::plural($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Repository ' . $stubParams['name'] . Artisan::output());
    }

    protected function request($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'field'                 => $this->option('field'),
            'namespace'             => '\Http\Requests\Backend\\' . Str::studly($key),
            'model'                 => Str::studly($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Request ' . $stubParams['name'] . Artisan::output());
    }

    protected function migration($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'field'                 => $this->option('field'),
            'namespace'             => '\..\database\migrations',
            'model'                 => Str::studly($key),
            'class'                 => "Create" . Str::studly(Str::plural($key)) . "Table",
            'table'                 => Str::plural($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        // If no migration with name "*create_names_table.php" exists then create it
        if (!glob(database_path() . "/migrations/*create_" . Str::plural($key) . "_table.php")) {
            Artisan::call('l5b:stub', $stubParams);
            $this->line('Migration ' . $stubParams['name'] . Artisan::output());
        } else {
            $this->line('A migration file for the table ' . Str::plural($key) . " already exists!\n");
        }

        // If option -m|--migrate is true then migrate the table
        if ($this->option('migrate')) {
            Artisan::call('migrate');
            $this->line('Migrating table ' . $stubParams['name'] . "\n");
        }
    }

    protected function routes($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\routes\backend',
            'controller'            => Str::studly($key) . "Controller",
            'model'                 => Str::studly($key),
            'route'                 => Str::plural($key),
            'variable'              => $key,
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Routes ' . $stubParams['name'] . Artisan::output());
    }

    protected function breadcrumbs($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\routes\breadcrumbs\backend',
            'route'                 => Str::plural($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Breadcrumbs ' . $stubParams['name'] . Artisan::output());

        // Include breadcrumb file in backend.php
        $require_breadcrumb = "require __DIR__.'/$name.php';";

        $backend_path = base_path("routes/breadcrumbs/backend/backend.php");

        $breadcrumbs = explode("\n", file_get_contents($backend_path));

        if(!in_array($require_breadcrumb, $breadcrumbs)){
            $myfile = file_put_contents($backend_path, PHP_EOL . $require_breadcrumb, FILE_APPEND | LOCK_EX);
        }
    }

    protected function view($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name . ".blade",
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\resources\views\backend' . '\\' . $key,
            'label'                 => Str::plural($key),
            'array'                 => Str::camel(Str::plural($key)),
            'field'                 => $this->option('field'),
            'route'                 => Str::plural($key),
            'variable'              => Str::camel($key),
            'view'                  => $key,
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('View ' . $stubParams['name'] . Artisan::output());
    }

    protected function label($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => 'backend_' . Str::plural($name),
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\resources\lang\en\\',
            'label'                 => Str::plural($key),
            'array'                 => Str::camel(Str::plural($key)),
            'field'                 => $this->option('field'),
            'route'                 => Str::plural($key),
            'variable'              => Str::camel($key),
            'view'                  => $key,
            'model'                 => Str::studly($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Label ' . $stubParams['name'] . Artisan::output());
    }

    /*
     *  Frontend
     */
    protected function frontend_controller($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\Http\Controllers\Frontend',
            'array'                 => Str::camel(Str::plural($key)),
            'controller'            => Str::studly($key) . "Controller",
            'field'                 => $this->option('field'),
            'label'                 => Str::plural($key),
            'model'                 => Str::studly($key),
            'repository'            => Str::studly($key) . "Repository",
            'repositoryVariable'    => $key . "Repository",
            'request'               => Str::studly($key) . "Request",
            'route'                 => Str::plural($key),
            'variable'              => Str::camel($key),
            'view'                  => $key,
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Controller ' . $stubParams['name'] . Artisan::output());
    }

    protected function frontend_event($key, $name, $stub)
    {
        $stubParams = [
            'name'              => $name,
            'stub'              => __DIR__ . '/Stubs/' . $stub,
            'namespace'         => '\Events\Frontend\\' . Str::studly($key),
            'event'             => Str::studly($key),
            'model'             => Str::studly($key),
            'table'             =>  Str::plural($key),
            'variable'          => Str::camel($key),
            'field'             => $this->option('field'),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];


        Artisan::call('l5b:stub', $stubParams);
        $this->line('Event ' . $stubParams['name'] . Artisan::output());
    }

    protected function frontend_listener($key, $name, $stub)
    {
        $stubParams = [
            'name'              => $name,
            'stub'              => __DIR__ . '/Stubs/' . $stub,
            'namespace'         => '\Listeners\Frontend\\' . Str::studly($key),
            'event'             => Str::studly($key),
            'field'             => $this->option('field'),
            'model'             => Str::studly($key),
            'table'             => $key,
            'variable'          => Str::camel($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Listener ' . $stubParams['name'] . Artisan::output());
    }

    protected function frontend_repository($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'field'                 => $this->option('field'),
            'namespace'             => '\Repositories\Frontend\\' . Str::studly($key),
            'model'                 => Str::studly($key),
            'repository'            => Str::studly($key) . "Repository",
            'variable'              => $key,
            'label'                 => Str::plural($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Repository ' . $stubParams['name'] . Artisan::output());
    }

    protected function frontend_request($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name,
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'field'                 => $this->option('field'),
            'namespace'             => '\Http\Requests\Frontend\\' . Str::studly($key),
            'model'                 => Str::studly($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Request ' . $stubParams['name'] . Artisan::output());
    }
    protected function frontend_routes($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => str_replace('_','', $name),
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\routes\frontend',
            'controller'            => Str::studly($key) . "Controller",
            'model'                 => Str::studly($key),
            'route'                 => Str::plural($key),
            'variable'              => $key,
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Routes ' . $stubParams['name'] . Artisan::output());
    }

    protected function frontend_view($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => $name . ".blade",
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\resources\views\frontend' . '\\' . $key,
            'label'                 => Str::plural($key),
            'array'                 => Str::camel(Str::plural($key)),
            'field'                 => $this->option('field'),
            'route'                 => Str::plural($key),
            'variable'              => Str::camel($key),
            'view'                  => $key,
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('View ' . $stubParams['name'] . Artisan::output());
    }

    protected function frontend_label($key, $name, $stub)
    {
        $stubParams = [
            'name'                  => 'frontend_' . Str::plural($name),
            'stub'                  => __DIR__ . '/Stubs/' . $stub,
            'namespace'             => '\..\resources\lang\en\\',
            'label'                 => Str::plural($key),
            'array'                 => Str::camel(Str::plural($key)),
            'field'                 => $this->option('field'),
            'route'                 => Str::plural($key),
            'variable'              => Str::camel($key),
            'view'                  => $key,
            'model'                 => Str::studly($key),
            '--force'           => $this->hasOption('force') ? $this->option('force') : false,
        ];

        Artisan::call('l5b:stub', $stubParams);
        $this->line('Label ' . $stubParams['name'] . Artisan::output());
    }
}
