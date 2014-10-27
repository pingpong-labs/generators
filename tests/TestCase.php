<?php

use Pingpong\Generators\Stub;

class TestCase extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		Stub::setPath(__DIR__ . '/../src/Pingpong/Generators/Stubs');
	}
	
}