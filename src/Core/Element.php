<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Core;

use ParagonIE\Ionizer\Contract\FilterInterface;
use ParagonIE\Ionizer\Filter\{
    BoolFilter,
    FloatFilter,
    IntFilter,
    StringFilter,
};
use ParagonIE\Ionizer\InputFilter;

/**
 * Class Element
 * @package Soatok\Cupcake\Core
 */
abstract class Element implements IngredientInterface
{
    use NameTrait;
    use StapleTrait;

    protected ?bool $autocomplete = null;
    protected bool $required = false;
    protected ?InputFilter $filter = null;
    protected string $pattern = '';

    /**
     * Ionizer input filter for the given input parameter.
     *
     * @return FilterInterface
     */
    public function getFilter(): FilterInterface
    {
        $filter = $this->filter ? clone $this->filter : new StringInputFilter();
        if ($filter instanceof StringInputFilter && $this->pattern !== '') {
            $filter->setPattern($this->pattern);
        }
        return new FieldFilter($filter, $this->required);
    }

    /**
     * @return string|null
     */
    public function getFilterType(): ?string
    {
        if (!$this->filter) {
            return null;
        }
        if ($this->filter instanceof StringFilter) {
            return 'string';
        }
        if ($this->filter instanceof BoolFilter) {
            return 'bool';
        }
        if ($this->filter instanceof IntFilter) {
            return 'int';
        }
        if ($this->filter instanceof FloatFilter) {
            return 'float';
        }
        return 'mixed';
    }

    /**
     * @param InputFilter $filter
     * @return self
     */
    public function setFilter(InputFilter $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @param string $pattern
     * @return self
     */
    public function setPattern(string $pattern): self
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @param bool $required
     * @return self
     */
    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Returns a map where:
     *
     * - key   -> key to include in flattenAttributes()
     * - value -> property of the object (or NULL if identical to key)
     *
     * @return array<string, ?string>
     */
    #[\Override]
    public function customAttributes(): array
    {
        return [];
    }

    /**
     * Direct key-value pair to include in output
     *
     * @return array<string, string>
     */
    #[\Override]
    public function renderAttributes(): array
    {
        return [];
    }

    /**
     * @return string
     */
    #[\Override]
    abstract public function render(): string;
}
