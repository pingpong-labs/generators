<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Pingpong\Generators\ViewGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ViewCommand extends Command {

    /**
     * The name of command.
     * 
     * @var string
     */
    protected $name = 'generate:view';

    /**
     * The description of command.
     * 
     * @var string
     */
    protected $description = 'Generate a new view.';

    /**
     * Execute the command.
     * 
     * @return void
     */
    public function fire()
    {
        $generator = new ViewGenerator([
            'name' => $this->argument('name'),
            'extends' => $this->option('extends'),
            'section' => $this->option('section'),
            'plain' => $this->option('plain'),
            'force' => $this->option('force'),
        ]);

        $generator->run();

        $this->info("View created successfully.");
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
          ['extends', 'e', InputOption::VALUE_OPTIONAL, 'The name of view layout being used.', 'layouts.master'],
          ['section', 's', InputOption::VALUE_OPTIONAL, 'The name of section being used.', 'content'],
          ['plain', 'p', InputOption::VALUE_NONE, 'Create a blank view.', null],
          ['force', 'f', InputOption::VALUE_NONE, 'Force the creation if file already exists.', null],
        ];
    }
}