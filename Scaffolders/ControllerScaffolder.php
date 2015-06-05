<?php

namespace Pingpong\Generators\Scaffolders;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class ControllerScaffolder implements Arrayable
{
    /**
     * The name of controller.
     *
     * @var string
     */
    protected $name;

    /**
     * The prefix option.
     *
     * @var string
     */
    protected $prefix;

    /**
     * The entity name.
     *
     * @var string
     */
    protected $entity;

    /**
     * The constructor.
     *
     * @param string      $name
     * @param string|null $prefix
     */
    public function __construct($name, $prefix = null)
    {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->entity = $this->getEntity();
    }

    /**
     * Get entity name.
     *
     * @return string
     */
    public function getEntity()
    {
        return Str::singular(str_replace('controller', '', strtolower($this->name)));
    }

    /**
     * Get prefix as StudlyCase.
     *
     * @return string
     */
    public function getStudlyPrefix()
    {
        return Str::studly($this->prefix ?: '');
    }

    /**
     * Get entities name as lowercase.
     *
     * @return string
     */
    public function getLowerEntities()
    {
        return strtolower(Str::plural($this->entity));
    }

    /**
     * Get singular name of entity in lowercase.
     *
     * @return string
     */
    public function getLowerSingularEntity()
    {
        return strtolower(Str::singular($this->entity));
    }

    /**
     * Get entity name in StudlyCase.
     *
     * @return string
     */
    public function getStudlyEntity()
    {
        return Str::studly($this->entity);
    }

    /**
     * Get plural entity name in StudlyCase.
     *
     * @return string
     */
    public function getStudlyPluralEntity()
    {
        return Str::plural($this->getStudlyEntity());
    }

    /**
     * Get prefix with dot suffix.
     *
     * @return string
     */
    public function getPrefixDot()
    {
        return $this->prefix ? $this->prefix.'.' : '';
    }

    /**
     * Get prefix with slash suffix.
     *
     * @return string
     */
    public function getPrefixSlash()
    {
        return $this->prefix ? Str::studly($this->prefix.'\\') : '';
    }

    /**
     * Array of replacements.
     *
     * @return string
     */
    public function toArray()
    {
        return [
            'prefix' => $this->prefix,
            'entity' => $this->entity,
            'lower_entities' => $this->getLowerEntities(),
            'lower_singular_entity' => $this->getLowerSingularEntity(),
            'studly_entity' => $this->getStudlyEntity(),
            'studly_plural_entity' => $this->getStudlyPluralEntity(),
            'prefix_dot' => $this->getPrefixDot(),
            'prefix_slash' => $this->getPrefixSlash(),
        ];
    }
}
