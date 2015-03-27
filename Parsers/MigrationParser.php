<?php

namespace Pingpong\Generators\Parsers;

class MigrationParser {

	protected $customAttributes = [
		'remember_token' => 'rememberToken()',
		'soft_delete' => 'softDeletes()',
	];

	/**
	 * Parse a string to array of formatted schema.
	 * 
	 * @param  string $migration]
	 * @return array
	 */
	public function parse($migration)
	{
		$schemas = explode(',', str_replace(' ', '', $migration));

		$parsed = [];

		foreach ($schemas as $schema)
		{
			$column = $this->getColumn($schema);
			
			$attributes = $this->getAttributes($column, $schema);

			$parsed[$column] = $attributes;	
		}

		return $parsed;
	}

	/**
	 * Get column name from schema.
	 * 
	 * @param  string $schema
	 * @return string
	 */
	public function getColumn($schema)
	{
		return array_first(explode(':', $schema), function ($key, $value)
		{
			return $value;
		});
	}

	/**
	 * Get column attributes.
	 * 
	 * @param  string $column
	 * @param  string $schema
	 * @return array
	 */
	public function getAttributes($column, $schema)
	{
		$fields = str_replace($column.':', '', $schema);

		return $this->hasCustomAttribute($column) ? $this->getCustomAttribute($column) : explode(':', $fields);
	}

	/**
	 * Determinte whether the given column is exist in customAttributes array.
	 * 
	 * @param  string  $column
	 * @return boolean
	 */
	public function hasCustomAttribute($column)
	{
		return array_key_exists($column, $this->customAttributes);
	}

	/**
	 * Get custom attributes value.
	 * 
	 * @param  string $column
	 * @return string|array
	 */
	public function getCustomAttribute($column)
	{
		return (array) $this->customAttributes[$column];
	}

}