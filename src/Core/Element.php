<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\InputFilter;

/**
 * Class Element
 * @package Soatok\Cupcake\Core
 */
abstract class Element implements IngredientInterface
{
    use StapleTrait;

    protected ?bool $autocomplete = null;
    protected bool $required = false;
    protected ?InputFilter $filter = null;
    protected string $pattern;
    protected string $name;

    /**
     * Ionizer input filter for the given input parameter.
     *
     * @return InputFilter
     */
    public function getFilter(): InputFilter
    {
        if (is_null($this->filter)) {
            throw new \TypeError('Filter is not defined for this element');
        }
        return $this->filter;
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
        return [];
    }

    /**
     * @return string
     */
    abstract public function render(): string;
}
