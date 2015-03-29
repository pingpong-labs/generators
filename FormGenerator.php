<?php

namespace Pingpong\Generators;

use Pingpong\Generators\Migrations\SchemaParser;
use Pingpong\Generators\Stub;

class FormGenerator {

    /**
     * The name of entity.
     * 
     * @var string
     */
    protected $name;

    /**
     * The form fields.
     * 
     * @var string
     */
    protected $fields;

    /**
     * The schema parser.
     * 
     * @var SchemaParser.
     */
    protected $parser;

    /**
     * The array of types.
     * 
     * @var array
     */
    protected $types = [
        'string' => 'text',
        'text' => 'textarea',
        'boolean' => 'checkbox',
    ];

    /**
     * The supported inputs.
     * 
     * @var array
     */
    protected $inputs = [
        'text',
        'textarea',
        'checkbox',
        'select',
        'radio',
        'password',
    ];

    /**
     * The array of special input/type.
     * 
     * @var array
     */
    protected $specials = [
        'email',
        'password',
    ];

    /**
     * The constructor.
     * 
     * @param string $name
     * @param string $fields
     */
    public function __construct($name, $fields)
    {
        $this->name = $name;
        $this->fields = $fields;
        $this->parser = new SchemaParser($fields);
    }

    /**
     * Render the form.
     * 
     * @return string
     */
    public function render()
    {
        $results = '';

        foreach ($this->parser->toArray() as $name => $types)
        {
            $results .= $this->getStub($this->getFieldType($types), $name) . PHP_EOL;
        }

        return $results;
    }

    /**
     * Get stub template.
     * 
     * @param  string $type
     * @param  string $name
     * @return string
     */
    public function getStub($type, $name)
    {
        $type = $this->getInputType($type, $name);

        return Stub::create(__DIR__ . '/Stubs/form/' . $type . '.stub', [
            'name' => $name,
            'label' => ucwords($name),
        ])->render();
    }

    /**
     * Get input type.
     * 
     * @param  string $type
     * @param  string $name
     * @return string
     */
    public function getInputType($type, $name)
    {
        if (in_array($name, $this->specials))
        {
            return $name;
        }

        if (array_key_exists($type, $this->types))
        {
            return $this->types[$type];
        }

        return in_array($type, $this->inputs) ? $type : 'text';
    }

    /**
     * Get field type.
     * 
     * @param  array $types
     * @return string
     */
    public function getFieldType($types)
    {
        return array_first($types, function ($key, $value)
        {
            return $value;
        });
    }

}