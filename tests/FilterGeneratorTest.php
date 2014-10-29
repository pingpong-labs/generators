<?php

use Pingpong\Generators\FilterGenerator;

class FilterGeneratorTest extends TestCase {

	public function testGenerateWithoutOptions()
	{
		$generator = new FilterGenerator($this->path, 'AuthFilter');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = ['namespace' => 'Http\\Filters'];

		$generator = new FilterGenerator($this->path, 'AuthBasicFilter', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}