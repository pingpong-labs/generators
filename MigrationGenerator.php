<?php

namespace Pingpong\Generators;

class MigrationGenerator extends Generator {

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
        return base_path() . '/database/migrations/' . $this->getName() . '.php';
    }

    /**
     * Get root namespace.
     * 
     * @return string
     */
    public function getRootNamespace()
    {
        return '';
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        if ($this->plain) return new Stub('migration/plain');

        return parent::getStub();
    }

}