<?php

namespace Pingpong\Generators;

class ViewGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'view';

    /**
     * The array of custom replacements.
     *
     * @var array
     */
    protected $customReplacements = [];

    /**
     * Setup.
     */
    public function setUp()
    {
        if ($this->master) {
            $this->stub = 'views/master';
        }
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return base_path().'/resources/views/';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().strtolower($this->getName()).'.blade.php';
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        if ($this->plain) {
            return $this->getPath();
        }

        if ($template = $this->template) {
            return Stub::create($template, $this->getReplacements())->render();
        }

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
        $replaces = [
            'extends' => $this->extends,
            'section' => $this->section,
            'content' => $this->content,
        ];

        return $this->customReplacements + $replaces;
    }

    /**
     * Append a custom replacements to this instance.
     *
     * @param array $replacements
     *
     * @return self
     */
    public function appendReplacement(array $replacements)
    {
        $this->customReplacements = $replacements;

        return $this;
    }
}
