<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Pingpong\Generators\FileAlreadyExistsException;
use Pingpong\Generators\SeedGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SeedCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'generate:seed';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Generate a new seed.';

    /**
     * Execute the command.
     */
    public function fire(Composer $composer)
    {
        try {
            SeedGenerator::generate([
                'name' => $this->argument('name'),
                'master' => $this->option('master'),
                'force' => $this->option('force'),
            ]);

            $this->info('Seed created successfully.');

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
            ['master', 'm', InputOption::VALUE_NONE, 'Generate master database seeder.', null],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the creation if file already exists.', null],
        ];
    }
}
