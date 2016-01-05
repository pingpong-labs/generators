<?php

namespace Pingpong\Generators\FormDumpers;

use Pingpong\Generators\Migrations\SchemaParser;
use Pingpong\Generators\Stub;

class FieldsDumper
{
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

        foreach ($this->getParser()->toArray() as $name => $types) {
            $results .= $this->getStub($this->getFieldType($types), $name).PHP_EOL;
        }

        return $results;
    }

    /**
     * Convert the fields to html heading.
     *
     * @return string
     */
    public function toHeading()
    {
        $results = '';

        foreach ($this->getParser()->toArray() as $name => $types) {
            if (in_array($name, $this->ignores)) {
                continue;
            }

            $results .= "\t\t\t".'<th>'.ucwords($name).'</th>'.PHP_EOL;
        }

        return $results;
    }

    /**
     * Convert the fields to formatted php script.
     *
     * @param string $var
     *
     * @return string
     */
    public function toBody($var)
    {
        $results = '';

        foreach ($this->getParser()->toArray() as $name => $types) {
            if (in_array($name, $this->ignores)) {
                continue;
            }

            $results .= "\t\t\t\t\t".'<td>{!! $'.$var.'->'.$name.' !!}</td>'.PHP_EOL;
        }

        return $results;
    }

    /**
     * Get replacements for $SHOW_BODY$.
     *
     * @param string $var
     *
     * @return string
     */
    public function toRows($var)
    {
        $results = PHP_EOL;

        foreach ($this->getParser()->toArray() as $name => $types) {
            if (in_array($name, $this->ignores)) {
                continue;
            }

            $results .= Stub::create('/scaffold/row.stub', [
                'label' => ucwords($name),
                'column' => $name,
                'var' => $var,
            ])->render();
        }

        return $results.PHP_EOL;
    }
}
