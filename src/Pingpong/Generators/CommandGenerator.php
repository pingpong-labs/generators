<?php namespace Pingpong\Generators;

use Illuminate\Support\Str;
use Pingpong\Generators\Traits\OptionableTrait;

class CommandGenerator extends FileGenerator {
    
    use OptionableTrait;
    
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
        return [
            'COMMAND_NAME' => $this->option('command', 'command:name'),
            'NAMESPACE' => $this->getNamespace() ,
        ];
    }

    /**
     * Get class namespace.
     * 
     * @return string
     */
    public function getNamespace()
    {
        return $this->option('namespace') ? 'namespace ' . $this->option('namespace') : '';
    }

    /**
     * Get class name.
     *
     * @return string
     */
    protected function getClassName()
    {
        return Str::studly($this->name);
    }

}