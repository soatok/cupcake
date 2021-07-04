<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\DoesNotPopulateTrait;
use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class Button
 * @package Soatok\Cupcake\Ingredients
 */
class Button extends Element
{
    use DoesNotPopulateTrait, NameTrait;
    protected string $label;
    protected string $type;

    public function __construct(string $name = '', string $label = '', string $type = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->type = $type;
    }

    /**
     * @param string $label
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function customAttributes(): array
    {
        $attributes = [];
        if (!empty($this->name)) {
            $attributes['name'] = null;
        }
        if (!empty($this->type)) {
            $attributes['type'] = null;
        }
        return $attributes;
    }

    /**
     * Render this button, and everything it contains.
     *
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '<button%s>%s</button>',
            $this->flattenAttributes(),
            Utilities::purify($this->label)
        );
    }
}
