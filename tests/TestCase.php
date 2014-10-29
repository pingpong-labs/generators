<?php

use Illuminate\Filesystem\Filesystem;
use Pingpong\Generators\Generator;
use Pingpong\Generators\Stub;

class TestCase extends PHPUnit_Framework_TestCase {

	protected $path;

	public function setUp()
	{
		Stub::setPath(__DIR__ . '/../src/Pingpong/Generators/Stubs');
		
		$this->path = __DIR__ . '/../fixture';
	}
	
	public function tearDown()
	{
		$filesystem  = new Filesystem;

		$filesystem->cleanDirectory($this->path);

		$filesystem->put($this->path . '/.gitignore', "*\n!.gitignore");
	}

	protected function assertGenerated(Generator $generator)
	{
		$this->assertTrue(file_exists($path = $generator->getDestinationFilePath()));
		$this->assertSame(
			file_get_contents($path),
			$generator->getTemplateContents()->getContents()
		);
	}
}