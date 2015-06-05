<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Pingpong\Generators\ControllerGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'generate:controller';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Generate a new controller.';

    /**
     * Execute the command.
     */
    public function fire()
    {
        $generator = new ControllerGenerator([
            'name' => $this->argument('name'),
            'resource' => $this->option('resource'),
            'scaffold' => $this->option('scaffold'),
            'force' => $this->option('force'),
        ]);

        $generator->run();

        $this->info('Controller created successfully.');
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
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller.', null],
            ['scaffold', 's', InputOption::VALUE_NONE, 'Generate a scaffold controller.', null],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the creation if file already exists.', null],
        ];
    }
}
