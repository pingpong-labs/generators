<?php

namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Pingpong\Generators\FormGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FormCommand extends Command {

    /**
     * The name of command.
     * 
     * @var string
     */
    protected $name = 'generate:form';

    /**
     * The description of command.
     * 
     * @var string
     */
    protected $description = 'Generate a new form.';

    /**
     * Execute the command.
     * 
     * @return void
     */
    public function fire()
    {
        $generator = new FormGenerator(
            $this->argument('name'),
            $this->option('fields')
        );

        $this->line($generator->render());
    }

    /**
     * The array of command arguments.
     * 
     * @return array
     */
    public function getArguments()
    {
        return [
          ['name', InputArgument::OPTIONAL, 'The name of entity being used.', null],
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
          ['fields', 'f', InputOption::VALUE_OPTIONAL, 'The form fields.', null],
        ];
    }
}