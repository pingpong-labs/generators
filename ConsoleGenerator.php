<?php

namespace Pingpong\Generators;

class ConsoleGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'console';

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return app_path().'/Console/Commands';
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return $this->getAppNamespace().'Console\\Commands\\';
    }

    /**
     * Get template replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'command' => $this->option('command', 'command:name'),
            'description' => $this->option('description', 'Command description'),
        ]);
    }
}
