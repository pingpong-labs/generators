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
	 * The array fo view names being created.
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
		return Str::studly($this->getEntity()) . 'Controller';
	}

	/**
	 * Generate model.
	 * 
	 * @return void
	 */
	public function generateModel()
	{
        $this->console->call('generate:model', [
            'name' => $this->getEntity(),
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
        foreach ($this->views as $view)
        {
            $this->console->call('generate:view', [
                'name' => $this->getEntities() . '/' . $view,
                '--with-layout' => true,
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
        $contents = $this->laravel['files']->get($path = app_path('Http/routes.php'));
        $contents.= PHP_EOL."Route::resource('{$this->getEntities()}', '{$this->getControllerName()}');";

        $this->laravel['files']->put($path, $contents);	
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
		$this->generateController();
		$this->generateViews();
		$this->appendRoute();
	}

}