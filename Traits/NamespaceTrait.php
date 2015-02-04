<?php namespace Pingpong\Generators\Traits;

trait NamespaceTrait {

    /**
     * Get class namespace.
     * 
     * @return string
     */
    public function getNamespace()
    {
        return $this->option('namespace') ? 'namespace ' . $this->option('namespace') : '';
    }

    /**
     * Merge namespace to stub replacements array.
     *
     * @param  array $replacements
     * @return array
     */
    public function appendNamespaceStub(array $replacements = [])
    {
		return array_merge(['NAMESPACE' => $this->getNamespace()], $replacements);    	
    }

}