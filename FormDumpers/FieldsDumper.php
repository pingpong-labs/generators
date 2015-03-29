<?php

namespace Pingpong\Generators\FormDumpers;

use Pingpong\Generators\Migrations\SchemaParser;

class FieldsDumper {

    use StubTrait;

    /**
     * The form fields.
     *
     * @var string
     */
    protected $fields;

    /**
     * The constructor.
     *
     * @param string $fields
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Get schema parser.
     *
     * @return string
     */
    public function getParser()
    {
        return new SchemaParser($this->fields);
    }

    /**
     * Render the form.
     *
     * @return string
     */
    public function render()
    {
        $results = '';

        foreach ($this->getParser()->toArray() as $name => $types)
        {
            $results .= $this->getStub($this->getFieldType($types), $name) . PHP_EOL;
        }

        return $results;
    }
}