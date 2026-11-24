<?php
declare(strict_types=1);
namespace Soatok\Cupcake\Ingredients;

use Soatok\Cupcake\Core\Container;
use Soatok\Cupcake\Core\IngredientInterface;

/**
 * Class Optgroup
 * @package Soatok\Cupcake\Ingredients
 */
class Optgroup extends Container
{
    protected string $label;

    public function __construct(string $label = '', Option ...$options)
    {
        $this->label = $label;
        foreach ($options as $option) {
            $this->append($option);
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
            throw new \TypeError('Optgroups can only contain options');
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
        return ['label' => null];
    }

    /**
     * Override this in inherited classes.
     *
     * @return string
     */
    #[\Override]
    public function renderAfter(): string
    {
        return '</optgroup>';
    }

    /**
     * Override this in inherited classes.
     *
     * @return string
     */
    #[\Override]
    public function renderBefore(): string
    {
        return sprintf('<optgroup%s>', $this->flattenAttributes());
    }
}
