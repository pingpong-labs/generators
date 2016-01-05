<?php

namespace Pingpong\Generators;

use Exception;

class FileAlreadyExistsException extends Exception
{
    /**
     * The file path.
     * 
     * @var string
     */
    private $path;

    /**
     * Create a new instance of this class.
     * 
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
        parent::__construct('File already exist at path: '.$path);
    }

    /**
     * Gets the value of path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}
