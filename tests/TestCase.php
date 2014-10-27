<?php

use Pingpong\Generators\Stub;

class TestCase extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		Stub::setPath(__DIR__ . '/../src/Pingpong/Generators/Stubs');
	}

	protected function assertGenerated($generator)
	{
		$this->assertTrue(file_exists($path = $generator->getDestinationFilePath()));
		$this->assertSame(
			file_get_contents($path),
			$generator->getTemplateContents()->getContents()
		);
	}
}