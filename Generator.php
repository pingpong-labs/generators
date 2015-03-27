<?php namespace Pingpong\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Pingpong\Generators\Stub;
use Illuminate\Console\AppNamespaceDetectorTrait;

abstract class Generator {

    use AppNamespaceDetectorTrait;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The array of options.
     * 
     * @var array
     */
    protected $options;

    /**
     * The shortname of stub.
     * 
     * @var string
     */
    protected $stub;

    /**
     * Create new instance of this class.
     * 
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->filesystem = new Filesystem;
        $this->options = $options;
    }

    /**
     * Get the filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the filesystem instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub()
    {
        return (new Stub(__DIR__ . '/Stubs/'.$this->stub, $this->getReplacements()))->getContents();
    }

    /**
     * Get template replacements.
     * 
     * @return array
     */
    public function getReplacements()
    {
        return [
            'class' => $this->getClass(),
            'namespace' => $this->getNamespace()
        ];
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    abstract public function getPath();

    /**
     * Get name input.
     * 
     * @return string
     */
    public function getName()
    {
        $name = $this->name;

        if(str_contains($this->name, '\\'))
        {
            $name = str_replace('\\', '/', $this->name);
        }

        if(str_contains($this->name, '/'))
        {
            $name = str_replace('/', '/', $this->name);
        }

        return Str::studly($name);
    }

    /**
     * Get class name.
     * 
     * @return string
     */
    public function getClass()
    {
        return array_last($this->getSegments(), function ($key, $value)
        {
            return $value;
        });
    }

    /**
     * Get paths of namespace.
     * 
     * @return array
     */
    public function getSegments()
    {
        return explode('/', $this->getName());
    }

    /**
     * Get root namespace.
     * 
     * @return string
     */
    public function getRootNamespace()
    {
        return $this->getAppNamespace();
    }

    /**
     * Get class namespace.
     * 
     * @return string
     */
    public function getNamespace()
    {
        $segments = $this->getSegments();

        array_pop($segments);

        return $this->getRootNamespace() . implode($segments, '\\');
    }

    /**
     * Run the generator.
     *
     * @return int
     * @throws FileAlreadyExistsException
     */
    public function run()
    {
        if ($this->filesystem->exists($path = $this->getPath()) && ! $this->force)
        {
            throw new FileAlreadyExistsException($path);
        }

        if( ! $this->filesystem->isDirectory($dir = dirname($path)))
        {
            $this->filesystem->makeDirectory($dir, 0777, true, true);
        }

        return $this->filesystem->put($path, $this->getStub());
    }

    /**
     * Get options.
     * 
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Determinte whether the given key exist in options array.
     * 
     * @param  string  $key
     * @return boolean
     */
    public function hasOption($key)
    {
        return array_key_exists($key, $this->options);
    }

    /**
     * Get value from options by given key.
     * 
     * @param  string $key
     * @param  string|null $default
     * @return string
     */
    public function getOption($key, $default = null)
    {
        if( ! $this->hasOption($key)) return $default;

        return $this->options[$key] ?: $default;
    }

    /**
     * Helper method for "getOption".
     * 
     * @param  string $key
     * @param  string|null $default
     * @return string
     */
    public function option($key, $default = null)
    {
        return $this->getOption($key, $default);
    }

    /**
     * Handle call to __get method.
     * 
     * @param  string $key
     * @return string|mixed
     */
    public function __get($key)
    {
        if (property_exists($this, $key))
        {
            return $this->{$key};
        }

        return $this->option($key);
    }

}