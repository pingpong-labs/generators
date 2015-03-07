<?php namespace Pingpong\Generators\Providers;

use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider {

	/**
	 * Register the commands.
	 * 
	 * @return void
	 */
	public function register()
	{
		$this->commands('Pingpong\Generators\Console\CrudCommand');
	}

}