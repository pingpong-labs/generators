<?php

use Pingpong\Generators\CommandGenerator;

class CommandGeneratorTest extends TestCase {

	public function testGenerateCommand()
	{
		$generator = new CommandGenerator($this->path, 'FooCommand');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = [
			'command' => 'foo',
			'namespace' => 'Foo\\Bar\\Baz'
		];

		$generator = new CommandGenerator($this->path, 'BarCommand', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}