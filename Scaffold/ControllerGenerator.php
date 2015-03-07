<?php namespace Pingpong\Generators\Scaffold;

use Illuminate\Support\Str;
use Pingpong\Generators\Stub;
use Pingpong\Generators\Generator;
use Pingpong\Generators\Traits\OptionableTrait;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Pingpong\Generators\Exceptions\FileAlreadyExistException;

class ControllerGenerator extends Generator {

	use OptionableTrait, AppNamespaceDetectorTrait;

    /**
     * The shortname of stub file path.
     * 
     * @var string
     */
	protected $stub = 'scaffold/controller';

    /**
     * The path of controllers.
     * 
     * @var string
     */
	protected $path = 'app/Http/Controllers';

    /**
     * Create new instance of this class.
     * 
     * @param array $options
     */
	public function __construct(array $options = array())
	{		
		parent::__construct();

		$this->options = $options;
	}

    /**
     * Get class name.
     * 
     * @return string
     */
	public function getClassName()
	{
		return Str::studly(Str::plural(str_replace('Controller', '', $this->option('name')))) . 'Controller';
	}

    /**
     * Get prefix path.
     * 
     * @return string
     */
	public function getPrefixBackSlash()
	{
		return $this->prefix ? '\\' . $this->prefix : null;
	}

    /**
     * Get prefix dot.
     * 
     * @return string
     */
	public function getPrefixDot()
	{
		return Str::lower($this->prefix ? $this->prefix . '.' : null);
	}

    /**
     * Get entity name in singular with studly case convention.
     * 
     * @return string
     */
	public function getStudlyEntityName()
	{
		return Str::studly(Str::singular($this->entity));
	}

    /**
     * Get entity name in plural with studly case convention.
     * 
     * @return string
     */
	public function getStudlyPluralEntityName()
	{
		return Str::studly(Str::plural($this->entity));
	}
    /**
     * Get entity name in plural with lower case convention.
     * 
     * @return string
     */
	public function getLowerPluralEntityName()
	{
		return Str::lower(Str::plural($this->entity));
	}
    /**
     * Get entity name in singular with lower case convention.
     * 
     * @return string
     */
	public function getLowerSingularEntityName()
	{
		return Str::lower(Str::singular($this->entity));
	}

    /**
     * Get model name.
     * 
     * @return string
     */
	public function getModel()
	{
		return $this->getAppNamespace() . $this->model;
	}

    /**
     * Get stub replacements.
     * 
     * @return array
     */
	public function getStubReplacements()
	{
		return [
			'MODEL' => $this->getModel(),
			'ENTITY' => $this->entity,
			'PREFIX_BACKSLASH' => $this->getPrefixBackSlash(),
			'PREFIX_DOT' => $this->getPrefixDot(),
			'STUDLY_ENTITY' => $this->getStudlyEntityName(),
			'LOWER_PLURAL_ENTITY' => $this->getLowerPluralEntityName(),
			'LOWER_SINGULAR_ENTITY' => $this->getLowerSingularEntityName(),
			'STUDLY_PLURAL_ENTITY' => $this->getStudlyPluralEntityName()
		];
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

    public function __get($key)
    {
    	return $this->option($key);
    }

}