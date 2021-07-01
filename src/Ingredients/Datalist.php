<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\DoesNotPopulateTrait;
use Soatok\Cupcake\Core\NameTrait;

/**
 * Class Datalist
 * @package Soatok\Cupcake\Ingredients
 */
class Datalist extends Container
{
    use DoesNotPopulateTrait, NameTrait;
    protected string $value = '';

    /**
     * Datalist constructor.
     * @param string $id
     * @param Option ...$options
     */
    public function __construct(string $id = '', Option ...$options)
    {
        $this->id = $id;
        foreach ($options as $option) {
            $this->append($option);
        }
    }

    /**
     * Returns a map where:
     *
     * - key   -> key to include in flattenAttributes()
     * - value -> property of the object (or NULL if identical to key)
     *
     * @return array<string, ?string>
     */
    public function customAttributes(): array
    {
        $attributes = [];
        if (!empty($this->name)) {
            $attributes['name'] = null;
        }
        if (!empty($this->value)) {
            $attributes['value'] = null;
        }
        return $attributes;
    }

    /**
     * Render this button, and everything it contains.
     *
     * @return string
     */
    public function renderBefore(): string
    {
        return sprintf(
            '<datalist%s>',
            $this->flattenAttributes()
        );
    }

    /**
     * @return string
     */
    public function renderAfter(): string
    {
        return '</datalist>';
    }

    public function render(): string
    {
        $middle = '';
        /** @var Option $ingredient */
        foreach ($this->ingredients as $ingredient) {
            $middle .= sprintf('<option%s />', $ingredient->flattenAttributes());
        }
        return $this->renderBefore() . $middle . $this->renderAfter();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return static
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }
}
