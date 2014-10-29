<?php

use Pingpong\Generators\ModelGenerator;

class ModelGeneratorTest extends TestCase {

	public function testGenerateWithoutOptions()
	{
		$generator = new ModelGenerator($this->path, 'User');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = ['namespace' => 'App\\Models'];

		$generator = new ModelGenerator($this->path, 'Post', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}