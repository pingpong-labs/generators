<?php

namespace Pingpong\Generators;

class Stub extends \Pingpong\Support\Stub
{
	/**
	 * Get base path.
	 * 
	 * @return string
	 */
	public static function getBasePath()
	{
        return str_finish(config('generators.template_path', __DIR__.'/Stubs/'), '/');
	}
}
