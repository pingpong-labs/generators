<?php namespace Pingpong\Generators;

use Illuminate\Filesystem\Filesystem;
use Pingpong\Generators\Contracts\GeneratorInterface;

abstract class Generator implements GeneratorInterface {

    /**
     * The filesystem instance.
     * 
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The Constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem;
    }

    /**
     * Get the filesystem instance.
     * 
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the filesystem instance.
     * 
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

}