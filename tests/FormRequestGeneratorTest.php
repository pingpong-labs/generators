<?php

use Pingpong\Generators\FormRequestGenerator;

class FormRequestGeneratorTest extends TestCase {

	public function testGenerateWithoutOptions()
	{
		$generator = new FormRequestGenerator($this->path, 'LoginRequest');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = ['namespace' => 'Http\\Request\\Auth'];

		$generator = new FormRequestGenerator($this->path, 'RegisterRequest', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}