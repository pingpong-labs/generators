<?php

namespace Pingpong\Generators;

use Pingpong\Generators\FormDumpers\TableDumper;
use Pingpong\Generators\Migrations\NameParser;
use Pingpong\Generators\Migrations\SchemaParser;

class MigrationGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'migration/plain';

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return base_path().'/database/migrations/';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().$this->getFileName().'.php';
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return '';
    }

    /**
     * Get migration name.
     *
     * @return string
     */
    public function getMigrationName()
    {
        if ($this->existing) {
            return 'create_'.str_plural($this->name).'_table';
        }

        return strtolower($this->name);
    }

    /**
     * Get file name.
     *
     * @return string
     */
    public function getFileName()
    {
        return date('Y_m_d_His_').$this->getMigrationName();
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->fields);
    }

    /**
     * Get name parser.
     *
     * @return NameParser
     */
    public function getNameParser()
    {
        return new NameParser($this->name);
    }

    /**
     * Get stub templates.
     *
     * @return string
     */
    public function getStub()
    {
        $parser = $this->getNameParser();

        if ($this->existing) {
            $this->name = 'create_'.str_plural($this->name).'_table';
            
            $parser = $this->getNameParser();
                
            $fields = (new TableDumper($parser->getTable()))->toSchema();

            $stub = Stub::create('/migration/create.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTable(),
                'fields' => (new SchemaParser($fields))->render(),
            ]);
        }
        elseif ($parser->isCreate()) {
            $stub = Stub::create('/migration/create.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTable(),
                'fields' => $this->getSchemaParser()->render(),
            ]);
        } elseif ($parser->isAdd()) {
            $stub = Stub::create('/migration/add.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTable(),
                'fields_up' => $this->getSchemaParser()->up(),
                'fields_down' => $this->getSchemaParser()->down(),
            ]);
        } elseif ($parser->isDelete()) {
            $stub = Stub::create('/migration/delete.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTable(),
                'fields_down' => $this->getSchemaParser()->up(),
                'fields_up' => $this->getSchemaParser()->down(),
            ]);
        } elseif ($parser->isDrop()) {
            $stub = Stub::create('/migration/drop.stub', [
                'class' => $this->getClass(),
                'table' => $parser->getTable(),
                'fields' => $this->getSchemaParser()->render(),
            ]);
        } else {
            $stub = false;
        }

        if ($stub) {
            return $stub->render();
        }

        return parent::getStub();
    }
}
