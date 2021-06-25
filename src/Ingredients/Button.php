<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\NameTrait;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class Button
 * @package Soatok\Cupcake\Ingredients
 */
class Button extends Element
{
    use NameTrait;
    protected string $label;

    public function __construct(string $name = '', string $label = '')
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function customAttributes(): array
    {
        $attributes = [];
        if (!empty($this->name)) {
            $attributes['name'] = null;
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
