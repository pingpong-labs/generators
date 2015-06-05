<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Pingpong\Generators\RequestGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RequestCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'generate:request';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Generate a new form request class.';

    /**
     * Execute the command.
     */
    public function fire()
    {
        $generator = new RequestGenerator([
            'name' => $this->argument('name'),
            'rules' => $this->option('rules'),
            'auth' => $this->option('auth'),
            'scaffold' => $this->option('scaffold'),
            'force' => $this->option('force'),
        ]);

        $generator->run();

        $this->info('Form request created successfully.');
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
            ['rules', 'r', InputOption::VALUE_OPTIONAL, 'The rules.', null],
            ['scaffold', 's', InputOption::VALUE_NONE, 'Determine whether the request class generated with scaffold.', null],
            ['auth', 'a', InputOption::VALUE_NONE, 'Determine whether the request class needs authorized.', null],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the creation if file already exists.', null],
        ];
    }
}
