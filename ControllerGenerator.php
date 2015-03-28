<?php

namespace Pingpong\Generators;

use Pingpong\Generators\Scaffolders\ControllerScaffolder;

class ControllerGenerator extends Generator {

    /**
     * Get stub name.
     * 
     * @var string
     */
    protected $stub = 'controller/plain';

    /**
     * Configure some data.
     *
     * @return void
     */
    public function setUp()
    {
        if ($this->resource)
        {
            $this->stub = 'controller/resource';
        }
        elseif ($this->scaffold)
        {
            $this->stub = 'controller/scaffold';
            $this->scaffolder = new ControllerScaffolder($this->getClass(), $this->getPrefix());
        }
    }

    /**
     * Get prefix class.
     * 
     * @return string
     */
    public function getPrefix()
    {
        $paths = explode('/', $this->getName());

        array_pop($paths);

        return strtolower(implode('\\', $paths));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return app_path() . '/Http/Controllers/' . $this->getName() . '.php';
    }

    /**
     * Get root namespace.
     * 
     * @return string
     */
    public function getRootNamespace()
    {
        return $this->getAppNamespace() . 'Http\\Controllers\\';
    }

    /**
     * Get template replacements.
     * 
     * @return array
     */
    public function getReplacements()
    {
        $replacements = array_merge(parent::getReplacements(), ['root_namespace' => $this->getAppNamespace()]);
        
        return array_merge($replacements, $this->scaffolder->toArray());
    }

}