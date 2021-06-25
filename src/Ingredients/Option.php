<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\Utilities;

/**
 * Class Option
 * @package Soatok\Cupcake\Ingredients
 */
class Option extends Element
{
    protected bool $selected;
    protected string $label;
    protected string $value;

    public function __construct(string $label, string $value, bool $selected = false)
    {
        $this->label = $label;
        $this->value = $value;
        $this->selected = $selected;
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
        $attributes = ['value' => null];
        if ($this->selected) {
            $attributes['selected'] = null;
        }
        return $attributes;
    }

    /**
     * @param string $name
     * @param bool $value
     * @return string
     */
    public function boolPropertyValue(string $name, bool $value): string
    {
        if ($name === 'selected') {
            return $value ? 'selected' : '';
        }
        return parent::boolPropertyValue($name, $value);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '<option%s>%s</option>',
            $this->flattenAttributes(),
            Utilities::escapeAttribute($this->label)
        );
    }
}
