<?php

namespace Pingpong\Generators\FormDumpers;

use Illuminate\Support\Facades\DB;

class TableDumper {

    use StubTrait;

    /**
     * The table name.
     *
     * @var string
     */
    protected $table;

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
     * Render the form.
     *
     * @return string
     */
    public function render()
    {
        $columns = $this->getColumns();

        $results = '';

        foreach ($columns as $column)
        {
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

        foreach ($this->getColumns() as $column)
        {
            if (in_array($name = $column->getName(), $this->ignores)) continue;

            $results .= "\t\t\t".'<th>' . ucwords($name) . '</th>'.PHP_EOL;
        }

        return $results;
    }

    /**
     * Convert the fields to formatted php script.
     *
     * @param  string $var
     * @return string
     */
    public function toBody($var)
    {
        $results = '';

        foreach ($this->getColumns() as $column)
        {
            if (in_array($name = $column->getName(), $this->ignores)) continue;

            $results .= "\t\t\t\t\t".'<td>{!! $'.$var.'->' . $name . ' !!}</td>'.PHP_EOL;    
        }

        return $results;   
    }

}