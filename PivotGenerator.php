<?php

namespace Pingpong\Generators;

use Illuminate\Support\Str;

class PivotGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'migration/pivot';

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
        return $this->getBasePath().$this->getFilename().'.php';
    }

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return date('Y_m_d_His_').$this->getMigrationName();
    }

    /**
     * Get migration name.
     *
     * @return string
     */
    public function getMigrationName()
    {
        return 'create_'.$this->getPivotTableName().'_pivot_table';
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return Str::studly($this->getMigrationName());
    }

    /**
     * Get the name of the pivot table.
     *
     * @return string
     */
    public function getPivotTableName()
    {
        return implode('_', array_map('str_singular', $this->getSortedTableNames()));
    }

    /**
     * Get sorted table names.
     *
     * @return array
     */
    public function getSortedTableNames()
    {
        $tables = [
            strtolower($this->table_one),
            strtolower($this->table_two),
        ];

        sort($tables);

        return $tables;
    }

    /**
     * Get stub replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'table_one' => $this->table_one,
            'table_two' => $this->table_two,
            'column_one' => $this->getColumnOne(),
            'column_two' => $this->getColumnTwo(),
            'table_pivot' => $this->getPivotTableName(),
            'timestamp' => $this->getTimestampReplacement(),
        ]);
    }

    /**
     * Get replacement for TIMESTAMP.
     *
     * @return string|null
     */
    public function getTimestampReplacement()
    {
        if ($this->timestamp) {
            return '$table->timestamps();';
        }

        return;
    }

    /**
     * Get column one.
     *
     * @return string
     */
    public function getColumnOne()
    {
        return str_singular($this->table_one);
    }

    /**
     * Get column two.
     *
     * @return string
     */
    public function getColumnTwo()
    {
        return str_singular($this->table_two);
    }
}
