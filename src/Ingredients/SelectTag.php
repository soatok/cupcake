<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\IngredientInterface;
use Soatok\Cupcake\Core\NameTrait;

/**
 * Class SelectTag
 * @package Soatok\Cupcake\Ingredients
 */
class SelectTag extends Container
{
    use NameTrait;

    /** @var array<array-key, Option|Optgroup> $options */
    protected array $options = [];
    protected bool $required = false;
    protected bool $multiple = false;

    public function setRequired(bool $required): self
    {
        $this->required = $required;
        return $this;
    }

    /** For multiple selects, use a name ending in [] so PHP receives an array. */
    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    /** @return list<Option> */
    protected function collectOptions(Container $container, array &$visited): array
    {
        $hash = spl_object_id($container);
        if (isset($visited[$hash])) {
            return [];
        }
        $visited[$hash] = true;
        $options = [];
        foreach ($container->getIngredients() as $ingredient) {
            if ($ingredient instanceof Option) {
                $options[] = $ingredient;
            } elseif ($ingredient instanceof Optgroup) {
                $options = array_merge($options, $this->collectOptions($ingredient, $visited));
            }
        }
        return $options;
    }

    #[\Override]
    protected function getInputFiltersInternal(array &$objectsVisited, array &$inputFilters): void
    {
        if (in_array(spl_object_id($this), $objectsVisited, true)) {
            return;
        }
        $objectsVisited[] = spl_object_id($this);
        if ($this->name === '') {
            return;
        }
        $visited = [];
        $values = [];
        foreach ($this->collectOptions($this, $visited) as $option) {
            $values[] = $option->getValue();
        }
        $inputFilters[$this->getIonizerName()] = new \Soatok\Cupcake\Core\FieldFilter(
            new \Soatok\Cupcake\Core\ChoiceFilter($values, null, $this->multiple),
            $this->required
        );
    }

    #[\Override]
    public function populateUserInput(array $untrusted): IngredientInterface
    {
        $value = $untrusted;
        foreach (explode(\Soatok\Cupcake\FilterContainer::SEPARATOR, $this->getIonizerName()) as $piece) {
            $value = is_array($value) ? ($value[$piece] ?? null) : null;
        }
        $selected = $this->multiple && is_array($value) ? $value : (is_string($value) ? [$value] : []);
        $visited = [];
        foreach ($this->collectOptions($this, $visited) as $option) {
            $option->setSelected(in_array($option->getValue(), $selected, true));
        }
        return $this;
    }

    /**
     * SelectTag constructor.
     * @param string $name
     * @param Option|Optgroup ...$ingredients
     */
    public function __construct(string $name = '', Option|Optgroup ...$ingredients)
    {
        $this->name = $name;
        foreach ($ingredients as $ingredient) {
            $this->append($ingredient);
        }
    }

    /**
     * @param IngredientInterface $ingredient
     * @return Container
     */
    #[\Override]
    public function append(IngredientInterface $ingredient): Container
    {
        if (!($ingredient instanceof Option)) {
            if (!($ingredient instanceof Optgroup)) {
                throw new \TypeError('Select elements can only contain options and optgroups');
            }
        }
        return parent::append($ingredient);
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
        $attributes = [];
        if (!empty($this->name)) {
            $attributes['name'] = null;
        }
        if ($this->required) {
            $attributes['required'] = null;
        }
        if ($this->multiple) {
            $attributes['multiple'] = null;
        }
        return $attributes;
    }

    #[\Override]
    public function boolPropertyValue(string $name, bool $value): string
    {
        return in_array($name, ['required', 'multiple'], true)
            ? ($value ? $name : '')
            : parent::boolPropertyValue($name, $value);
    }

    /**
     * Render this button, and everything it contains.
     *
     * @return string
     */
    #[\Override]
    public function renderBefore(): string
    {
        return sprintf(
            '<select%s>',
            $this->flattenAttributes()
        );
    }

    /**
     * @return string
     */
    #[\Override]
    public function renderAfter(): string
    {
        return '</select>';
    }
}
