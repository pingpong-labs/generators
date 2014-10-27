<?php namespace Pingpong\Generators;

use Pingpong\Generators\Exceptions\FileAlreadyExistException;
use Pingpong\Generators\Contracts\FileGeneratorInterface;
use Pingpong\Generators\Storage;
use Pingpong\Generators\Stub;

abstract class FileGenerator extends Generator implements FileGeneratorInterface {

    /**
     * The name of stub file (without extension) will be used.
     * 
     * @var string
     */
    protected $stub;

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
     * Get class name.
     *
     * @return string
     */
    abstract protected function getClassName();

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

}