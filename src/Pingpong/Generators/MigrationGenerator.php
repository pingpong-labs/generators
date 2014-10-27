<?php namespace Pingpong\Generators;

use Pingpong\Generators\Exceptions\InvalidMigrationNameException;
use Pingpong\Generators\Schema\Field;
use Pingpong\Generators\Schema\Parser;
use Pingpong\Generators\Stub;
use Illuminate\Support\Str;

class MigrationGenerator extends FileGenerator {

    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var null|string
     */
    protected $fields;

    /**
     * @var bool
     */
    protected $plain;

    /**
     * @var string
     */
    protected $type = 'migration';

    /**
     * @param string $path
     * @param string $name
     * @param string|null $fields
     * @param bool $plain
     */
    public function __construct($path, $name, $fields = null, $plain = false)
    {
        parent::__construct();

        $this->name = $name;
        $this->path = $path;
        $this->fields = $fields;
        $this->plain = $plain;
    }

    /**
     * Get stub replacements.
     *
     * @return array
     */
    public function getStubReplacements()
    {
        return [];
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

    /**
     * @return Stub
     * @throws Invalidname
     */
    public function getTemplateContents()
    {
        $schema = new Parser($this->name);

        $fields = new Field($this->fields);

        if ($this->plain)
        {
            return new Stub('migration/plain', [
                'CLASS' => $this->getClassName()
            ]);
        }
        elseif ($schema->isCreate())
        {
            return new Stub('migration/create', [
                'CLASS' => $this->getClassName(),
                'FIELDS' => $fields->getSchemaCreate(),
                'TABLE' => $schema->getTableName()
            ]);
        }
        elseif ($schema->isAdd())
        {
            return new Stub('migration/add', [
                'CLASS' => $this->getClassName(),
                'FIELDS_UP' => $fields->getSchemaCreate(),
                'FIELDS_DOWN' => $fields->getSchemaDropColumn(),
                'TABLE' => $schema->getTableName()
            ]);
        }
        elseif ($schema->isDelete())
        {
            return new Stub('migration/delete', [
                'CLASS' => $this->getClassName(),
                'FIELDS_DOWN' => $fields->getSchemaCreate(),
                'FIELDS_UP' => $fields->getSchemaDropColumn(),
                'TABLE' => $schema->getTableName()
            ]);
        }
        elseif ($schema->isDrop())
        {
            return new Stub('migration/drop', [
                'CLASS' => $this->getClassName(),
                'FIELDS' => $fields->getSchemaCreate(),
                'TABLE' => $schema->getTableName()
            ]);
        }

        throw new InvalidMigrationNameException;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return date('Y_m_d_His_') . $this->name . '.php';
    }

    public function getDestinationFilePath()
    {
        return $this->path . '/' . $this->getFilename();
    }

}