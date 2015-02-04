<?php namespace Pingpong\Generators\Contracts;

interface FileGeneratorInterface {

    /**
     * Get template contents.
     *
     * @return string
     */
    public function getTemplateContents();

    /**
     * Get destination file path.
     *
     * @return string
     */
    public function getDestinationFilePath();

    /**
     * Generate a new file.
     *
     * @return void
     */
    public function generateFile();

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename();

} 