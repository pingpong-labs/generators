<?php

namespace Pingpong\Generators;

use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider
{

    /**
     * The array of consoles.
     *
     * @var array
     */
    protected $consoles = [
        'Model',
        'Controller',
        'Console',
        'View',
        'Seed',
        'Migration',
        'Request',
        'Pivot',
        'Scaffold',
        'Form',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->consoles as $console) {
            $this->commands('Pingpong\Generators\Console\\' . $console . 'Command');
        }
    }
}
