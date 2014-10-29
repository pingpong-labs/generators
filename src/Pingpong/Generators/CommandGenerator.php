<?php namespace Pingpong\Generators;

class CommandGenerator extends FileGenerator { 
    
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = 'command';

    /**
     * @var string
     */
    protected $stub = 'command';

    /**
     * @param string $name
     * @param string $options
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
        return $this->appendNamespaceStub([
            'COMMAND_NAME' => $this->option('command', 'command:name'),
        ]);
    }

}