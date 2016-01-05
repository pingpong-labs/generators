<?php

namespace Pingpong\Generators\FormDumpers;

use Pingpong\Generators\Stub;

trait StubTrait
{
    /**
     * The array of types.
     *
     * @var array
     */
    protected $types = [
        'string' => 'text',
        'text' => 'textarea',
        'boolean' => 'checkbox',
    ];

    /**
     * The supported inputs.
     *
     * @var array
     */
    protected $inputs = [
        'text',
        'textarea',
        'checkbox',
        'select',
        'radio',
        'password',
    ];

    /**
     * The array of special input/type.
     *
     * @var array
     */
    protected $specials = [
        'email',
        'password',
    ];

    /**
     * The array of ignores columns.
     *
     * @var array
     */
    protected $ignores = [
        'id',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Get stub template.
     *
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    public function getStub($type, $name)
    {
        if (in_array($name, $this->ignores)) {
            return;
        }

        $type = $this->getInputType($type, $name);

        return Stub::create('/form/'.$type.'.stub', [
            'name' => $name,
            'label' => ucwords(str_replace('_', ' ', $name)),
        ])->render();
    }

    /**
     * Get input type.
     *
     * @param string $type
     * @param string $name
     *
     * @return string
     */
    public function getInputType($type, $name)
    {
        if (in_array($name, $this->specials)) {
            return $name;
        }

        if (array_key_exists($type, $this->types)) {
            return $this->types[$type];
        }

        return in_array($type, $this->inputs) ? $type : 'text';
    }

    /**
     * Get field type.
     *
     * @param array $types
     *
     * @return string
     */
    public function getFieldType($types)
    {
        return array_first($types, function ($key, $value) {
            return $value;
        });
    }
}
