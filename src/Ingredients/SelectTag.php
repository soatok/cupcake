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
    public function renderAfter(): string
    {
        return '</select>';
    }
}
