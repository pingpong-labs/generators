<?php namespace Pingpong\Generators\Scaffold;

use Pingpong\Generators\Contracts\GeneratorInterface;
use Pingpong\Generators\Stub;

class FormGenerator implements GeneratorInterface {

    /**
     * The list of form fields will be created.
     *
     * @var string
     */
    protected $fields;

    /**
     * Create a new instance.
     *
     * @param void
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Generate the form.
     *
     * @return string
     */
    public function generate()
    {
        $fields = explode(',', $this->fields);

        $result = '';

        foreach ($fields as $field)
        {
            list($name, $type) = explode(':', $field);

            $label = ucfirst($name);

            $result .= PHP_EOL . $this->getStub($type, compact('name', 'label', 'type'))->getContents() . PHP_EOL;
        }

        return $result;
    }

    /**
     * Get stub for specified form type.
     *
     * @param  string $type
     * @param  array $replacements
     * @return string
     */
    protected function getStub($type, array $replacements)
    {
        return new Stub('form/' . $type, $replacements);
    }

}