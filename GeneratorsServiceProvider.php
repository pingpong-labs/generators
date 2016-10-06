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
     * Boot the package.
     * 
     * @return void
     */
    public function boot()
    {
        $templatePath = config('generators.template_path', $fallbackTemplatePath = __DIR__.DIRECTORY_SEPARATOR.'Stubs').DIRECTORY_SEPARATOR;
        if (!\File::exists($templatePath) || !is_dir($templatePath)) {
            throw new \Symfony\Component\Filesystem\Exception\FileNotFoundException("Directory $templatePath dose not exist. Please create it first !");
        }

        $this->publishes([
            __DIR__ . '/Stubs/' => $templatePath
        ], 'stubs');

        $configPath = config_path('generators.php');
       
        $this->publishes([
            __DIR__.'/config.php' => $configPath,
        ], 'config');

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'generators');
        }

        $this->setStubBasePath($templatePath);   
    }

    /**
     * Set stub base path.
     *
     * @param string $templatePath
     * @return void 
     */
    protected function setStubBasePath($templatePath)
    {
        Stub::setBasePath($templatePath);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        foreach ($this->consoles as $console) {
            $this->commands('Pingpong\Generators\Console\\'.$console.'Command');
        }
    }
}
