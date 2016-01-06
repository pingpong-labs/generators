<?php

namespace Pingpong\Generators\FormDumpers;

use Illuminate\Support\Facades\DB;
use Pingpong\Generators\Stub;

class TableDumper
{
    use StubTrait;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table;

    /**
     * The array of excepted fields.
     * 
     * @var array
     */
    protected $except = [];

    /**
     * The constructor.
     *
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Make a new instance of this class.
     * 
     * @param  string $table
     * @return self
     */
    public static function make($table)
    {
        return new static($table);
    }

    /**
     * Set excepted fields.
     * 
     * @param  array|string $except
     * @return self
     */
    public function except($except)
    {
        $this->except = $except;

        return $this;
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Get column.
     *
     * @return string
     */
    public function getColumns()
    {
        return DB::getDoctrineSchemaManager()->listTableDetails($this->getTable())->getColumns();
    }

    /**
     * Convert table description to migration schema.
     * 
     * @return string
     */
    public function toSchema()
    {
        $schema = [];

        foreach ($this->getColumns() as $column) {
            if (! in_array($name = $column->getName(), $this->except)) {
                $schema[] = $name.':'.strtolower($column->getType());
            }
        }

        return implode(', ', $schema);
    }

    /**
     * Render the form.
     *
     * @return string
     */
    public function render()
    {
        $columns = $this->getColumns();

        $results = '';

        foreach ($columns as $column) {
            $results .= $this->getStub($column->getType()->getName(), $column->getName());
        }

        return $results;
    }

    /**
     * Convert the fields to html heading.
     *
     * @return string
     */
    public function toHeading()
    {
        $results = '';

        foreach ($this->getColumns() as $column) {
            if (in_array($name = $column->getName(), $this->ignores)) {
                continue;
            }

            $results .= "\t\t\t".'<th>'.ucwords($name).'</th>'.PHP_EOL;
        }

        return $results;
    }

    /**
     * Convert the fields to formatted php script.
     *
     * @param string $var
     *
     * @return string
     */
    public function toBody($var)
    {
        $results = '';

        foreach ($this->getColumns() as $column) {
            if (in_array($name = $column->getName(), $this->ignores)) {
                continue;
            }

            $results .= "\t\t\t\t\t".'<td>{!! $'.$var.'->'.$name.' !!}</td>'.PHP_EOL;
        }

        return $results;
    }

    /**
     * Get replacements for $SHOW_BODY$.
     *
     * @param string $var
     *
     * @return string
     */
    public function toRows($var)
    {
        $results = PHP_EOL;

        foreach ($this->getColumns() as $column) {
            if (in_array($name = $column->getName(), $this->ignores)) {
                continue;
            }

            $results .= Stub::create('scaffold/row.stub', [
                'label' => ucwords($name),
                'column' => $name,
                'var' => $var,
            ])->render();
        }

        return $results.PHP_EOL;
    }
}
