<?php

namespace Pingpong\Generators;

use Pingpong\Generators\Stub;

class ViewGenerator extends Generator {

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'view';

    /**
     * Setup.
     *
     * @return void
     */
    public function setUp()
    {
        if ($this->master)
        {
            $this->stub = 'views/master';
        }
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return base_path() . '/resources/views/' . strtolower($this->getName()) . '.blade.php';
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        if ($this->plain)
            return $this->getPath();

        if ($template = $this->template)
            return Stub::create($template, $this->getReplacements())->render();

        return parent::getStub();
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
     * Get template replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return [
            'extends' => $this->extends,
            'section' => $this->section,
            'content' => $this->content,
        ];
    }

}