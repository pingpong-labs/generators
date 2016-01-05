<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Pingpong\Generators\FileAlreadyExistsException;
use Pingpong\Generators\MigrationGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MigrationCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'generate:migration';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Generate a new migration.';

    /**
     * Execute the command.
     */
    public function fire(Composer $composer)
    {
        try {
            MigrationGenerator::generate([
                'name' => $this->argument('name'),
                'fields' => $this->option('fields'),
                'force' => $this->option('force'),
                'existing' => $this->option('existing'),
            ]);
         
            $this->info('Migration created successfully.');
         
            $composer->dumpAutoloads();
        } catch (FileAlreadyExistsException $e) {
            $this->comment($e->getMessage());       
        }
    }

    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of class being generated.', null],
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
            ['fields', 'c', InputOption::VALUE_OPTIONAL, 'The fields of migration. Separated with comma (,).', null],
            ['existing', 'e', InputOption::VALUE_NONE, 'Create migration from an existing table.', null],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the creation if file already exists.', null],
        ];
    }
}
