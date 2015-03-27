<?php

namespace Pingpong\Generators;

class ControllerGenerator extends Generator {

    /**
     * Get stub name.
     * 
     * @var string
     */
    protected $stub = 'controller';

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

}