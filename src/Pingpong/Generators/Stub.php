<?php namespace Pingpong\Generators;

class Stub {

    /**
     * The short stub name.
     *
     * @var string
     */
    protected $name;

    /**
     * The replacements array.
     *
     * @var array
     */
    protected $replaces = [];

    /**
     * The stub path.
     * 
     * @var string
     */
    protected static $path;

    /**
     * The stub extension.
     * 
     * @var string
     */
    protected $extension = '.stub';

    /**
     * The contructor.
     *
     * @param string $name
     * @param array $replaces
     */
    public function __construct($name, array $replaces = [])
    {
        $this->name = $name;
        $this->replaces = $replaces;
    }

    /**
     * Create new self instance.
     *
     * @param  string $name
     * @param  array $replaces
     * @return self
     */
    public static function create($name, array $replaces = [])
    {
        return new static($name, $replaces);
    }

    /**
     * Register the default stub path.
     * 
     * @return void
     */
    public function register()
    {
        static::$path = __DIR__. '/Stubs';    
    }

    /**
     * Set stub path.
     * 
     * @param string $path
     */
    public static function setPath($path)
    {
        static::$path = $path;
    }

    /**
     * Get stub path.
     * 
     * @return string
     */
    public static function getPath()
    {
        return static::$path;
    }

    /**
     * Get stub path.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return static::$path . '/' . $this->name . $this->extension;
    }

    /**
     * Get stub contents.
     *
     * @return mixed|string
     */
    public function getContents()
    {
        $contents = file_get_contents($this->getStubPath());

        foreach ($this->replaces as $search => $replace)
        {
            $contents = str_replace('$' . strtoupper($search) . '$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Set replacements array.
     *
     * @param  array $replaces
     * @return $this
     */
    public function replace(array $replaces = [])
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * Get replacements.
     *
     * @return array
     */
    public function getReplaces()
    {
        return $this->replaces;
    }

    /**
     * Handle magic method __toString.
     *
     * @return mixed|string
     */
    public function __toString()
    {
        return $this->getContents();
    }

} 