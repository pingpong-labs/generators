<?php namespace Pingpong\Generators\Console;

use Illuminate\Console\Command;
use Pingpong\Generators\CrudGenerator;
use Pingpong\Generators\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrudCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new CRUD';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        Stub::register();

        $options = [
            'entity' => $this->argument('entity'),
            'form' => $this->option('form'),
            'fields' => $this->option('fields'),
            'prefix' => $this->option('prefix'),
            'table-heading' => $this->option('table-heading'),
            'view-layout' => $this->option('view-layout'),
            'table' => $this->option('table'),
        ];

        (new CrudGenerator($options, $this))->generate();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['entity', InputArgument::REQUIRED, 'An entity name.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, 'An fields data.', null],
            ['form', null, InputOption::VALUE_OPTIONAL, 'An form data.', null],
            ['table', null, InputOption::VALUE_OPTIONAL, 'An table name.', null],
            ['prefix', null, InputOption::VALUE_OPTIONAL, 'An prefix path.', null],
            ['table-heading', null, InputOption::VALUE_OPTIONAL, 'An table heading.', null],
            ['view-layout', null, InputOption::VALUE_OPTIONAL, 'An view layout.', 'admin.layouts.master'],
        ];
    }

}
