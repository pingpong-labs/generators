<?php

use Pingpong\Generators\SeedGenerator;

class SeedGeneratorTest extends TestCase {

	public function testGenerateWithoutOptions()
	{
		$generator = new SeedGenerator($this->path, 'UsersTableSeeder');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateWithSpecifiedOptions()
	{
		$options = ['namespace' => 'Database\\Seeds'];

		$generator = new SeedGenerator($this->path, 'PostsTableSeeders', $options);

		$generator->generate();

		$this->assertGenerated($generator);
	}

}