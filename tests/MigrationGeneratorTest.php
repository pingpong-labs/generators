<?php

use Pingpong\Generators\MigrationGenerator;
use Mockery as m;

class MigrationGeneratorTest extends TestCase {

	public function testGenerateMigrationTable()
	{
		$generator = new MigrationGenerator($this->path, 'create_users_table');

		$generator->generate();

		$this->assertGenerated($generator);
	}

	public function testGenerateMigrationTableButThatFileAlreadyExist()
	{
		$this->setExpectedException('Pingpong\Generators\Exceptions\FileAlreadyExistException');
		
		$generator = new MigrationGenerator($this->path, 'create_users_table');

		$filesystem = m::mock('Illuminate\Filesystem\Filesystem');

		$generator->setFilesystem($filesystem);

		$filesystem->shouldReceive('exists')->once()->andReturn(true);
		
		$generator->generate();
		
		$this->assertGenerated($generator);
	}

	public function testGenerateMigrationWithSpecifiedFields()
	{
		$fields = 'title:string, body:text';

		$generator = new MigrationGenerator($this->path, 'create_posts_table', $fields);

		$generator->generate();
		
		$this->assertGenerated($generator);
	}

	public function testGenerateMigrationTableWithPlainFlag()
	{
		$generator = new MigrationGenerator($this->path, 'create_dogs_table', null, true);

		$generator->generate();
		
		$this->assertGenerated($generator);
	}

	public function testGenerateMigrationTableWithInvalidMigrationName()
	{
		$this->setExpectedException('Pingpong\Generators\Exceptions\InvalidMigrationNameException');

		$generator = new MigrationGenerator($this->path, 'foo_bar');

		$generator->generate();
		
		$this->assertGenerated($generator);
	}

	public function testGenerateMigrationTableWithOtherSchema()
	{
		// make
		$generator = new MigrationGenerator($this->path, 'make_teachers_table', 'name:string');
		$generator->generate();
		$this->assertGenerated($generator);

		// delete
		$generator = new MigrationGenerator($this->path, 'delete_name_from_dogs_table', 'name:string');
		$generator->generate();
		$this->assertGenerated($generator);

		// remove
		$generator = new MigrationGenerator($this->path, 'remove_name_from_dogs_table', 'name:string');
		$generator->generate();
		$this->assertGenerated($generator);

		// add
		$generator = new MigrationGenerator($this->path, 'add_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertGenerated($generator);
		
		$generator = new MigrationGenerator($this->path, 'insert_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertGenerated($generator);
		
		
		$generator = new MigrationGenerator($this->path, 'append_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertGenerated($generator);
		
		
		$generator = new MigrationGenerator($this->path, 'update_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertGenerated($generator);

		// drop
		$generator = new MigrationGenerator($this->path, 'drop_dogs_table', 'name:string, age:string');
		$generator->generate();
		$this->assertGenerated($generator);

		$generator = new MigrationGenerator($this->path, 'destroy_dogs_table', 'name:string, age:string');
		$generator->generate();
		$this->assertGenerated($generator);
	}

}