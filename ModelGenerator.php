<?php

namespace Pingpong\Generators;

class ModelGenerator extends Generator {

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'model';

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return app_path() . '/' . $this->getName() . '.php';
    }

}