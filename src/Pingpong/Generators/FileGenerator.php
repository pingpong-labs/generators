<?php namespace Pingpong\Generators;

use Pingpong\Generators\Stub;
use Pingpong\Generators\Storage;
use Pingpong\Generators\Traits\NamespaceTrait;
use Pingpong\Generators\Traits\OptionableTrait;
use Pingpong\Generators\Traits\StudlyClassNameTrait;
use Pingpong\Generators\Contracts\FileGeneratorInterface;
use Pingpong\Generators\Exceptions\FileAlreadyExistException;

abstract class FileGenerator extends Generator implements FileGeneratorInterface {

    use OptionableTrait, NamespaceTrait, StudlyClassNameTrait;

    /**
     * The name of stub file (without extension) will be used.
     * 
     * @var string
     */
    protected $stub;

    /**
     * The path.
     * 
     * @var string
     */
    protected $path;

    /**
     * Constructor.
     * 
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct();

        $this->path = $path;
    }

    /**
     * Get base path.
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     * 
     * @param  string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get stub replacements.
     *
     * @return array
     */
    abstract public function getStubReplacements();

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->getClassName() . '.php';
    }

    /**
     * Generate the file.
     *
     * @return bool
     */
    public function generate()
    {
        return $this->generateFile();
    }

    /**
     * Get default replacements.
     * 
     * @return array 
     */
    public function getDefaultReplacements()
    {
        return ['CLASS_NAME' => $this->getClassName()];   
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    public function getTemplateContents()
    {
        return new Stub($this->stub, array_merge(
            $this->getDefaultReplacements(),
            $this->getStubReplacements()
            )
        );
    }

    /**
     * Generate a new file.
     *
     * @throws FileAlreadyExistException
     * @return bool
     */
    public function generateFile()
    {
        $path = $this->getDestinationFilePath();

        if($this->filesystem->exists($path))
        {
            throw new FileAlreadyExistException("File already exist : {$path}");
        }

        $this->autoCreateDirectory($path);

        return $this->filesystem->put($path, $this->getTemplateContents());
    }

    /**
     * Auto create directory.
     * 
     * @param  string $path 
     * @return void       
     */
    protected function autoCreateDirectory($path)
    {
        if( ! is_dir($dir = dirname($path)))
        {
            $this->filesystem->makeDirectory($dir);
        }
    }

    /**
     * Get destination filepath.
     * 
     * @return string
     */
    public function getDestinationFilePath()
    {
        return $this->path . '/' . $this->getFilename();
    }

}