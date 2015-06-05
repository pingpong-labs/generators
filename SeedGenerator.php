<?php

namespace Pingpong\Generators;

class SeedGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'seed';

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return base_path().'/database/seeds/';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().$this->getName().'.php';
    }

    /**
     * Get name of class.
     *
     * @return string
     */
    public function getName()
    {
        $name = parent::getName();

        $suffix = $this->master ? 'DatabaseSeeder' : 'TableSeeder';

        return $name.$suffix;
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return false;
    }
}
