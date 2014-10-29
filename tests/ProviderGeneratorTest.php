<?php

use Pingpong\Generators\ProviderGenerator;

class ProviderGeneratorTest extends TestCase {

	public function testGenerateWithoutOptions()
	{
		$generator = new ProviderGenerator($this->path, 'FooServiceProvider');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = ['namespace' => 'Providers'];

		$generator = new ProviderGenerator($this->path, 'BarServiceProvider', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}