<?php

namespace Pingpong\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ScaffoldGenerator {

    /**
     * The illuminate command instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The laravel instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $laravel;

    /**
     * The array of view names being created.
     *
     * @var array
     */
    protected $views = ['index', 'edit', 'show', 'create'];

    /**
     * The constructor.
     *
     * @param Command $console
     */
    public function __construct(Command $console)
    {
        $this->console = $console;
        $this->laravel = $console->getLaravel();
    }

    /**
     * Get entity name.
     *
     * @return string
     */
    public function getEntity()
    {
        return strtolower(str_singular($this->console->argument('entity')));
    }

    /**
     * Get entities name.
     *
     * @return string
     */
    public function getEntities()
    {
        return str_plural($this->getEntity());
    }

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getControllerName()
    {
        $controller = Str::studly($this->getEntity()) . 'Controller';

        if ($this->console->option('prefix'))
        {
            $controller = Str::studly($this->getPrefix('/')) . $controller;
        }

        return $controller;
    }

    /**
     * Confirm a question with the user.
     * 
     * @param  string $message
     * @return string
     */
    public function confirm($message)
    {
        if ($this->console->option('no-question')) return true;
        
        return $this->console->confirm($message) && $this->console->option('force');
    }

    /**
     * Generate model.
     *
     * @return void
     */
    public function generateModel()
    {
        if ( ! $this->confirm('Do you want to create a model?'))
        {
            return;
        }

        $this->console->call('generate:model', [
            'name' => $this->getEntity(),
            '--force' => $this->console->option('force')
        ]);
    }

    /**
     * Generate seed.
     *
     * @return void
     */
    public function generateSeed()
    {
        if ( ! $this->confirm('Do you want to create a database seeder class?'))
        {
            return;
        }

        $this->console->call('generate:seed', [
            'name' => $this->getEntities(),
            '--force' => $this->console->option('force')
        ]);
    }

    /**
     * Generate migration.
     *
     * @return void
     */
    public function generateMigration()
    {
        if ( ! $this->confirm('Do you want to create a migration?'))
        {
            return;
        }

        $this->console->call('generate:migration', [
            'name' => "create_{$this->getEntities()}_table",
            '--fields' => $this->console->option('fields'),
            '--force' => $this->console->option('force')
        ]);
    }

    /**
     * Generate controller.
     *
     * @return void
     */
    public function generateController()
    {
        if ( ! $this->confirm('Do you want to generate a controller?'))
        {
            return;
        }

        $this->console->call('generate:controller', [
            'name' => $this->getControllerName(),
            '--force' => $this->console->option('force'),
            '--scaffold' => true
        ]);
    }

    /**
     * Generate views.
     *
     * @return void
     */
    public function generateViews()
    {
        $layout = $this->getPrefix('/') . 'layouts/master';
        
        if ($this->confirm('Do you want to create master view?'))
        {
            $this->console->call('generate:view', [
                'name' => $layout,
                '--master' => true,
                '--force' => $this->console->option('force')
            ]);
        }

        if ( ! $this->confirm('Do you want to create view resources?'))
        {
            return;
        }

        foreach ($this->views as $view)
        {
            $this->console->call('generate:view', [
                'name' => $this->getPrefix('/') . $this->getEntities() . '/' . $view,
                '--extends' => str_replace('/', '.', $layout),
                '--force' => $this->console->option('force')
            ]);
        }
    }

    /**
     * Append new route.
     *
     * @return void
     */
    public function appendRoute()
    {
        if ( ! $this->confirm('Do you want to append new route?'))
        {
            return;
        }

        $contents = $this->laravel['files']->get($path = app_path('Http/routes.php'));
        $contents .= PHP_EOL . "Route::resource('{$this->getRouteName()}', '{$this->getControllerName()}');";

        $this->laravel['files']->put($path, $contents);

        $this->console->info("Route appended successfully.");
    }

    /**
     * Get route name.
     *
     * @return string
     */
    public function getRouteName()
    {
        $route = $this->getEntities();

        if ($this->console->option('prefix'))
        {
            $route = strtolower($this->getPrefix('/')) . $route;
        }

        return $route;
    }

    /**
     * Get prefix name.
     *
     * @param  string|null $suffix
     * @return string|null
     */
    public function getPrefix($suffix = null)
    {
        return $this->console->option('prefix') . $suffix;
    }

    /**
     * Run the generator.
     *
     * @return void
     */
    public function run()
    {
        $this->generateModel();
        $this->generateMigration();
        $this->generateSeed();
        $this->generateController();
        $this->generateViews();
        $this->appendRoute();
    }

}