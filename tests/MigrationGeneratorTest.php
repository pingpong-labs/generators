<?php

use Pingpong\Generators\MigrationGenerator;

class MigrationGeneratorTest extends TestCase {

	protected $path;

	public function setUp()
	{
		parent::setUp();

		$this->path = __DIR__ . '/../fixture';
	}

	protected function assertMigrationSame($generator)
	{
		$this->assertSame(
			file_get_contents($generator->getDestinationFilePath()),
			$generator->getTemplateContents()->getContents()
		);
	}

	public function testGenerateMigrationTable()
	{
		$generator = new MigrationGenerator($this->path, 'create_users_table');

		$generator->generate();

		$this->assertMigrationSame($generator);
	}

	public function testGenerateMigrationTableButThatFileAlreadyExist()
	{
		$this->setExpectedException('Pingpong\Generators\Exceptions\FileAlreadyExistException');

		$generator = new MigrationGenerator($this->path, 'create_users_table');

		$generator->generate();
		
		$this->assertMigrationSame($generator);
	}

	public function testGenerateMigrationWithSpecifiedFields()
	{
		$fields = 'title:string, body:text';

		$generator = new MigrationGenerator($this->path, 'create_posts_table', $fields);

		$generator->generate();
		
		$this->assertMigrationSame($generator);
	}

	public function testGenerateMigrationTableWithPlainFlag()
	{
		$generator = new MigrationGenerator($this->path, 'create_dogs_table', null, true);

		$generator->generate();
		
		$this->assertMigrationSame($generator);
	}

	public function testGenerateMigrationTableWithInvalidMigrationName()
	{
		$this->setExpectedException('Pingpong\Generators\Exceptions\InvalidMigrationNameException');

		$generator = new MigrationGenerator($this->path, 'foo_bar');

		$generator->generate();
		
		$this->assertMigrationSame($generator);
	}

	public function testGenerateMigrationTableWithOtherSchema()
	{
		// make
		$generator = new MigrationGenerator($this->path, 'make_teachers_table', 'name:string');
		$generator->generate();
		$this->assertMigrationSame($generator);

		// delete
		$generator = new MigrationGenerator($this->path, 'delete_name_from_dogs_table', 'name:string');
		$generator->generate();
		$this->assertMigrationSame($generator);

		// remove
		$generator = new MigrationGenerator($this->path, 'remove_name_from_dogs_table', 'name:string');
		$generator->generate();
		$this->assertMigrationSame($generator);

		// add
		$generator = new MigrationGenerator($this->path, 'add_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertMigrationSame($generator);
		
		$generator = new MigrationGenerator($this->path, 'insert_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertMigrationSame($generator);
		
		
		$generator = new MigrationGenerator($this->path, 'append_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertMigrationSame($generator);
		
		
		$generator = new MigrationGenerator($this->path, 'update_age_to_dogs_table', 'age:string');
		$generator->generate();
		$this->assertMigrationSame($generator);

		// drop
		$generator = new MigrationGenerator($this->path, 'drop_dogs_table', 'name:string, age:string');
		$generator->generate();
		$this->assertMigrationSame($generator);

		$generator = new MigrationGenerator($this->path, 'destroy_dogs_table', 'name:string, age:string');
		$generator->generate();
		$this->assertMigrationSame($generator);
	}

}