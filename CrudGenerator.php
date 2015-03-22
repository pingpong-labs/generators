<?php namespace Pingpong\Generators;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Pingpong\Generators\Exceptions\FileAlreadyExistException;
use Pingpong\Generators\Scaffold\ControllerGenerator;

class CrudGenerator {

    use AppNamespaceDetectorTrait;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var Command
     */
    protected $console;

    /**
     * @param array $options
     * @param Command $console
     */
    public function __construct(array $options = [], Command $console = null)
    {
        $this->options = $options;
        $this->console = $console;
    }

    /**
     * @param Command $console
     * @return $this
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * @param $message
     * @return bool
     */
    public function confirm($message)
    {
        if ($this->option('force'))
        {
            return true;
        }

        return $this->console->confirm($message);
    }

    /**
     * @param $command
     * @param array $arguments
     */
    public function call($command, array $arguments)
    {
        $this->console->call($command, $arguments);
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function option($key, $default = null)
    {
        return array_get($this->options, $key, $default);
    }

    /**
     *
     */
    public function generate()
    {
        $this->generateModel();
        $this->generateMigration();
        $this->generateController();
        $this->generateRequest();
        $this->runMigration();
        $this->generateView();
        $this->dumpAutoload();
    }

    /**
     * @return bool
     */
    public function hasPrefix()
    {
        return ! is_null($this->getPrefix());
    }

    /**
     * @return mixed|string
     */
    public function getPrefix()
    {
        return Str::studly($this->option('prefix'));
    }

    /**
     * @return mixed|string
     */
    public function getModel()
    {
        return Str::studly($this->option('entity'));
    }

    /**
     * @return string
     */
    public function getController()
    {
        $class = Str::studly(Str::plural($this->option('entity')));

        if ($prefix = $this->getPrefix())
        {
            $class = $prefix . '/' . $class;
        }

        return $class . 'Controller';
    }

    /**
     * @return string
     */
    public function getMigration()
    {
        return 'create_' . $this->getPluralEntityName() . '_table';
    }

    /**
     * @return string
     */
    public function getPluralEntityName()
    {
        return Str::lower(Str::plural($this->option('entity')));
    }

    /**
     * @return string
     */
    public function getSingularEntityName()
    {
        return Str::lower(Str::singular($this->option('entity')));
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->option('entity');
    }

    /**
     *
     */
    public function generateModel()
    {
        $name = $this->getModel();

        if ($this->confirm("Do you want me to create a new model: {$name} ? [yes|no]"))
        {
            $namespace = str_replace('\\', '', $this->getAppNamespace());

            (new ModelGenerator(app_path(), $name, compact('namespace')))->generate();
        }
    }

    /**
     *
     */
    public function generateMigration()
    {
        $name = $this->getMigration();

        if ($this->confirm("Do you want me to create a new migration: {$name} ? [yes|no]"))
        {
            $path = base_path('database/migrations');

            $fields = $this->option('fields');

            $file = (new MigrationGenerator($path, $name, $fields))->generate();

            $this->console->info("File created : {$file}");
        }
    }

    /**
     *
     */
    public function generateController()
    {
        try
        {
            $name = $this->getController();

            if ($this->confirm("Do you want me to create a new controller: {$name} ? [yes|no]"))
            {
                $generator = new ControllerGenerator([
                    'name' => $name,
                    'entity' => $this->option('entity'),
                    'prefix' => $this->getPrefix(),
                    'model' => $this->getModel()
                ]);

                $generator->generate();
            }
        }
        catch (FileAlreadyExistException $e)
        {
            $this->console->error("Controller already exists!");
        }
    }

    /**
     *
     */
    public function generateView()
    {
        if ($this->confirm('Do you want me to create views?'))
        {
            foreach (['index', 'create', 'edit', 'show', 'form'] as $view)
            {
                $generator = new ViewGenerator([
                    'name' => $view,
                    'entity' => $this->option('entity'),
                    'form' => $this->option('form'),
                    'prefix' => $this->getPrefix(),
                    'fields' => $this->option('fields'),
                    'table-heading' => $this->option('table-heading'),
                    'view-layout' => $this->option('view-layout', 'layouts.master'),
                    'table' => $this->option('table'),
                ]);

                $message = $generator->generate();

                $this->console->info($message);
            }
        }
    }

    /**
     * @param string $type
     * @return string
     */
    public function getRequestName($type = 'Create')
    {
        $entityPrefix = Str::studly(Str::plural($this->option('entity')));

        $class = $entityPrefix . '/' . $type . Str::studly($this->option('entity'));

        if ($this->hasPrefix())
        {
            $class = $this->getPrefix() . '/' . $class;
        }

        return $class . 'Request';
    }

    /**
     *
     */
    public function generateRequest()
    {
        if ($this->confirm("Do you want me to create a new form request class? [yes|no]"))
        {
            $this->call('make:request', [
                'name' => $this->getRequestName('Create')
            ]);

            $this->call('make:request', [
                'name' => $this->getRequestName('Update')
            ]);
        }
    }

    /**
     *
     */
    protected function runMigration()
    {
        if ($this->confirm("Do you want to all migrations now? [yes|no]"))
        {
            $this->call('migrate', []);
        }
    }

    /**
     * Dump autoload.
     *
     * @return void
     */
    protected function dumpAutoload()
    {
        passthru('composer dump -o');

        $this->call('optimize', []);
    }

}
