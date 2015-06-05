<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Pingpong\Generators\PivotGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PivotCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'generate:pivot';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Generate a new pivot migration.';

    /**
     * Execute the command.
     */
    public function fire()
    {
        $generator = new PivotGenerator([
            'table_one' => $this->argument('table_one'),
            'table_two' => $this->argument('table_two'),
            'timestamp' => $this->option('timestamp'),
            'force' => $this->option('force'),
        ]);

        $generator->run();

        $this->info('Migration created successfully.');
    }

    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            ['table_one', InputArgument::REQUIRED, 'The name of table one.', null],
            ['table_two', InputArgument::REQUIRED, 'The name of table two.', null],
        ];
    }

    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            ['timestamp', 't', InputOption::VALUE_NONE, 'Add timestamp to migration schema.', null],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the creation if file already exists.', null],
        ];
    }
}
