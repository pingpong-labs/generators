<?php namespace Pingpong\Generators;

class FormRequestGenerator extends FileGenerator {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = 'request';

    /**
     * @var string
     */
    protected $stub = 'request';

    /**
     * @param $name
     * @param $options
     */
    public function __construct($path, $name, array $options = [])
    {
        parent::__construct($path);

        $this->name = $name;
        $this->options = $options;
    }

    /**
     * Get stub replacements.
     *
     * @return array
     */
    public function getStubReplacements()
    {
        return $this->appendNamespaceStub();
    }

}