<?php namespace Pingpong\Generators;

use Illuminate\Support\Str;
use Pingpong\Generators\Stub;
use Pingpong\Generators\Generator;
use Pingpong\Generators\Scaffold\FormGenerator;
use Pingpong\Generators\Traits\OptionableTrait;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Pingpong\Generators\Exceptions\FileAlreadyExistException;

class ViewGenerator extends Generator {

    use OptionableTrait;

    /**
     * @var string
     */
    protected $path = 'resources/views';

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct();

        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getStub()
    {
        return 'scaffold/views/' . $this->option('name', 'blank');
    }

    /**
     * @return null|string
     */
    public function getPrefixBackSlash()
    {
        return $this->prefix ? '\\' . $this->prefix : null;
    }

    /**
     * @return string
     */
    public function getPrefixDot()
    {
        return Str::lower($this->prefix ? $this->prefix . '.' : null);
    }

    /**
     * @return mixed|string
     */
    public function getStudlyEntityName()
    {
        return Str::studly(Str::singular($this->entity));
    }

    /**
     * @return mixed|string
     */
    public function getStudlyPluralEntityName()
    {
        return Str::studly(Str::plural($this->entity));
    }

    /**
     * @return mixed|string
     */
    public function getStudlySingularEntityName()
    {
        return Str::studly(Str::singular($this->entity));
    }

    /**
     * @return string
     */
    public function getLowerPluralEntityName()
    {
        return Str::lower(Str::plural($this->entity));
    }

    /**
     * @return string
     */
    public function getLowerSingularEntityName()
    {
        return Str::lower(Str::singular($this->entity));
    }

    /**
     * @return array
     */
    public function getStubReplacements()
    {
        return [
            'VIEW_LAYOUT' => $this->option('view-layout'),
            'ENTITY' => $this->entity,
            'PREFIX_BACKSLASH' => $this->getPrefixBackSlash(),
            'PREFIX_DOT' => $this->getPrefixDot(),
            'STUDLY_ENTITY' => $this->getStudlyEntityName(),
            'LOWER_PLURAL_ENTITY' => $this->getLowerPluralEntityName(),
            'LOWER_SINGULAR_ENTITY' => $this->getLowerSingularEntityName(),
            'STUDLY_PLURAL_ENTITY' => $this->getStudlyPluralEntityName(),
            'STUDLY_SINGULAR_ENTITY' => $this->getStudlySingularEntityName(),
            'TABLE_HEADING' => $this->getTableHeading(),
            'TABLE_BODY' => $this->getTableBody(),
            'SHOW_BODY' => $this->getShowTableBody(),
            'FORM_FIELDS' => $this->getFormBody()
        ];
    }

    /**
     * @return string
     */
    public function getShowTableBody()
    {
        $result = '';

        $var = $this->getLowerPluralEntityName();

        $heading = explode(',', $this->option('table-heading'));

        $template = '
            <tr>
                <td><b>:title</b></td>
                <td>:value</td>
            </tr>
        ';

        foreach ($heading as $value)
        {
            $entityGetter = '{!! $' . $var . '->' . strtolower($value) . ' !!}';

            $result .= str_replace([':title', ':value'], [$value, $entityGetter], $template);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        if ($table = $this->option('table')) return Str::plural($table);

        return $this->getLowerPluralEntityName();
    }

    /**
     * @return string
     */
    protected function getFormBody()
    {
        return (new FormGenerator($this->option('form')))->generate();
    }

    /**
     * Calculate correct Formbuilder method
     *
     * @param  string $name
     * @return string
     */
    public function getInputType($name)
    {
        $tableInfo = $this->getTableInfo();

        $dataType = $tableInfo[$name]->getType()->getName();

        $lookup = array(
            'string' => 'text',
            'float' => 'text',
            'date' => 'text',
            'text' => 'textarea',
            'boolean' => 'checkbox'
        );

        return array_key_exists($dataType, $lookup)
            ? $lookup[$dataType]
            : 'text';
    }

    /**
     * @return mixed
     */
    public function getTableInfo()
    {
        return \DB::getDoctrineSchemaManager()->listTableDetails($this->getTable())->getColumns();
    }

    /**
     * @return string
     */
    public function getTableBody()
    {
        $result = '';

        $var = $this->getLowerSingularEntityName();

        foreach ($this->getTableInfo() as $name => $info)
        {
            if ($this->isDisplayable($name))
            {
                $result .= '<td>{!! $' . $var . '->' . $name . ' !!}</td>' . PHP_EOL;
            }
        }

        return $result;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isDisplayable($name)
    {
        return ! in_array($name, ['id', 'updated_at', 'deleted_at', 'password']);
    }

    /**
     * @return string
     */
    public function getTableHeading()
    {
        $result = '';

        if ($heading = $this->option('table-heading'))
        {
            $heading = explode(',', $heading);

            foreach ($heading as $key => $head)
            {
                $result .= '<th>' . $head . '</th>' . PHP_EOL;
            }
        }

        return $result;
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    public function getTemplateContents()
    {
        return new Stub($this->getStub(), $this->getStubReplacements());
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

        if ($this->filesystem->exists($path))
        {
            throw new FileAlreadyExistException("File already exist : {$path}");
        }

        $this->autoCreateDirectory($path);

        $this->filesystem->put($path, $this->getTemplateContents());

        return "File created : {$path}";
    }

    /**
     * Auto create directory.
     *
     * @param  string $path
     * @return void
     */
    protected function autoCreateDirectory($path)
    {
        if ( ! is_dir($dir = dirname($path)))
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
        $prefix = Str::lower(($this->prefix ? $this->prefix . '/' : ''));

        return $this->path . '/' . $prefix . $this->getLowerPluralEntityName() . '/' . $this->getFilename();
    }

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return Str::lower($this->name) . '.blade.php';
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
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->option($key);
    }

}