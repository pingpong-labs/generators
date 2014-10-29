<?php namespace Pingpong\Generators\Traits;

use Illuminate\Support\Str;

trait StudlyClassNameTrait {

    /**
     * Get class name.
     *
     * @return string
     */
    protected function getClassName()
    {
        return Str::studly($this->name);
    }

}