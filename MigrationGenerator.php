<?php namespace Pingpong\Generators;

use Pingpong\Generators\Exceptions\InvalidMigrationNameException;
use Pingpong\Generators\Schema\Field;
use Pingpong\Generators\Schema\Parser;
use Pingpong\Generators\Stub;
use Illuminate\Support\Str;

class MigrationGenerator extends FileGenerator {

    /**
     * The name of migration.
     * 
     * @var string
     */
    protected $name;

    /**
     * The specified migration fields.
     * 
     * @var null|string
     */
    protected $fields;

    /**
     * Create a plain migration.
     * 
     * @var bool
     */
    protected $plain;

    /**
     * The name of stub will be used.
     * 
     * @var string
     */
    protected $type = 'migration';

    /**
     * The constructor.
     * 
     * @param string $path
     * @param string $name
     * @param string|null $fields
     * @param bool $plain
     */
    public function __construct($path, $name, $fields = null, $plain = false)
    {
        parent::__construct($path);

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
        $schema = $this->getSchemaParser();
        
        if ($this->plain) return $this->getPlainStubContents();
        
        elseif ($schema->isCreate()) return $this->getCreatingStubContents();

        elseif ($schema->isAdd()) return $this->getAddingStubContents();

        elseif ($schema->isDelete()) return $this->getDeletingStubContents();

        elseif ($schema->isDrop()) return $this->getDroppingStubContents();

        throw new InvalidMigrationNameException;
    }

    /**
     * Get fields.
     * 
     * @return Field
     */
    public function getFields()
    {
        return new Field($this->fields);
    }

    /**
     * Get schema parser.
     * 
     * @return Parser 
     */
    public function getSchemaParser()
    {
        return new Parser($this->name);
    }

    /**
     * Get stub contents for dropping action.
     * 
     * @return Stub
     */
    protected function getPlainStubContents()
    {
        return new Stub('migration/plain', ['CLASS' => $this->getClassName()]);
    }

    /**
     * Get stub contents for creating action.
     * 
     * @return Stub
     */
    protected function getCreatingStubContents()
    {
        return new Stub('migration/create', [
            'CLASS' => $this->getClassName(),
            'FIELDS' => $this->getFields()->getSchemaCreate(),
            'TABLE' => $this->getSchemaParser()->getTableName()
        ]);
    }

    /**
     * Get stub contents for adding action.
     * 
     * @return Stub
     */
    protected function getAddingStubContents()
    {
        return new Stub('migration/add', [
            'CLASS' => $this->getClassName(),
            'FIELDS_UP' => $this->getFields()->getSchemaCreate(),
            'FIELDS_DOWN' => $this->getFields()->getSchemaDropColumn(),
            'TABLE' => $this->getSchemaParser()->getTableName()
        ]);
    }

    /**
     * Get stub contents for deleting action.
     * 
     * @return Stub
     */
    protected function getDeletingStubContents()
    {
        return new Stub('migration/delete', [
            'CLASS' => $this->getClassName(),
            'FIELDS_DOWN' => $this->getFields()->getSchemaCreate(),
            'FIELDS_UP' => $this->getFields()->getSchemaDropColumn(),
            'TABLE' => $this->getSchemaParser()->getTableName()
        ]);
    }

    /**
     * Get stub contents for dropping action.
     * 
     * @return Stub
     */
    protected function getDroppingStubContents()
    { 
        return new Stub('migration/drop', [
            'CLASS' => $this->getClassName(),
            'FIELDS' => $this->getFields()->getSchemaCreate(),
            'TABLE' => $this->getSchemaParser()->getTableName()
        ]);
    }

    /**
     * Get filename.
     * 
     * @return string
     */
    public function getFilename()
    {
        return date('Y_m_d_His_') . $this->name . '.php';
    }

}