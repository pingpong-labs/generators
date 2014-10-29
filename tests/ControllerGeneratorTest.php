<?php

use Pingpong\Generators\ControllerGenerator;

class ControllerGeneratorTest extends TestCase {

	public function testGenerateWithoutOptions()
	{
		$generator = new ControllerGenerator($this->path, 'HelloController');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = ['namespace' => 'Http\\Controllers'];

		$generator = new ControllerGenerator($this->path, 'UsersController', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}