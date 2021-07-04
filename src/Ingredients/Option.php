<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Element;
use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Core\Utilities;
use Soatok\Cupcake\Exceptions\CupcakeException;
use Soatok\Cupcake\FilterContainer;

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
     * @param array $untrusted
     * @return IngredientInterface
     * @throws CupcakeException
     */
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        /** @var string[]|string[][] $pointer */
        $pointer = &$untrusted;
        $pieces = explode(FilterContainer::SEPARATOR, $this->getIonizerName());
        foreach ($pieces as $piece) {
            if (!array_key_exists($piece, $pointer)) {
                $this->selected = false;
                return $this;
            }
            /** @var string[]|string[][] $pointer */
            $pointer = &$pointer[$piece];
        }
        /** @var string|string[] $pointer */
        if (is_array($pointer)) {
            return $this;
        }
        $this->selected = $pointer === $this->value;
        return $this;
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
