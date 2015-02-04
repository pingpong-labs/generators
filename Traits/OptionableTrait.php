<?php namespace Pingpong\Generators\Traits;

trait OptionableTrait {

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Get the specified option by given key.
     * 
     * @param  string $key     
     * @param  mixed  $default 
     * @return mixed
     */
    public function option($key, $default = null)
    {
        return array_get($this->options, $key, $default);
    }

}