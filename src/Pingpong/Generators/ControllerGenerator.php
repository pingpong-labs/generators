<?php namespace Pingpong\Generators;

class ControllerGenerator extends FileGenerator {

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = 'controller';

    /**
     * @var string
     */
    protected $stub = 'controller';

    /**
     * @param string $name
     * @param array  $options
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