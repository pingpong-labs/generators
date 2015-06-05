<?php

namespace Pingpong\Generators;

use Pingpong\Generators\Migrations\SchemaParser;

class RequestGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'request';

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return app_path().'/Http/Requests/';
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
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return $this->getAppNamespace().'Http\Requests\\';
    }

    /**
     * Get stub replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'auth' => $this->getAuth(),
            'rules' => $this->getRules(),
        ]);
    }

    /**
     * Get auth replacement.
     *
     * @return string
     */
    public function getAuth()
    {
        $authorize = $this->auth ? 'true' : 'false';

        return 'return '.$authorize.';';
    }

    /**
     * Get replacement for "$RULES$".
     *
     * @return string
     */
    public function getRules()
    {
        if (!$this->rules) {
            return 'return [];';
        }

        $parser = new SchemaParser($this->rules);

        $results = 'return ['.PHP_EOL;

        foreach ($parser->toArray() as $field => $rules) {
            $results .= $this->createRules($field, $rules);
        }

        $results .= "\t\t];";

        return $results;
    }

    /**
     * Create a rule.
     *
     * @param string $field
     * @param string $rules
     *
     * @return string
     */
    protected function createRules($field, $rules)
    {
        $rule = str_replace(['(', ')', ';'], [':', '', ','], implode('|', $rules));

        if ($this->scaffold) {
            $rule = 'required';
        }

        return "\t\t\t'{$field}' => '".$rule."',".PHP_EOL;
    }
}
